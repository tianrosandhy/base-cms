<?php
namespace Module\Main\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends AdminBaseController
{

	public function index(){
		$title = 'Dashboard';
		return view('main::module.dashboard', compact('title'));
	}

	public function switchLang($lang=''){
		set_lang($lang);
		return [
			'type' => 'success'
		];
	}

}