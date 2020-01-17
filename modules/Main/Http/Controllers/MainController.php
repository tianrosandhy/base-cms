<?php
namespace Module\Main\Http\Controllers;

use Illuminate\Http\Request;
use Module\Main\Http\Repository\AnalyticRepository;

class MainController extends AdminBaseController
{

	public function index(){
		$title = 'Dashboard';
		$analytic = new AnalyticRepository;
		return view('main::module.dashboard', compact('title', 'analytic'));
	}

	public function switchLang($lang=''){
		set_lang($lang);
		return [
			'type' => 'success'
		];
	}

}