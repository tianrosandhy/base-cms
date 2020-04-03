<?php

namespace Core\Main\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Core\Main\Http\Repository\CrudRepository;
use Mail;
use Core\Main\Mail\MainMail;
use Auth;

class ForgotPasswordController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $request;

    public function __construct(Request $req)
    {
        $this->middleware('guest');
        $this->request = $req;
    }

    public function index(){
        Validator::make($this->request->all(), [
            'email' => 'email|required'
        ])->validate();

        $user = new CrudRepository('user');
        $user = $user->show($this->request->email, 'email');
        if(empty($user)){
            return back()->withErrors(['error' => 'Email not found.']);
        }

        $user->remember_token = sha1(uniqid() . time() . rand(1, 100000));
        $user->save();

        \CMS::log($user, 'ADMIN FORGOT PASSWORD');

        //kalo ada, langsung kirim aja
        $checkSmtp = checkSmtp();
        if(!$checkSmtp){
            $mail = new MainMail();
            $mail->setSubject('New password reset request for ' . setting('site.title') . ' admin panel account');
            $mail->setTitle('Password Reset Request');
            $mail->setContent('We just receive the order to reset your account. If you dont make such request, you can just ignore this email. To change your password, just click the button below.');
            $mail->setButton([
                'label' => 'Reset Password',
                'url' => admin_url('reset-password/'.$user->remember_token)
            ]);
            Mail::to($user->email)
                ->send($mail);
        }
        else{
            return back()->withErrors(['error' => 'Error SMTP : ' . $checkSmtp]);
        }

        return back()->with('success', 'Email password request has been sent to your email.');
    }


    public function changePassword($hash){
        $repo = new CrudRepository('user');
        $user = $repo->show($hash, 'remember_token');
        if(empty($user)){
            abort(404);
        }

        return view('main::auth.reset-password', compact(
            'user'
        ));
    }

    public function applyPassword($hash){
        $repo = new CrudRepository('user');
        $user = $repo->show($hash, 'remember_token');
        if(empty($user)){
            abort(404);
        }

        $validate = Validator::make($this->request->all(), [
            'password' => 'required|confirmed|min:8'
        ])->validate();

        \CMS::log($this->request->all(), 'ADMIN CHANGE PASSWORD');
        $user->password = bcrypt($this->request->password);
        $user->remember_token = null;
        $user->save();

        //sekalian anggap login juga bole
        if($user->is_active == 1 && $user->role_id > 0){
            admin_guard()->attempt([
                'email' => $user->email,
                'password' => $this->request->password
            ]);
        }

        return redirect(admin_url(''))->with('success', 'Your password has been updated. Now you can login with this new password');

    }

}
