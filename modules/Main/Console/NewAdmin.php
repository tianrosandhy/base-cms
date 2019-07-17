<?php

namespace Module\Main\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Artisan;

class NewAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new administrator with full priviledge';

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
        //
        $email = $this->ask('Please insert the email');
        //cek email exists or not
        $warn = self::validateEmail($email);
        if($warn){
            $this->error($warn);
            exit();
        }
        if(self::checkEmailUsed($email)){
            $this->error('Email is already used for another account');
            exit();
        }


        $username = $this->ask('Type your name');
        $password = $this->secret('Type your password');
        if(strlen($password) < 6){
            $this->error('Your password too short. Please use at least 6 characters');
            exit();
        }

        if($this->confirm('We will create new admin for user '.$username.' with email '.$email.'. Do you wish to continue? ')){
            //create user
            self::createUser($email, $username, $password);
            Artisan::call('make:role');
            Artisan::call('default:setting'); //init setting baru setiap kali bikin admin. in case settingnya udah ada ya gapapa
            $this->info('Your admin account has been made. You can login with this credential now.');
        }
    }

    protected function checkEmailUsed($email){
        $n = DB::table('users')->where('email', $email)->count();
        if($n > 0)
            return true;
        return false;
    }

    protected function validateEmail($email){
        $validate = Validator::make(['email' => $email], [
            'email' => 'required|email'
        ]);
        if($validate->fails()){
            return $validate->messages()->first();
        }
        return false;
    }

    protected function createUser($email, $username, $password){
        DB::table('users')->insert([
            'name' => $username,
            'email' => $email,
            'password' => bcrypt($password),
            'role_id' => 1, //default
            'image' => '',
            'activation_key' => null,
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
