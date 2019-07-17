<?php
namespace Module\Main\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends AdminBaseController
{

	public function index(){
        $post = $project = $user = null;

        if(\Route::has('admin.post.index')){
			$post = \DB::table('posts')->where('is_active', 1)->count();
		}
		if(\Route::has('admin.project.index')){
			$project = \DB::table('projects')->where('is_active', 1)->count();
		}
		if(\Route::has('admin.user.index')){
			$user = \DB::table('users')->where('is_active', 1)->count();
		}
		return view('main::dashboard', compact(
			'post',
			'project',
			'user'
		));
	}

	public function switchLang($lang=''){
		set_lang($lang);
		return [
			'type' => 'success'
		];
	}

}