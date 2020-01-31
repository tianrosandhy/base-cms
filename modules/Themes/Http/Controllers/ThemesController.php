<?php
namespace Module\Themes\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Main\Http\Middleware\AdminAuth;
use Auth;
use Illuminate\Validation\ValidationException;
use Validator;
use Module\Main\Http\Repository\CrudRepository;
use Illuminate\Contracts\Foundation\Application;
use Module\Themes\Manager\ThemeManager;
use Module\Main\Models\SettingStructure;

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
		$this->middleware(AdminAuth::class);
		$this->app = $app;
		$this->themeManager = new ThemeManager($this->app, config('appearances.themes.paths')[0]);
		$this->repo = new CrudRepository('theme_options');
		$this->language = config('module-setting.'.$this->hint.'.lang_data');
	}

	
	public function index() {
		$datatable = $this->themeManager->allPublicThemes();
		$row_themes = count($datatable);
		if($this->request->ajax()) {
			$data_list = [];
			foreach ($datatable as $key => $theme) {
				$data_value = ($theme->active) ? 'enable' : 'disable';
				$data_active = ($theme->active) ? 'checked' : '';
				$data_list[] = array(
					'name' => $theme->tname,
					'path' => $theme->tdirectory,
					'status' => '<div class="btn-group">
						<input type="checkbox" data-init-plugin="switchery" data-size="small" name="themes-active" value="'.$data_value.'" data-theme="'.$theme->tname.'" '.$data_active.'>
						</div>'
				);
			}

			$response = array(
				'draw' => $this->request->draw,
				'data' => $data_list,
				'recordsTotal' => $row_themes,
      			'recordsFiltered' => $row_themes,
			);
	  
	  
			return response()->json($response);
		}

		$title = self::usedLang('index.title');
		$hint = $this->hint();
		$ctrl_button = $this->appendIndexControlButton();
		
		return view(config('module-setting.'.$this->hint().'.view.index'), compact(
			'title',
			'hint',
			'ctrl_button'
		));
	}

	public function setActive(){
		$validate = Validator::make($this->request->all(), [
			'theme' => 'required|string',
		]);
		if (!$validate->fails()) {
			$active_theme = SettingStructure::where('param', 'frontend_theme')->first();
			$active_theme->default_value = $this->request->input('theme');
			$active_theme->save();
		} else {
			$errors = $validate->messages()->all();
			$json = $errors;

			return response()->json($json, 400);
		}
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
		return '';
	}

	public function prependIndex(){
		return '';
	}

	public function appendIndexControlButton(){
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