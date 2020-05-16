<?php
namespace Core\Main\SettingExtension;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Exception;
use Closure;

class DatabaseStructureModifier
{
	public 
        $command_run_count,
        $info;

    public function __construct(){
        $this->info = [];
    }

    // method called for update/add/drop schema fields
    public function handleTable($tb_name, Closure $table_function){
        try {
            Schema::table($tb_name, $table_function);
            $this->addInfo($tb_name.' field has been updated');
            $this->command_run_count++;
        } catch (Exception $e) {}
    }

    protected function addInfo($string_msg){
        $this->info[] = $string_msg;
    }

    public function getCommandRunCount(){
    	return $this->command_run_count;
    }

    public function getInfo(){
        return $this->info;
    }

}