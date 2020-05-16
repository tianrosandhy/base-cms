<?php

namespace Core\Main\Console;

use Illuminate\Console\Command;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use FilesystemIterator;

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
    public $name, $hint, $module_dir;

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
            $this->module_dir = $module_dir;
            mkdir($module_dir, 0755);
            copy_directory(__DIR__ .'/StubModule', $module_dir);
            $this->info('Scaffolding file copied successfully');

            $this->renameAllStubToPhp();

            //manual rename file.
            $this->renameModule('BlankServiceProvider.php');

            if($this->dual){
                $this->removeFiles([
                    'Models/Blank.php',
                    'Http/Skeleton/BlankSkeleton.php',
                    'Http/Controllers/BlankController.php',
                ]);

                //rename dual name
                $this->renameModules([
                    'Http/Controllers/BlankControllerDual.php',
                    'Http/Skeleton/BlankSkeletonDual.php',
                    'Models/BlankDual.php',
                ]);

            }
            else{
                $this->removeFiles([
                    'Models/BlankDual.php',
                    'Http/Skeleton/BlankSkeletonDual.php',
                    'Http/Controllers/BlankControllerDual.php',
                ]);

                //rename dual name
                $this->renameModules([
                    'Http/Controllers/BlankController.php',
                    'Http/Skeleton/BlankSkeleton.php',
                    'Models/Blank.php',
                ]);
            }

            $this->renameModules([
                'Http/Controllers/Extensions/BlankExtension.php',
                'Http/Controllers/Extensions/BlankCrudExtension.php',
                'Http/Controllers/Extensions/BlankDeleteExtension.php',
                'Http/Controllers/Extensions/BlankFormExtension.php',
                'Http/Controllers/Extensions/BlankIndexExtension.php',
                'Migrations/2018_08_25_000000_blank.php',
                'Exceptions/BlankException.php',
                'Facades/BlankFacade.php',
                'Services/BlankInstance.php'
            ]);


            //rename file content
            $this->changeContents([
                $name.'ServiceProvider.php',
                'Http/Controllers/'.$name.'Controller.php',
                'Http/Controllers/Extensions/'.$name.'Extension.php',
                'Http/Controllers/Extensions/'.$name.'CrudExtension.php',
                'Http/Controllers/Extensions/'.$name.'DeleteExtension.php',
                'Http/Controllers/Extensions/'.$name.'FormExtension.php',
                'Http/Controllers/Extensions/'.$name.'IndexExtension.php',
                'Http/Skeleton/'.$name.'Skeleton.php',
                'Migrations/2018_08_25_000000_'.$hint.'.php',
                'Translation/en/module.php',
                'Routes/api.php',
                'Routes/web.php',
                'Config/cms.php',
                'Config/model.php',
                'Config/permission.php',
                'Config/module-setting.php',
                'Models/'.$name.'.php',
                'Exceptions/'.$name.'Exception.php',
                'Facades/'.$name.'Facade.php',
                'Services/'.$name.'Instance.php',
                'SettingExtender/ModuleExtender.php',
                'SettingExtender/MigrationModifier.php',
                'Views/partials/crud/after-form.blade.php',
                'Views/partials/crud/before-form.blade.php',
                'Views/partials/index/after-table.blade.php',
                'Views/partials/index/before-table.blade.php',
                'Views/partials/index/control-button.blade.php',
            ]);

            $this->info('New module has been created for you. Now you just need to register the service provider (in config/modules.php or in config/app.php) , manage migration, manage the model and skeleton.');
        }

    }


    protected function renameAllStubToPhp(){
        $path = $this->module_dir;
        $di = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        //rename .stub -> .php
        foreach($di as $fname => $fio) {
            $file_full_path = $fio->getPath() . DIRECTORY_SEPARATOR . $fio->getFilename();
            if(strpos($file_full_path, '.stub') !== false){
                rename($file_full_path, str_replace('.stub', '.php', $file_full_path));
            }
        }
    }


    protected function removeFiles($list_of_deleted_path=[]){
        foreach($list_of_deleted_path as $path){
            $first_char = substr($path, 0, 1);
            if(!in_array($first_char, ['/', '\\', DIRECTORY_SEPARATOR])){
                $path = DIRECTORY_SEPARATOR . $path;
            }
            try{
                unlink($this->module_dir . $path);
            }catch(\Exception $e){
                //do nothing but show error
                $this->error($this->module_dir . $path .' is cannot be deleted because the path was not found');
            }


        }
    }

    protected function renameModules($list_of_path=[]){
        foreach($list_of_path as $path){
            $this->renameModule($path);
        }
    }

    protected function renameModule($module_path){
        $first_char = substr($module_path, 0, 1);
        if(!in_array($first_char, ['/', '\\', DIRECTORY_SEPARATOR])){
            $module_path = DIRECTORY_SEPARATOR . $module_path;
        }

        $rename_path = str_replace('blank', $this->hint, $module_path);
        $rename_path = str_replace('Blank', $this->name, $rename_path);
        $rename_path = str_replace('.stub', '.php', $rename_path);

        rename($this->module_dir.$module_path, $this->module_dir.$rename_path);
    }



    protected function changeContents($list_of_path){
        foreach($list_of_path as $path){
            $this->changeContent($path);
        }
    }

    protected function changeContent($path){
        $first_char = substr($path, 0, 1);
        if(!in_array($first_char, ['/', '\\', DIRECTORY_SEPARATOR])){
            $path = DIRECTORY_SEPARATOR . $path;
        }

        $content = file_get_contents($this->module_dir . $path);
        $content = str_replace('Blank', $this->name, $content);
        $content = str_replace('blank', $this->hint, $content);

        file_put_contents($this->module_dir . $path, $content);
    }

}
