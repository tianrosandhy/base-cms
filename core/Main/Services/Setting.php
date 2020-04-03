<?php
namespace Core\Main\Services;

use Core\Main\Models\SettingStructure;

class Setting
{
	protected 
		$structure,
		$module_setting;

	public function __construct(){
		$this->structure = app('setting')->groupBy('group')->toArray();
	}

	public function all(){
		$this->module_setting = $this->loadModuleSetting();
		if(!empty($this->module_setting)){
			$this->mergeSettingFromModule();
		}
		return $this->structure;
	}

	protected function loadModuleSetting(){
		$modules =  config('modules.load'); 
		//register current main module service provider too ._.
		array_unshift($modules, 'Core\Main\MainServiceProvider');

		$modules = array_map(function($item){
			$pch = explode('\\', $item);
			unset($pch[count($pch)-1]);
			$base_class = implode('\\', $pch);
			
			$class_target = $base_class.'\\SettingExtender\\ModuleExtender';
			if(class_exists($class_target)){
				return app($class_target);
			}
		}, $modules);
		return array_filter($modules, function($item){
			return $item != null;
		});
	}

	protected function mergeSettingFromModule(){
		$override_data = [];
		foreach($this->module_setting as $module_setting){
			$override_data = array_merge($override_data, $module_setting->get());
		}

		//get saved value from database if exists
		$final = [];
		if(!empty($override_data)){
			$override_data = collect($override_data);
			$stored = app('setting');
			if($stored->count() > 0){
				foreach($override_data as $row){
					$grab = $stored->where('group', $row['group'])->where('param', $row['param'])->first();
					$temp = $row;
					if(!empty($grab)){
						$temp['default_value'] = $grab['default_value'];
					}
					$final[] = $temp;
				}
			}
			else{
				$final = $override_data;
			}
		}

		//merge $final to $this->structure
		foreach($final as $row){
			if(isset($this->structure[$row['group']])){
				$check_exists = collect($this->structure[$row['group']])->where('param', $row['param'])->where('group', $row['group']);
				if($check_exists->count() > 0){
					foreach($check_exists as $idini => $valuesini){
						$base_data = $valuesini;
						$base_data['type'] = $row['type'];
						$base_data['source'] = isset($row['source']) ? $row['source'] : null;
						$base_data['name'] = $row['name'];
						$base_data['attribute'] = $row['attribute'];
						$this->structure[$row['group']][$idini] = $base_data;
					}
				}
				else{
					$this->structure[$row['group']][] = $row;
				}
			}
			else{
				$this->structure[$row['group']][] = $row;
			}
		}
		return $this->structure;
	}

}