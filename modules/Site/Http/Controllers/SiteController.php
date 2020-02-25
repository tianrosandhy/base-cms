<?php
namespace Module\Site\Http\Controllers;

use App\Http\Controllers\Controller;
use SiteInstance;
use Illuminate\Http\Request;
use Module\Main\Transformer\Seo;

class SiteController extends Controller
{
	use Seo;

	public function __construct(Request $req){
		$this->request = $req;
	}

	public function index(){
		$seo = $this->generateSeoTags();
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