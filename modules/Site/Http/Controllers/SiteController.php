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
		$seo = $this->generateRawSeoTags();
		$homepage = true;
		return view('site.index', compact(
			'seo',
			'homepage'
		));
	}

	public function blog($category=null){
		$title = 'Blog';
		$data = $this->apiPost();
		return view('site.filter', compact(
			'title',
			'data'
		));
	}

	public function apiPost(){
		$request = $this->request->all();
		$data = SiteInstance::post()->response($request);
		return view('pages.post-response', compact('data', 'request'))->render();
	}

	public function slugDetail($slug){

	}

	public function contact(){

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