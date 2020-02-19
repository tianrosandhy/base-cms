<?php
namespace Module\Themes\SettingExtender;

use Module\Main\SettingExtension\BaseSetting;
use Module\Main\SettingExtension\Item;
use Module\Main\SettingExtension\DataType;
use Module\Main\Exceptions\SettingExtensionException;

class ModuleExtender extends BaseSetting
{
	public function handle(){
		$item[] = new Item('site.frontend_theme', 'Used Frontend Theme', new DataType('select', function(){
			$base = config('appearances.themes.paths');
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
		}));

		$this->registers($item);
	}
}