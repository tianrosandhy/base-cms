<?php
namespace Module\Themes\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Main\Http\Middleware\AdminAuth;
use Auth;
use Illuminate\Validation\ValidationException;

use Illuminate\Contracts\Foundation\Application;
use Module\Themes\Manager\ThemeManager;

class ThemesController extends Controller
{
	
	public 
		$request,
		$hint = 'themes',
		$language = [],
		$multi_language = false,
		$themeManager,
		$as_ajax = false;

	public function __construct(
		Application $app,
		Request $request
	) {
		$this->request = $request;
		$this->middleware('auth');
		$this->middleware(AdminAuth::class);
		$this->app = $app;
		$this->themeManager = new ThemeManager($this->app, config('appearances.themes.paths')[0]);
	}
	
	public function index(){
		// dd($this->themeManager->allPublicThemes());
		// echo "index";die();

		$datatable = $this->themeManager->allPublicThemes();
		$title = self::usedLang('index.title');
		$hint = $this->hint();		
		$append_index = $this->appendIndex();
		$prepend_index = $this->prependIndex();
		$ctrl_button = $this->appendIndexControlButton();
		$as_ajax = $this->asAjax();
		return view(config('module-setting.'.$this->hint().'.view.index'), compact(
			'title',
			'hint',
			'datatable',
			'append_index',
			'prepend_index',
			'ctrl_button',
			'as_ajax'
		));
	}

	protected function usedLang($param='') {
		if(!isset($this->language[$param])){
			return false;
		}

		$langdata = $this->language[$param];

		if(is_array($langdata)){
			if(isset($langdata[current_lang()])){
				return $langdata[current_lang()];
			}
			if(isset($langdata[def_lang()])){
				return $langdata[def_lang()];
			}

			return false;
		}
		return $langdata;
	}

	public function appendIndex(){
		if($this->hint){
			if(view()->exists($this->hint.'::partials.index.after-table')){
				return view($this->hint.'::partials.index.after-table');
			}
		}
		//fallback : blank
		return '';
	}

	public function prependIndex(){
		if($this->hint){
			if(view()->exists($this->hint.'::partials.index.before-table')){
				return view($this->hint.'::partials.index.before-table');
			}
		}
		//fallback : blank
		return '';
	}

	public function appendIndexControlButton(){
		if($this->hint){
			if(view()->exists($this->hint.'::partials.index.control-button')){
				return view($this->hint.'::partials.index.control-button');
			}
		}
		//fallback : blank
		return '';
	}

	public function asAjax(){
		return $this->as_ajax;
	}

	public function hint($var=''){
		if(strlen($var) > 0)
			return $var;
		return $this->hint;
	}
}