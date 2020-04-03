<?php

namespace Core\Main\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SetRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:role';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create all permissions for admin role';

    /**
     * Create a new command instance.
     *
     * @return void
     */
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
        $this->actionRunner();
        $this->info('Super Admin full permission has been created');
    }

    public function actionRunner(){
        //
        $lists = config('permission');
        $out = [];

        if(empty($lists)){
            //config blm kebaca -_-
            try{
                $lists = include(__DIR__.'/../Config/permission.php');
            }catch(\Exception $e){
                //do nothing
                //kalo masih ga kebaca juga yaudah
            }
        }

        if(is_array($lists)){
            foreach($lists as $data){
                foreach($data as $title => $lists){
                    $out = array_merge($out, $lists);
                }
            }
        }

        $out = json_encode($out);

        //super admin ga butuh list priviledge        
        $cek = DB::table('roles')->where('is_sa', 1);
        $save_data = [
            'name' => 'Super Admin',
            'priviledge_list' => null,
            'is_sa' => 1
        ];
        if($cek->count() == 0){
            DB::table('roles')->insert($save_data);
        }
        else{
            $cek->update($save_data);
        }

        //generate normal admin data
        $normal = DB::table('roles')->whereNull('is_sa')->first();
        if(empty($normal)){
            DB::table('roles')->insert([
                'name' => 'Admin',
                'priviledge_list' => $out
            ]);
        }
    }
}
