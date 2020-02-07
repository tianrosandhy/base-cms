<?php
namespace Module\Site\Http\Controllers;

use App\Http\Controllers\Controller;
use SiteInstance;

class SiteController extends Controller
{
	public function __construct(){
		
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
}