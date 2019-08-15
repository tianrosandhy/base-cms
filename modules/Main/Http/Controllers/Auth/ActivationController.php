<?php

namespace Module\Main\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Module\Main\Http\Repository\CrudRepository;
use Mail;
use Module\Main\Mail\MainMail;

class ActivationController extends Controller
{

    public function __construct(Request $req)
    {
        $this->middleware('guest')->except('logout');
        $this->request = $req;
    }


    public function index($hash){
    	$repo = new CrudRepository('user');
    	$cek = $repo->filterFirst([
    		['activation_key', '=', $hash]
    	]);

    	if(empty($cek)){
    		abort(404);
    	}

    	if($cek->is_active != 0){
    		return redirect(admin_url('login'))->withErrors(['error' => 'Your account is already activated.']);
    	}
    	else{
    		$cek->is_active = 1;
    		$cek->save();

    		//send email notif ke admin
    		$mail = new MainMail();
    		$mail->setSubject('New admin register in admin panel '. setting('cms.site_title'));
    		$mail->setTitle('New User Registration Notification');
    		$mail->setContent('Hi admin, we just need to inform you that we just have a new admin registered to our site. Please manage the user priviledge before the user can sign in too. If you dont want this user to be registered, feel free to just delete that account.');
    		$mail->setReason('You receive this email because you are the admin of this site');
    		$mail->setButton([
    			'label' => 'Go to Admin Panel',
    			'url' => admin_url('setting/user')
    		]);

    		Mail::to(config('cms.admin.email_receiver'))
    			->send($mail);

    		return redirect(admin_url('login'))->with('success', 'Thank you, your email is has been validated. Now our admin team will activate your account.');
    	}
    }



    public function resend(){
        $user = new CrudRepository('user');
        $data = $user->show($this->request->email, 'email');
        if(empty($data)){
            return back()->with(['error' => 'Email not found']);
        }

        if($data->is_active == 1){
            if($data->role_id == 0){
                return back()->with(['error' => 'Your email is already activated. Please wait until the admin give you the proper priviledge first.']);
            }

            return back()->with(['error' => 'Email is already validated']);
        }


        if(empty($data->activation_key)){
            $data->activation_key = sha1(uniqid() . time() . rand(1, 999999));
            $data->save();
        }

        $mail = new MainMail();
        $mail->setSubject('Validation Request for ' . setting('site.title'));
        $mail->setTitle('User Registration confirmation');
        $mail->setContent('Hi, '.$data->name.'. We just receive registration request for this email account. Please click the activation link below to activate your account now.');
        $mail->setButton([
            'label' => 'Activate Your Account',
            'url' => admin_url('activate/'.$data->activation_key)
        ]);

        $now = time();
        $max_timelimit = 120;
        if($now - strtotime($data->updated_at) < $max_timelimit){
            return back()->with(['error' => 'Please wait at least ' . ($max_timelimit - ($now - strtotime($data->updated_at))) .' seconds before resend the email again.']);
        }

        Mail::to($data->email)
            ->send($mail);

        return back()->with('success', 'The validation link has been sent to your email.');
    }



}
