<?php
namespace Module\Themes\Http\Controllers;

use Module\Main\Http\Repository\CrudRepository;
use Module\Main\Http\Controllers\AdminBaseController;
use Module\Themes\Http\Skeleton\ThemesSkeleton;
use Module\Themes\Manager\ThemeManager;
use Validator;

class ThemesController extends AdminBaseController
{
	//hint => used as route name, url name, view alias
	public $hint = 'themes';

	public function repo(){
		//repo => model alias used (default : same as hint)
		return $this->hint;
	}

	public function skeleton(){
		return new ThemesSkeleton;
	}

	public function table(){
		$skeleton = $this->skeleton();
		$skeleton->setDataTable();
		$skeleton->validateRequest();

		$theme_manager = new ThemeManager(app(), config('appearances.themes.paths')[0]);
		$theme_lists = $theme_manager->allPublicThemes();

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