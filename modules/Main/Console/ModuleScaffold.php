<?php

namespace Module\Main\Console;

use Illuminate\Console\Command;

class ModuleScaffold extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold new CMS Module';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public $name, $hint;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $name = $this->ask('Please insert module name');
        $name = str_replace(' ', '', ucwords($name));
        $this->name = $name;

        $hint = strtolower($this->name);
        $this->hint = $hint;

        $this->dual = $this->confirm('Do you need dual language support in this module?');


        //bikin module dir dulu kalau blm ada
        if(!is_dir(base_path('modules'))){
            mkdir(base_path('modules', 0755, true));
        }

        $base_dir = base_path('modules');
        $path = realpath($base_dir . '/'.$name);
        if($path){
            $this->error('Directory ' . $path . ' is exists. Please try using another module name');
        }
        else{
            $module_dir = $base_dir .'/'.$name;
            mkdir($module_dir, 0755);
            copy_directory(__DIR__ .'/StubModule', $module_dir);
            $this->info('Scaffolding file copied successfully');

            
            //manual rename file.
            rename($module_dir .'/BlankServiceProvider.php', $module_dir .'/'.$name.'ServiceProvider.php');

            if($this->dual){
                unlink($module_dir .'/Models/Blank');
                unlink($module_dir .'/Http/Skeleton/BlankSkeleton');
                unlink($module_dir .'/Http/Controllers/BlankController');

                //rename dual name
                rename($module_dir .'/Http/Skeleton/BlankSkeletonDual', $module_dir .'/Http/Skeleton/'.$name.'Skeleton.php');
                rename($module_dir .'/Models/BlankDual', $module_dir .'/Models/Blank.php');
                rename($module_dir .'/Http/Controllers/BlankControllerDual', $module_dir .'/Http/Controllers/'.$name.'Controller.php');
            }
            else{
                unlink($module_dir .'/Models/BlankDual');
                unlink($module_dir .'/Http/Skeleton/BlankSkeletonDual');
                unlink($module_dir .'/Http/Controllers/BlankControllerDual');

                //rename dual name
                rename($module_dir .'/Http/Skeleton/BlankSkeleton', $module_dir .'/Http/Skeleton/'.$name.'Skeleton.php');
                rename($module_dir .'/Models/Blank', $module_dir .'/Models/Blank.php');
                rename($module_dir .'/Http/Controllers/BlankController', $module_dir .'/Http/Controllers/'.$name.'Controller.php');
            }

            rename($module_dir .'/Migrations/2018_08_25_000000_blank.php', $module_dir .'/Migrations/2018_08_25_000000_'.$hint.'.php');
            rename($module_dir .'/Models/Blank.php', $module_dir .'/Models/'.$name.'.php');


            //rename file content
            self::changeContent($module_dir .'/'.$name.'ServiceProvider.php');
            self::changeContent($module_dir .'/'.$name.'ServiceProvider.php');
            self::changeContent($module_dir .'/Http/Controllers/'.$name.'Controller.php');
            self::changeContent($module_dir .'/Http/Skeleton/'.$name.'Skeleton.php');
            self::changeContent($module_dir .'/Migrations/2018_08_25_000000_'.$hint.'.php');
            self::changeContent($module_dir .'/Routes/api.php');
            self::changeContent($module_dir .'/Routes/web.php');
            self::changeContent($module_dir .'/Config/cms.php');
            self::changeContent($module_dir .'/Config/model.php');
            self::changeContent($module_dir .'/Config/permission.php');
            self::changeContent($module_dir .'/Config/module-setting.php');
            self::changeContent($module_dir .'/Models/'.$name.'.php');

            $this->info('New module has been created for you. Now you just need to register the service provider (in config/modules.php or in config/app.php) , manage migration, manage the model and skeleton.');
        }

    }



    protected function changeContent($path){
        $content = file_get_contents($path);
        $content = str_replace('Blank', $this->name, $content);
        $content = str_replace('blank', $this->hint, $content);

        file_put_contents($path, $content);
    }

}
