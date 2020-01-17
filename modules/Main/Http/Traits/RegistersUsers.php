<?php

namespace Module\Main\Http\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Mail;
use Module\Main\Mail\MainMail;

trait RegistersUsers
{
    use RedirectsUsers;

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        if(!config('cms.admin.components.register')){
            return redirect(admin_url());
        }
        return view('main::auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user_data = $this->create($request->all());
        \CMS::log($user_data, 'ADMIN REGISTER');
        self::sendActivationMail($user_data);
        //user register baru bisa login setelah divalidasi oleh admin
        return redirect(admin_url('login'))->with([
        	'success' => 'Registration success.We have sent the validation link to your email. Please click the activation link first.'
        ]);

    }

    protected function sendActivationMail($instance){
        $mail = new MainMail();
        $mail->setSubject('Validation Request for ' . setting('site.title'));
        $mail->setTitle('User Registration confirmation');
        $mail->setContent('Hi, '.$instance->name.'. We just receive registration request for this email account. Please click the activation link below to activate your account now.');
        $mail->setButton([
            'label' => 'Activate Your Account',
            'url' => admin_url('activate/'.$instance->activation_key)
        ]);

        Mail::to($instance->email)
            ->send($mail);
    }


}
