<?php
namespace Module\Main\SettingExtender;

use Module\Main\SettingExtension\BaseSetting;
use Module\Main\SettingExtension\Item;
use Module\Main\SettingExtension\DataType;
use Module\Main\Exceptions\SettingExtensionException;

class ModuleExtender extends BaseSetting
{
	public function handle(){
		//register basic CMS default setting
		$item[] = new Item('site.title', 'Site Title', new DataType('text'), 'Your Site Name');
		$item[] = new Item('site.description', 'Site Description', new DataType('textarea'), 'Your basic site description here');
		$item[] = new Item('site.favicon', 'Site Favicon', new DataType('image'));
		$item[] = new Item('site.ga_tracking', 'Site GA Tracking ID', new DataType('text'));
		$item[] = new Item('site.mail_receiver', 'Site Email Receiver', new DataType('text'), 'tianrosandhy@gmail.com');

		$item[] = new Item('admin.logo', 'Admin Image Logo', new DataType('image'));
		$item[] = new Item('admin.favicon', 'Admin Favicon Logo', new DataType('image'));

		$this->registers($item);
	}
}