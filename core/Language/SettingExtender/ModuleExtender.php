<?php
namespace Core\Language\SettingExtender;

use Core\Main\SettingExtension\BaseSetting;
use Core\Main\SettingExtension\Item;
use Core\Main\SettingExtension\DataType;
use Core\Main\Exceptions\SettingExtensionException;

class ModuleExtender extends BaseSetting
{
	public function handle(){
		//format example
		# $item[] = new Item('group.name', 'Label Name', new DataType('text'), 'default_value');
		# $item[] = new Item('group.another_name', 'Another Label Name', new DataType('select', function(){
		#    return [];
		# }, '1'), 'default_value');

		# $this->registers($item);
	}
}