<?php

namespace Core\Main\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Exception;
use Closure;

class UpdateStructure extends Command
{
    protected $signature = 'update:structure';
    protected $description = 'Implementasi perubahan struktur di local dan staging / production';
    public $command_run_count = 0;

    public function __construct()
    {
        parent::__construct();
    }

    public function structureUpdateCommands(){
/*
        // you can call handleAddField() method as much as you need here.
        //example : 
*/

/*
        $this->handleAddField('table_name', function($table){
            $table->datatype('field_name')->nullable();
            $table->datatype('field_name')->nullable();
            $table->datatype('field_name')->nullable();
        });
*/

    }







    protected function handleAddField($tb_name, Closure $add_table_fn){
        try {
            Schema::table($tb_name, $add_table_fn);
            $this->info($tb_name.' field has been updated');
            $this->command_run_count++;
        } catch (Exception $e) {}
    }

    public function handle(){
        $this->structureUpdateCommands();
        if($this->command_run_count == 0){
            $this->info('Hooray! Your database structured is already the most updated');
        }
    }


}