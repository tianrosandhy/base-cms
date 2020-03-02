<?php
namespace Module\Language\Http\Controllers;

use Module\Main\Http\Repository\CrudRepository;
use Module\Main\Http\Controllers\AdminBaseController;
use Module\Language\Http\Skeleton\LanguageSkeleton;
use Module\Main\Transformer\Exportable;
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

	public function afterValidation($mode='create', $instance=null){
		//default language gaboleh didisable
		if(strlen($this->request->is_default_language) > 0 && !$this->request->is_default_language && $mode <> 'create'){
			throw ValidationException::withMessages([
				'error' => 'Please dont turn off the default language'
			]);
		}
	}

	public function afterCrud($instance){
		$instance->title = isset(config('module-setting.language.lists')[$instance->code]) ? config('module-setting.language.lists')[$instance->code] : '-';
		$instance->save();

		if($instance->is_default_language){
			//hapus default language di data lain
			app(config('model.language'))->where('is_default_language', 1)->where('id', '<>', $instance->id)->update([
				'is_default_language' => 0
			]);
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