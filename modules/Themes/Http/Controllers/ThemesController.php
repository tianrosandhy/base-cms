<?php
namespace Module\Themes\Http\Controllers;

use Module\Main\Http\Repository\CrudRepository;
use Module\Main\Http\Controllers\AdminBaseController;
use Module\Themes\Http\Skeleton\ThemesSkeleton;
use Illuminate\Http\Request;
use ThemesInstance;
use Validator;

class ThemesController extends AdminBaseController
{
	//hint => used as route name, url name, view alias
	public $hint = 'themes';

	public function __construct(Request $request){
		parent::__construct($request);
	}

	public function repo(){
		//repo => model alias used (default : same as hint)
		return $this->hint;
	}


	public function themeOption(){
		$active_theme = ThemesInstance::getActiveTheme();
		if(!isset($active_theme->themeoption)){
			return back()->with('error', 'This theme has no theme options');
		}

		$saved = ThemesInstance::getStored();
		$theme_option = $active_theme->themeoption;
		return view('themes::theme-option', [
			'title' => 'Theme Option',
			'active_theme' => $active_theme,
			'theme_option' => $theme_option,
			'saved' => $saved
		]);
	}

	public function storeThemeOption(){
		$active_theme = ThemesInstance::getActiveTheme();
		if(!isset($active_theme->themeoption)){
			return back()->with('error', 'This theme has no theme options');
		}

		$theme_name = $active_theme->getName();
		$stored_theme = app(config('model.themes'))->where('theme', $theme_name)->get();

		if(!is_array($this->request->theme)){
			return back()->with('error', 'Invalid request. Please try again');
		}

		foreach($this->request->theme as $key => $values){
			foreach($values as $index => $data){
				$keyname = $key.'.'.$index;
				if($stored_theme->where('key', $keyname)->count() > 0){
					$instance = $stored_theme->where('key', $keyname)->first();
					if(empty($data) && strlen($data) == 0){
						//hapus instance ini sekalian
						$instance->delete();
					}
				}
				else{
					if(empty($data) && strlen($data) == 0){
						//skip gausa diinsert
						continue;
					}
					$instance = app(config('model.themes'));
					$instance->theme = $theme_name;
					$instance->key = $keyname;
				}

				$instance->value = $data;
				$instance->save();
			}
		}


		return back()->with('success', 'Theme option has been saved');

	}



	public function skeleton(){
		return new ThemesSkeleton;
	}

	public function table(){
		$skeleton = $this->skeleton();
		$skeleton->setDataTable();
		$skeleton->validateRequest();

		$theme_lists = ThemesInstance::allPublicThemes();

		$skeleton->query_count = count($theme_lists);
		$skeleton->raw_data = $theme_lists;
		$skeleton->tableFormat();
		return $skeleton->getResponse();
	}	


	public function setActive(){
		$validate = Validator::make($this->request->all(), [
			'theme' => 'required|string',
		]);
		if (!$validate->fails()) {
			$active_theme = app(config('model.setting_structure'))->where('param', 'frontend_theme')->first();
			$active_theme->default_value = $this->request->input('theme');
			$active_theme->save();
		}
		else {
			$errors = $validate->messages()->all();
			$json = $errors;

			return response()->json($json, 400);
		}
	}

}