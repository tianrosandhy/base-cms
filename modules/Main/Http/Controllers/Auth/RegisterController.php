<?php

namespace Module\Main\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Module\Main\Http\Traits\RegistersUsers;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    public $request;
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $req)
    {
        $this->middleware(RedirectIfAuthenticated::class)->except('logout');
        $this->request = $req;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        //email yg format nonstandar harus diconvert ke format standar dulu
        /*
        Ex : 
        tian.rosa.ndhy@gmail.com        -> tianrosandhy@gmail.com
        tian.rosandhy+12387@gmail.com   -> tianrosandhy@gmail.com

        2 email diatas kalo ga diconvert, nanti bakalan duplikat karena menuju ke 1 mail username yg sama
        */
        $data['real_email'] = mail_reformat($data['email']);

        return Validator::make($data, [
            'name' => 'required|string|max:255|unique:users',
            'real_email' => 'required|strict_mail|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'toc' => 'required'
        ], [
            'real_email.required' => 'Please fill the email',
            'real_email.strict_mail' => 'The email format is invalid. Please try again',
            'real_email.unique' => 'Email is already exists',
            'toc.required' => 'Please accept the terms and conditions'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = new User();

        $user->name = $data['name'];
        $user->email = mail_reformat($data['email']);
        $user->password = bcrypt($data['password']);
        $user->role_id = 0;
        $user->activation_key = sha1(uniqid() . time() . rand(1, 999999));
        $user->is_active = 0;

        $user->save();
        \CMS::log($user, 'ADMIN REGISTER');
        return $user;
    }
}
