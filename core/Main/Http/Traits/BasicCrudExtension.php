<?php
namespace Core\Main\Http\Traits;

use SlugInstance;
use Illuminate\Database\Eloquent\Builder;


trait BasicCrudExtension
{
	//main default table saved logic
	public function saveProcess($instance=null, $is_active_field = 'is_active'){
		if(empty($instance)){
			//create new blank instance model
			$instance = model($this->repo());
		}

		$inputData = $this->getUsedField();
		//masing-masing input data difilter, apakah nama fieldnya exists atau tidak
		$listingColumn = $this->modelTableListing();
		$changed_field = 0;
		foreach($inputData as $fld => $value){
			if(in_array($fld, $listingColumn)){
				$instance->{$fld} = $value;
				$changed_field += 1;
			}
		}

		if($changed_field > 0){
			$instance->save();
		}
		else{
			return false;
		}
		return $instance;
	}

	protected function storeSlug($instance){
		//check for input type slug
		$slug_structures = collect($this->skeleton()->structure)->where('input_type', 'slug');
		if($slug_structures->count() > 0){
			// ada field slug, lakukan penyimpanan ke slug master
			foreach($slug_structures as $slug_instance){
				$input_name = $slug_instance->field;
				$slug_stored = $this->request->$input_name;
				if(!empty($slug_stored)){
					//slug store logic : 
					$table_name = $this->repo->getTableName();
					$pk = $instance->id;
					if(isset($slug_stored[def_lang()])){
						foreach($slug_stored as $lang => $storedata){
							if(!empty($storedata)){
								$stored_slug = SlugInstance::create($table_name, $pk, $storedata, $lang);
							}
						}
					}
					else{
						$stored_slug = SlugInstance::create($table_name, $pk, $slug_stored);
					}
				}
			}
		}		
	}




	protected function modelTableListing(){
		$model = $this->repo->model;
		if($model instanceof Builder){
			$model = $model->getModel();
		}

        return $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());
	}

	protected function setMode($mode='view'){
		$this->mode = $mode;
	}

	protected function getUsedPlugin($skeleton){
		$collect = collect($skeleton);
		$used_plugin = [
			'gutenberg' => false,
			'dropzone' => false,
			'cropper' => false,
			'media' => false,
			'richtext' => false,
		];

		if($collect->where('input_type', 'gutenberg')->count() > 0){
			$used_plugin['gutenberg'] = true;
		}
		if($collect->whereIn('input_type', ['file', 'file_multiple'])->count() > 0){
			$used_plugin['dropzone'] = true;
		}
		if($collect->whereIn('input_type', ['image', 'image_multiple', 'richtext'])->count() > 0){
			$used_plugin['media'] = true;
		}
		if($collect->where('input_type', 'cropper')->count() > 0){
			$used_plugin['cropper'] = true;
		}
		if($collect->where('input_type', 'richtext')->count() > 0){
			$used_plugin['richtext'] = true;
		}
		return $used_plugin;
	}


	protected function usedLang($param=''){
		//grab from module translation

		$module_query = $this->getTranslationModuleAlias().'::module.'.$this->getTranslationNameAlias().'.'.$param;
		$module_lang = trans($module_query);
		if($module_lang <> $module_query){
			return $module_lang;
		}
		return null;

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

	protected function getUsedField(){
		$active_fields = $this->skeleton()->getActiveFormFields();
		$inputData = [];
		foreach($active_fields as $fields){
			if(isset($this->request->{$fields})){
				if($this->multi_language){
					$stored = get_lang($this->request->{$fields});
					//if input with index [language] not found, then try the input without index [language]
					if(empty($stored) && !is_array($this->request->{$fields})){
						$stored = $this->request->{$fields};
					}
				}
				else{
					$stored = $this->request->{$fields};
				}

				//save array data as json object
				if(is_array($stored)){
					$stored = array_filter($stored, function($item){
						return !empty($item);
					});
					$stored = json_encode($stored);
				}
				$inputData[$fields] = $stored;
			}
		}

		return $inputData;
	}

}