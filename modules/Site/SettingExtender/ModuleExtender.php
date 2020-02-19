<?php
namespace Module\Site\SettingExtender;

use Module\Main\SettingExtension\BaseSetting;
use Module\Main\SettingExtension\Item;
use Module\Main\SettingExtension\DataType;
use Module\Main\Exceptions\SettingExtensionException;

class ModuleExtender extends BaseSetting
{
	public function handle(){
		$item[] = new Item('seo.keyword', 'Default SEO Keyword', new DataType('text'));
		$item[] = new Item('seo.description', 'Default SEO Description', new DataType('textarea'));
		$item[] = new Item('seo.image', 'Default SEO Image', new DataType('image'));


		$item[] = new Item('social.facebook', 'Facebook URL', new DataType('text'), 'https://facebook.com');
		$item[] = new Item('social.twitter', 'Twitter URL', new DataType('text'), 'https://twitter.com');
		$item[] = new Item('social.instagram', 'Instagram URL', new DataType('text'), 'https://instagram.com');
		$item[] = new Item('social.whatsapp', 'Whatsapp URL', new DataType('text'), 'https://api.whatsapp.com/send?phone=');
		$this->registers($item);
	}
}