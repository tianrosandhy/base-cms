<?php
namespace Module\Main\Services;

use Illuminate\Contracts\Foundation\Application;
use DataSource;
use LanguageInstance;
use Input;

class DataStructure
{
	public 
		$field,
		$name,
		$orderable,
		$searchable,
		$data_source,

		$hide_form,
		$hide_table,
		$form_column,
		$input_type,
		$input_attribute,
		$input_array,
		$create_validation,
		$update_validation,
		$validation_translation,
		$slug_target,
		$value_source,
		$array_source,
		$value_data,
		$imagedir_path,
		$translate,
		$view_source,
		$tab_group;

	public function __construct(Application $app){
		//manage default value
		$this->orderable = true;
		$this->searchable = true;
		$this->hide_form = false;
		$this->hide_table = false;
		$this->form_column = 12;
		$this->data_source = 'text';
		$this->input_type = 'text';
		$this->input_array = false;
		$this->slug_target = false;
		$this->value_source = false;
		$this->array_source = null;
		$this->imagedir_path = false;
		$this->translate = true;
		$this->tab_group = 'General';
		$this->validation_translation = [];
	}

	public function createInput($data=null, $multi_language=false){
		$config = [
			'type' => $this->input_type,
			'name' => $this->field,
			'attr' => $this->input_attribute,
			'data' => $data,
			'value' => $this->generateStoredValue($data, $multi_language)
		];

		if($this->input_type == 'slug'){
			$config['slug_target'] = $this->slug_target;
		}
		if(in_array($this->input_type, ['select', 'select_multiple', 'radio', 'checkbox'])){
			$config['source'] = $this->data_source;
		}
		if($this->view_source){
			$config['view_source'] = $this->view_source;
		}

		if($multi_language){
			return Input::multiLanguage()->type($this->input_type, $this->field, $config);
		}
		else{
			return Input::type($this->input_type, $this->field, $config);
		}
	}

	protected function generateStoredValue($data, $multi_language=false){
		if($this->value_source){
			$grab_ = \DB::table($this->value_source[0])->find($this->value_source[1]);
			if($multi_language){
				$value[def_lang()] = $grab_->{$this->value_source[2]};
			}
			else{
				$value = $grab_->{$this->value_source[2]};
			}
		}
		elseif($this->array_source){
			$value = call_user_func($this->array_source, $data);
		}
		elseif($this->value_data){
			if($multi_language){
				foreach(LanguageInstance::available(true) as $lang){
					$value[$lang['code']] = call_user_func($this->value_data, $data, $lang['code']);
				}
			}
			else{
				$value = call_user_func($this->value_data, $data);
			}
		}
		else{
			if($multi_language){
				foreach(LanguageInstance::available(true) as $lang){
					$value[$lang['code']] = isset($data->{$this->field}) ? $data->outputTranslate($this->field, $lang['code'], true) : null;
				}
			}
			else{
				$value = isset($data->{$this->field}) ? $data->{$this->field} : null;
			}
		}

		return $value;
	}


	public function field($field=''){
		$this->field = $field;
		$this->inputAttribute();
		return $this;
	}

	//quick skeleton
	public function checker($name='id'){
		$this->field($name);
		$this->orderable(false);
		$this->searchable(false);
		$this->name('<input type="checkbox" name="checker_all" id="checker_all_datatable">');
		$this->hideForm();
		return $this;
	}

	public function switcher($field='is_active', $name='Is Active', $col=6, $value=[]){
		if(empty($value)){
			$value = [
				1 => 'Active',
				0 => 'Draft',
			];
		}

		$this->field($field);
		$this->formColumn($col);
		$this->name($name);
		$this->inputType('radio');
		$this->dataSource($value);
		return $this;
	}

	public function autoSlug($target='title', $field='slug', $name='Slug', $col=12){
		$this->field($field);
		$this->formColumn($col);
		$this->name($name);
		$this->inputType('slug');
		$this->slugTarget($target);
		$this->setTranslate(false);
		return $this;
	}


	public function slug($field='slug', $name='Slug', $target='title', $col=12){
		$this->field($field);
		$this->formColumn($col);
		$this->name($name);
		$this->inputType('slug');
		$this->slugTarget($target);

		if(!LanguageInstance::active()){
			$this->setTranslate(false);
		}

		$this->valueData(function($data, $lang=null){
			if(empty($lang)){
				$lang = def_lang();
			}
			if(isset($data->id)){
				return \SlugInstance::get($data, $data->id, $lang);
			}
		});
		return $this;
	}


	public function dateRange($field, $name, $callback=null){
		if(strpos($field, '[]') === false){
			$field = $field.'[]';
		}
		$this->field($field);
		$this->formColumn(12);
		$this->name($name);
		$this->inputType('daterange');
		$this->viewSource('main::inc.daterange-helper');
		$this->inputAttribute([
			'data-mask' => '0000-00-00'
		]);
		$this->setTranslate(false);
		$this->arraySource($callback);
		return $this;
	}

	public function name($name=''){
		$this->name = $name;
		return $this;
	}

	public function orderable($orderable=true){
		$this->orderable = (bool)$orderable;
		return $this;
	}

	//aliasnya orderable aja
	public function sortable($var=true){
		return $this->orderable($var);
	}

	public function searchable($searchable=true){
		$this->searchable = (bool)$searchable;
		return $this;
	}

	public function dataSource($data){
		if($data instanceof DataSource){
			$this->data_source = $data->output();
		}
		else{
			$this->data_source = $data;
		}
		return $this;
	}


	



	//manage hide / show in table or form
	public function hideForm(){
		$this->hide_form = true;
		return $this;
	}
	
	public function hideTable(){
		$this->hide_table = true;
		return $this;
	}
	




	public function formColumn($i=12){
		$i = $i < 0 ? 1 : $i;
		$i = $i > 12 ? 12 : $i;

		$this->form_column = $i;
		return $this;
	}

	public function inputType($type='', $param=false){
		$lists = [
			'text',
			'slug',
			'number',
			'email',
			'tel',
			'password',
			'tags',
			'checkbox',
			'radio',
			'textarea',
			'richtext',
			'select',
			'select_multiple',
			'image',
			'image_multiple',
			'file',
			'file_multiple',
			'date',
			'time',
			'datetime',
			'daterange',
			'view',
			'color',
			'gutenberg',
		];

		if(!in_array($type, $lists)){
			$type = 'text'; //paling default
		}
		if(in_array($type, ['select_multiple', 'daterange'])){
			$this->inputArray();
		}

		$this->input_type = $type;
		return $this;
	}

	public function slugTarget($target=''){
		$this->slug_target = $target;
		return $this;
	}

	public function inputArray($bool=true){
		$this->input_array = (bool)$bool;
		return $this;
	}

	public function inputAttribute($attr=[]){
		$fld = $this->field;
		$add = '';
		if(strpos($this->field, '[]') !== false){
			//kalo ada input field array, pindah ke paling ujung
			$fld = str_replace('[]', '', $this->field);
			$add = '[]';
		}

		$must = [
			'class' => ['form-control'],
			'name' => $fld.$add,
			'id' => 'input-'.$fld
		];

		$this->input_attribute = array_merge($must, $attr);
		return $this;
	}

	public function createValidation($rule='', $same_with_update=false){
		$this->create_validation = $rule;
		if($same_with_update){
			$this->updateValidation($rule);
		}
		return $this;
	}

	public function updateValidation($rule=''){
		if(strlen($rule) == 0)
			$rule = $this->create_validation; //ambil dari create validation aja sbg nilai default
		$this->update_validation = $rule;
		return $this;
	}

	public function validationTranslation($arr=[]){
		$this->validation_translation = array_merge($this->validation_translation, $arr);
		return $this;
	}

	public function valueSource($table='', $id='', $grab=''){
		$this->value_source = [$table, $id, $grab];
		return $this;
	}

	public function arraySource($fn){
		$this->array_source = $fn;
		return $this;
	}

    public function setImageDirPath($path=''){
        $this->imagedir_path = $path;
        return $this;
    }

    public function setTranslate($bool=false){
    	$this->translate = $bool;
    	return $this;
    }

    public function viewSource($target){
    	$this->view_source = $target;
    	return $this;
    }

    public function valueData($function){
    	$this->value_data = $function;
    	return $this;
    }

    public function tabGroup($tab_name){
    	$this->tab_group = $tab_name;
    	return $this;
    }



}