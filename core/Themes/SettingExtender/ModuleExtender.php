<?php
namespace Core\Themes\SettingExtender;

use Core\Main\SettingExtension\BaseSetting;
use Core\Main\SettingExtension\Item;
use Core\Main\SettingExtension\DataType;
use Core\Main\Exceptions\SettingExtensionException;

class ModuleExtender extends BaseSetting
{
	public function handle(){
		$item[] = new Item('site.frontend_theme', 'Used Frontend Theme', new DataType('select', function(){
			$base = config('cms.themes.paths');
			if(isset($base[0])){
				if(file_exists($base[0])){
					$scan = scandir($base[0]);
					$scan = array_diff($scan, ['.', '..']);

					$out = [];
					if(!empty($scan)){
						foreach($scan as $themename){
							$out[$themename] = $themename;
						}
						return $out;
					}
				}
			}
			return [];
		}), 'theme1');

		$this->registers($item);
	}
}