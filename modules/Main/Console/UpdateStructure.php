<?php

namespace Module\Main\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Exception;

class UpdateStructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:structure';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Implementasi perubahan struktur di local dan staging / production';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(){
/*
        try{
            Schema::table('tbname', function($table){

            });
            $this->info('Updated tbname fields');
        }catch(Exception $e){}
*/
    }

}