<?php
namespace Module\Main\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends AdminBaseController
{

	public function index(){
		return view('main::module.dashboard');
	}

	public function switchLang($lang=''){
		set_lang($lang);
		return [
			'type' => 'success'
		];
	}

}