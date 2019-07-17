<?php

namespace Module\Main\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Module\Main\Http\Controllers\AdminBaseController;
use Validator;
use Auth;
use ImageService;

class ProfileController extends AdminBaseController
{

	public function index(){
		return view('main::auth.profile');
	}

	public function store(){
		self::validateInput();
		$post = [
			'name' => $this->request->name,
			'email' => $this->request->email,
			'image' => $this->request->image,
		];
		if(strlen($this->request->password) > 0){
			//validate password
			Validator::make($this->request->only('password', 'password_confirmation'), [
				'password' => 'required|confirmed|min:7'
			], [
				'password.confirmed' => 'The password confirmation field is not matched',
				'password.min' => 'Please input at least 7 characters'
			])->validate();

			$post['password'] = bcrypt($this->request->password);
		}


		$user = Auth::user();
		//delete old image
		ImageService::removeImage($user->image);

		foreach($post as $field => $value){
			$user->{$field} = $value;
		}
		$user->save();

		\CMS::log($user, 'ADMIN UPDATE PROFILE');

		return back()->with('success', 'Your profile has been updated');
	}

	protected function validateInput(){
		return Validator::make($this->request->all(), [
			'name' => 'required',
			'email' => 'required|email|unique:users,email,'.admin_data('id'),
		])->validate();
	}

}
