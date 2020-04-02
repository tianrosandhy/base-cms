<?php
namespace Module\Main\SettingExtension;

use Module\Main\SettingExtension\Item;
use Module\Main\Models\SettingStructure;

class BaseSetting
{
	public 
		$structured = [],
		$stored_setting;

	public function __construct(){
		$this->stored_setting = app('setting');
		$this->handle();
	}

	public function handle(){
		//override this
	}

	public function register(Item $item){
		$this->structured[] = $item;
	}

	public function registers($item_array = []){
		foreach($item_array as $items){
			$this->saveDefaultDataIfNotExists($items);
		}
		$this->structured = array_merge($this->structured, $item_array);
	}

	protected function saveDefaultDataIfNotExists($item_instance){
		$item = $item_instance->render();
		$get = $this->stored_setting->where('group', $item['group'])->where('param', $item['param'])->first();
		if(empty($get)){
			//create new setting instance with default value
			$item_name = ucwords(implode(' ', explode('_', $item['param'])));

			$set = new SettingStructure;
			$set->param = $item['param'];
			$set->group = $item['group'];
			$set->name = $item['name'];
			$set->default_value = $item['default_value'];
			$set->type = $item['type'];
			$set->save();
		}
	}

	public function get(){
		$out = [];
		foreach($this->structured as $lists){
			$out[] = $lists->render();
		}

		return $out;
	}

}