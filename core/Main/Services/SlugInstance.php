<?php
namespace Core\Main\Services;

use Core\Main\Models\SlugMaster;
use Illuminate\Database\Eloquent\Model;

class SlugInstance
{
	public function create($table, $id, $string, $language=null){
		if(empty($language)){
			$language = def_lang();
		}
		$slug = slugify($string);
		$instance = SlugMaster::where('table', $table)->where('primary_key', $id)->where('language', $language)->first();
		$slug = $this->slugForSaved($slug, $instance);


		if(empty($instance)){
			$instance = new SlugMaster;
			$instance->table = $table;
			$instance->primary_key = $id;
			$instance->language = $language;
		}
		$instance->slug = $slug;
		$instance->save();

		return $instance;
	}

	protected function slugForSaved($slug, $except_instance=null){
		$language = empty($language) ? def_lang() : $language;
		if($except_instance){
			$check = SlugMaster::where('slug', $slug)->where('id', '<>', $except_instance->id)->first();
		}
		else{
			$check = SlugMaster::where('slug', $slug)->first();
		}
		if(!empty($check)){
			//create slug iteration
			$grabs = SlugMaster::where('slug', 'like', $slug.'%')->orderBy('slug')->get(['slug']);
			$iteration = 1;
			foreach($grabs as $item){
				if($item->slug == $slug){
					continue;
				}
				$split = explode('-', $item->slug);
				$last_part = $split[count($split)-1];
				if(is_numeric($last_part)){
					$iteration = $last_part + 1;
				}
			}

			return $slug.'-'.$iteration;
		}
		return $slug;
	}

	public function get($model, $id, $lang=null){
		$table_name = $model;
		if($model instanceof Model){
			$table_name = $model->getTable();
		}

		$grab_lang = $lang;
		if(empty($lang)){
			$grab_lang = def_lang();
		}

		$grab = SlugMaster::where('table', $table_name)->where('primary_key', $id)->get();
		$real_grab = $grab->where('language', $grab_lang)->first();
		if(!empty($real_grab)){
			return $real_grab->slug;
		}
		else{
			//still return even if the language data is not exists
			return isset($grab->first()->slug) ? $grab->first()->slug : null;
		}

	}

	public function instance($slug='', $model=null){
		$must_table = null;
		if(empty($model)){
			$model = [];
		}
		else{
			if(is_string($model)){
				$model = [$model];
			}
		}

		$must_table = [];
		foreach($model as $mdl){
			$tbname = model($mdl)->getTable();
			$must_table[$tbname] = $mdl;
		}


		$grab = SlugMaster::where('slug', $slug)->first();
		if(!empty($grab)){
			if(!empty($must_table) && !array_key_exists($grab->table, $must_table)){
				return false;
			}

			if(empty($model)){
				$object = \DB::table($grab->table)->find($grab->primary_key);
				$object->table = $grab->table;
				return $object;
			}
			else{
				$used_model = isset($must_table[$grab->table]) ? $must_table[$grab->table] : null;
				if(!empty($used_model)){
					$instance = model($used_model)->find($grab->primary_key);
					if(!empty($instance)){
						$instance->table = $grab->table;
						return $instance;
					}
				}
			}
		}
		return false;
	}

}