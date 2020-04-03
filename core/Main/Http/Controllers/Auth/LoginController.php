<?php

namespace Core\Main\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Core\Main\Http\Traits\AuthenticatesUsers;
use Core\Main\Http\Repository\CrudRepository;
use Socialite;
use Core\Main\Http\Middleware\RedirectIfAuthenticated;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $maxAttempts = 5;
    protected $decayMinutes = 10;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(RedirectIfAuthenticated::class)->except('logout');
    }


    public function redirectTo(){
        return session('redirect') ? session('redirect') : admin_url();
    }

    public function socialRedirect($type=''){
        $type = strtolower($type);
        $social_validator = $this->getSocialLoginError($type);
        if($social_validator){
            return $social_validator;
        }

        return Socialite::driver($type)->with([
            'prompt' => 'select_account'
        ])->redirect();
    }

    public function socialRedirectHandle($type=''){
        $type = strtolower($type);
        $social_validator = $this->getSocialLoginError($type);
        if($social_validator){
            return $social_validator;
        }

        $user = Socialite::with($type)->user();

        if(isset($user->email)){
            $instance = (new CrudRepository('user'))->show($user->email, 'email');
            if($instance){
                if($instance->is_active){
                    admin_guard()->loginUsingId($instance->id);
                    return redirect($this->redirectTo());
                }
                else{
                    return redirect()->route('login')->with('error', 'User is not activated yet');
                }
            }
        }
        return redirect()->route('login')->with('error', 'User not found.');
    }

    protected function getSocialLoginError($type=''){
        if(!config('cms.social_login')){
            return redirect()->route('login')->with('Social login is disabled. You cannot login with social credentials until you reenable in CMS Settings');
        }
        if(!in_array($type, config('cms.social_driver'))){
            return redirect()->route('login')->with('Social login ' . $type .' is not registered in system');
        }
        return false;
    }
}
