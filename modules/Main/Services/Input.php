<?php
namespace Module\Main\Services;

use Module\Main\Exceptions\InputException;
use Str;

class Input
{
	public 
		$base_view = 'main::input.',
		$multi_language = false;

	public function type($type, $name, $config=[]){
		$camelize = Str::camel($type);
		if(method_exists($this, $camelize)){
			return $this->{$camelize}($name, $config);
		}
		else{
			throw new InputException('Input type ' . $type .' is still not defined');
		}
	}

	public function multiLanguage(){
		$this->multi_language = true;
		return $this;
	}

	protected function mandatoryConfig($config=[], $mandatory=[], $input_type='text'){
		if(!empty($mandatory)){
			foreach($mandatory as $config_key){
				if(!isset($config[$config_key])){
					throw new InputException('Config "'.$config_key.'" is mandatory for input type "'.$input_type.'"');
				}
			}
		}
	}

	public function loadView($view_name, $input_name, $config=[], $fallback=true){
		if(view()->exists($this->base_view.$view_name)){
			if(!isset($config['name'])){
				$config['name'] = $input_name;
			}
			if($this->multi_language){
				$config['multi_language'] = true;
			}
			return view($this->base_view.$view_name, $config)->render();
		}

		$msg = 'Input '.$view_name.' view is still not defined';
		if($fallback){
			return '<div class="alert alert-danger">'.$msg.'</div>';
		}
		throw new InputException($msg);
	}


	public function text($name, $config=[]){
		return $this->loadView('text', $name, $config);
	}
	public function number($name, $config=[]){
		$config['type'] = 'number';
		return $this->loadView('text', $name, $config);
	}
	public function email($name, $config=[]){
		$config['type'] = 'email';
		return $this->loadView('text', $name, $config);
	}
	public function password($name, $config=[]){
		$config['type'] = 'password';
		return $this->loadView('text', $name, $config);
	}
	public function color($name, $config=[]){
		$config['type'] = 'color';
		return $this->loadView('text', $name, $config);
	}
	public function richtext($name, $config=[]){
		return $this->loadView('richtext', $name, $config);
	}
	public function textarea($name, $config=[]){
		return $this->loadView('textarea', $name, $config);
	}
	public function gutenberg($name, $config=[]){
		return $this->loadView('gutenberg', $name, $config);
	}
	public function tel($name, $config=[]){
		$config['type'] = 'tel';
		return $this->loadView('text', $name, $config);
	}
	public function tags($name, $config=[]){
		$config['type'] = 'tags';
		return $this->loadView('text', $name, $config);
	}
	public function image($name, $config=[]){
		return $this->loadView('image', $name, $config);
	}
	public function imageMultiple($name, $config=[]){
		return $this->loadView('image_multiple', $name, $config);
	}
	public function slug($name, $config=[]){
		$this->mandatoryConfig($config, ['slug_target'], 'slug');
		return $this->loadView('slug', $name, $config);
	}
	public function date($name, $config=[]){
		$config['type'] = $date;
		return $this->loadView('datetime', $name, $config);
	}
	public function time($name, $config=[]){
		$config['type'] = 'time';
		return $this->loadView('datetime', $name, $config);
	}
	public function dateTime($name, $config=[]){
		$config['type'] = 'datetime';
		return $this->loadView('datetime', $name, $config);
	}
	public function dateRange($name, $config=[]){
		return $this->loadView('daterange', $name, $config);
	}
	public function file($name, $config=[]){
		return $this->loadView('file', $name, $config);
	}
	public function fileMultiple($name, $config=[]){
		$config['type'] = 'file_multiple';
		return $this->loadView('file', $name, $config);
	}
	public function select($name, $config=[]){
		$this->mandatoryConfig($config, ['source'], 'select');
		return $this->loadView('select', $name, $config);
	}
	public function selectMultiple($name, $config=[]){
		$this->mandatoryConfig($config, ['source'], 'selectMultiple');
		$config['type'] = 'select_multiple';
		return $this->loadView('select', $name, $config);
	}
	public function radio($name, $config=[]){
		$this->mandatoryConfig($config, ['source'], 'radio');
		return $this->loadView('radio', $name, $config);
	}
	public function checkbox($name, $config=[]){
		$this->mandatoryConfig($config, ['source'], 'checkbox');
		$config['type'] = 'checkbox';
		return $this->loadView('radio', $name, $config);
	}
	public function view($name, $config=[]){
		$this->mandatoryConfig($config, ['view_source', 'data'], 'view');
		return $this->loadView('view', $name, $config);
	}


}