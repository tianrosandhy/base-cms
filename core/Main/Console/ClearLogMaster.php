<?php

namespace Core\Main\Console;

use Illuminate\Console\Command;
use Core\Main\Mail\MainMail;
use niklasravnsborg\LaravelPdf\Facades\Pdf as PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class ClearLogMaster extends Command
{
    protected $signature = 'logmaster:clear';
    protected $description = 'Clear log masters data';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(){
    	$data = model('log_master')->where('id', '>', 0)->delete();
        $this->info('Log masters data has been deleted');
    }

}
