<?php

namespace Core\Main\Console;

use Illuminate\Console\Command;
use Core\Main\Mail\MainMail;
use niklasravnsborg\LaravelPdf\Facades\Pdf as PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class SendLogMaster extends Command
{
    protected $signature = 'logmaster:sendmail';
    protected $description = 'Send site error report to admin';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(){
        if(!setting('log.active')){
            return $this->error('You are not using mail logging feature.');
        }
        if(!setting('log.email_receiver')){
            return $this->error('You are not set the log email receiver yet');
        }

        //proses kirim email
        $data = model('log_master')->where('is_reported', 0)->orderBy('created_at', 'DESC')->get();
        if($data->count() == 0){
            return $this->info('No error log data to be sent');
        }

        //generate PDF report
        $pdf = PDF::loadView('main::module.log-pdf', compact(
            'data'
        ));
        $savepath = 'Log-Exception-Report.pdf';
        $pdf->save(Storage::path($savepath));

        $mail = new MainMail;
        $mail->setSubject("Error Exception Report for Site " . url('/'));
        $mail->setTitle('Site Error Exception Report');
        $msg = '<p>Hi, this is an automatic email feature to report that your site '.url('/').' has just experienced an error. Here is some quick reference of the latest error occured in your site : </p>';

        foreach($data as $row){
            $msg .= '<p><strong>'.($row->type ?? 'UNDEFINED').'</strong> : '.$row->description.' ('. $row->url .')</p>';
        }
        $msg .= '<p>You can have more information about all the error with the exception details attached below. Ignore this email if the error message is already fixed, please recheck the site to fix these problems. Thank you</p>';
        $mail->setContent($msg);
        $mail->setButton([
            'url' => admin_url('/'),
            'label' => 'Go to Admin Panel'
        ]);
        $mail->setFile([
            Storage::path($savepath)
        ]);

        Mail::to(setting('log.email_receiver'))
            ->send($mail);

        //set log to is_reported=1 after send this message
        $ids = $data->pluck('id')->toArray();
        model('log_master')->whereIn('id', $ids)->update([
            'is_reported' => 1
        ]);

        $this->info('Error reporting mail has been sent to ' . setting('log.email_receiver'));
    }

}
