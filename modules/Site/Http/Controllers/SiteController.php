<?php
namespace Module\Site\Http\Controllers;

use App\Http\Controllers\Controller;
use SiteInstance;
use SlugInstance;
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
		$request = $this->request->all();
		if(!empty($category)){
			$cat_instance = SlugInstance::instance($category, ['post_category']);
			if(empty($cat_instance)){
				abort(404);
			}
			$request['category'] = $cat_instance->id;
		}
		$data = $this->apiPost($request);
		return view('site.filter', compact(
			'title',
			'data',
			'request'
		));
	}

	public function apiPost($request=null){
		if(empty($request)){
			$request = $this->request->all();
		}
		$data = SiteInstance::post()->response($request);
		return view('pages.post-response', compact('data', 'request'))->render();
	}

	public function slugDetail($slug){
		$data = SlugInstance::instance($slug, ['post', 'page']);
		if(empty($data)){
			abort(404);
		}

		$mode = 'post';
		if($data->table == 'pages'){
			$mode = 'page';
		}
		$title = $data->outputTranslate('title');
		return view('site.single', compact(
			'title',
			'data',
			'mode'
		));
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