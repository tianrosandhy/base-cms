<?php
namespace Module\Main\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Module\Main\Http\Repository\CrudRepository;
use ImageService;
use Mail;
use Module\Main\Mail\MainMail;
use Module\Main\Http\Skeleton\UserSkeleton;
use Module\Main\Transformer\Exportable;

class UserManagementController extends AdminBaseController
{
	use Exportable;
	public $hint = 'user';

	public function repo(){
		return $this->hint;
	}

	public function skeleton(){
		return new UserSkeleton();
	}

	public function languageData(){
		return [
			'index.title' => 'User Management',
			'create.title' => 'Add New User',
			'edit.title' => 'Edit User Data',

			'store.success' => 'User data has been saved',
			'update.success' => 'User data has been updated',
			'delete.success' => 'User data has been deleted',
		];
	}


	//store dan update dibuat manual karena logicnya sedikit beda dari menu lain

	public function store(){
		$this->skeleton->formValidation(false, 'create');

		//pengecekan password confirmednya manual
		$pass1 = ($this->request->password);
		$pass2 = ($this->request->password_confirmation);
		if($pass1 <> $pass2) {
			return back()->withErrors([
				'password' => 'Password confirmation doesn\'t match'
			]);
		}



		//proses simpan
		$this->repo->insert([
			'name' => ($this->request->name),
			'email' => ($this->request->email),
			'password' => bcrypt(($this->request->password)),
			'role_id' => ($this->request->role_id),
			'image' => ($this->request->image),
			'is_active' => 1
		]);

		return redirect()->route('admin.user.index')->with('success', 'User data has been saved');
	}

	public function update($id=0){
		$this->skeleton->formValidation(false, 'update', $id);
		$show = $this->repo->show($id);
		if(empty($show)){
			abort(404);
		}

		//pengecekan password confirmednya manual
		$pass1 = ($this->request->password);
		$pass2 = ($this->request->password_confirmation);

		if(strlen($pass1) > 0 || strlen($pass2) > 0){
			if($pass1 <> $pass2) {
				return back()->withErrors([
					'password' => 'Password confirmation doesn\'t match'
				]);
			}

			if(strlen($pass1) < 6){
				return back()->withErrors([
					'password' => 'Password need to be at least 6 character length'
				]);
			}
		}


		$post['name'] = ($this->request->name);
		$post['email'] = ($this->request->email);
		$post['role_id'] = ($this->request->role_id);
		$post['image'] = ($this->request->image);

		$pw = ($this->request->password);
		$pw_conf = ($this->request->password_confirmation);

		if(strlen($pw) > 0){
			if(strlen($pw) < 0){
				return back()
					->withErrors(['password' => 'Password is too short. Please use at least 6 characters'])
					->withInput();
			}
			if($pw <> $pw_conf){
				return back()
					->withErrors(['password' => 'Password confirmation is not match'])
					->withInput();
			}
			$post['password'] = bcrypt($pw);
		}

		$oldPriv = $show->role_id;

		$this->repo->update($id, $post);

		if($oldPriv == 0 && ($this->request->role_id) > 0){
			//send notif ke user kalo dia udah diapprove sbg user
			self::sendUserInfoMail($show);
		}

		return redirect()->route('admin.user.index')->with('success', 'User data has been updated');

	}

	protected function sendUserInfoMail($instance){
		$mail = new MainMail();
		$mail->setSubject('Your registration to '. setting('site.title').' admin panel has been approved.');
		$mail->setTitle('Registration Acceptance Information');
		$mail->setContent('Hi, '. $instance->name .', your registration to admin panel of '. setting('site.title') .' has been approved by admin. Now you can login with the given priviledge. Click the button below if you want to go now.');
		$mail->addButton([
			'label' => 'Login to Admin Panel',
			'url' => admin_url('')
		]);

		Mail::to($instance->email)
			->send($mail);
	}





}