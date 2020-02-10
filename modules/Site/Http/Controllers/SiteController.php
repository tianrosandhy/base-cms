<?php
namespace Module\Site\Http\Controllers;

use App\Http\Controllers\Controller;
use SiteInstance;
use Illuminate\Http\Request;

class SiteController extends Controller
{
	public function __construct(Request $req){
		$this->request = $req;
	}

	public function index(){
		return view('site.index');
	}

	public function page($slug=''){
		$data = SiteInstance::page()->get($slug, 'slug');
		if(empty($data)){
			abort(404);
		}

		$title = $data->title;
		$mode = 'page';
		return view('site.single', compact(
			'data',
			'title',
			'mode'
		));
	}

	public function blog(){

	}

	public function post($slug=''){
		$data = SiteInstance::post()->get($slug, 'slug');
		if(empty($data)){
			abort(404);
		}

		$title = $data->title;
		$mode = 'post';
		return view('site.single', compact(
			'data',
			'title',
			'mode'
		));
	}


	public function sendContact(){
		if(SiteInstance::contact()->store()){
			return back()->with('success', 'Thank you, your message has been sent');
		}
		else{
			return back()->with('error', 'Invalid request');
		}
	}

}