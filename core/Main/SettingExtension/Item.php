<?php
namespace Core\Main\SettingExtension;

use Core\Main\Exceptions\SettingExtensionException;
use Core\Main\SettingExtension\DataType;

class Item
{

	private 
		$group,
		$param,
		$label;

	public function __construct($name='group.name', $label, DataType $data_type, $default_value=null){
		$pch = explode('.', $name);
		if(count($pch) <> 2){
			throw new SettingExtensionException('Setting extension name must be in "group_string.name_string" format.');
		}

		$this->group = strtolower($pch[0]);
		$this->param = strtolower($pch[1]);
		$this->label = $label;
		$this->data_type = $data_type;
		$this->default_value = $default_value;
	}

	public function render(){
		$data_type = $this->data_type->get();
		return [
			'param' => $this->param,
			'group' => $this->group,
			'name' => $this->label,
			'type' => $data_type['type'],
			'source' => $data_type['source'],
			'attribute' => $data_type['attribute'],
			'default_value' => $this->default_value,
		];
	}
}