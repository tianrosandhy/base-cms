<?php
namespace Module\Main\SettingExtension;

use Module\Main\Exception\SettingExtensionException;

class DataType
{
	public $type, $attribute, $source;

	public function __construct($type='text', $source=null, $attribute=[]){
		$this->source = $source;
		$this->attribute = $attribute;
		$this->setType($type);
	}

	protected function setType($type){
		$type = strtolower($type);
		$allowed_type = ['text', 'number', 'image', 'textarea', 'select'];
		if(!in_array($type, $allowed_type)){
			throw new SettingExtensionException('DataType input type is not in allowed lists. ('.implode(', ', $allowed_type).')');
		}
		$this->type = $type;
		if($this->type == 'select' && empty($this->source)){
			throw new SettingExtensionException('Select DataType must be used with $source parameter (array or closure)');
		}
	}

	public function get(){
		return [
			'type' => $this->type,
			'attribute' => (array)$this->attribute,
			'source' => $this->source,
		];
	}
}