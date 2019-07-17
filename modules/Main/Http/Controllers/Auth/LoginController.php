<?php

namespace Module\Main\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Module\Main\Http\Traits\AuthenticatesUsers;

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
        $this->middleware('guest')->except('logout');
    }


    public function redirectTo(){
        return session('redirect') ? session('redirect') : admin_url();
    }
}
