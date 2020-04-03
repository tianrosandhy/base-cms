<?php
namespace Core\Language\Http\Controllers;

use Core\Main\Http\Repository\CrudRepository;
use Core\Main\Http\Controllers\AdminBaseController;
use Core\Language\Http\Skeleton\LanguageSkeleton;
use Core\Main\Transformer\Exportable;
use LanguageInstance;
use Illuminate\Validation\ValidationException;

class LanguageController extends AdminBaseController
{
	use Exportable;
	//hint => used as route name, url name, view alias
	public $hint = 'language';

	public function repo(){
		//repo => model alias used (default : same as hint)
		return $this->hint;
	}

	public function skeleton(){
		return new LanguageSkeleton;
	}

	public function afterCrud($instance){
		$instance->title = isset(config('module-setting.language.lists')[$instance->code]) ? config('module-setting.language.lists')[$instance->code] : '-';
		$instance->save();

		if($instance->is_default_language){
			//hapus default language di data lain
			model('language')->where('is_default_language', 1)->where('id', '<>', $instance->id)->update([
				'is_default_language' => 0
			]);
		}
		else{
			$check_no_default_language = model('language')->where('is_default_language', 1)->count();
			if($check_no_default_language == 0){
				//kembalikan data ini sbg default language
				$instance->is_default_language = 1;
				$instance->save();
			}
		}
	}

	public function image_field(){
		return ['image'];
	}

	public function switch(){
		if(strlen($this->model) == 0){
			abort(404);
		}

		$table = new CrudRepository($this->model);
		$this->request->validate([
			'field' => 'required',
			'id' => 'required|numeric',
			'value' => 'required|numeric|min:0|max:1'
		]);



		$table->update($this->request->id, [
			$this->request->field => $this->request->value
		]);

		return [
			'type' => 'success',
			'message' => 'Data has been updated'
		];
	}
}