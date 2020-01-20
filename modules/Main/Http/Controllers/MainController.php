<?php
namespace Module\Main\Http\Controllers;

use Illuminate\Http\Request;
use Module\Main\Http\Repository\AnalyticRepository;

class MainController extends AdminBaseController
{

	public function index(){
		$title = 'Dashboard';
		$datediff = 30; //normalnya analytic dalam date range bulanan
		$period_string = 'this month';
		if(is_array($this->request->period)){
			//must be an array with date values
			if(count($this->request->period) <> 2){
				return back()->with('error', 'Invalid date format');
			}
			if(!strtotime($this->request->period[0]) || !strtotime($this->request->period[1])){
				return back()->with('error', 'Invalid date format inputted');
			}

			if(strtotime($this->request->period[0]) > strtotime($this->request->period[1])){
				return back()->with('error', 'Invalid date range. Please input the right start and end date');
			}

			$datediff = (strtotime($this->request->period[1]) - strtotime($this->request->period[0])) / 86400;
			$period_string = date('d M Y', strtotime($this->request->period[0])) . ' - ' . date('d M Y', strtotime($this->request->period[1]));
		}
		$analytic = new AnalyticRepository($this->request->all());
		return view('main::module.dashboard', compact('title', 'analytic', 'datediff', 'period_string'));
	}

	public function switchLang($lang=''){
		set_lang($lang);
		return [
			'type' => 'success'
		];
	}

}