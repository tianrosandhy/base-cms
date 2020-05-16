<?php
namespace Core\Main\SettingExtender;

use Core\Main\SettingExtension\BaseSetting;
use Core\Main\SettingExtension\Item;
use Core\Main\SettingExtension\DataType;
use Core\Main\Exceptions\SettingExtensionException;

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

		$item[] = new Item('log.active', 'Use Email Log Reporting', new DataType('select', [
			0 => 'No',
			1 => 'Yes'
		]), 0);
		$item[] = new Item('log.email_receiver', 'Log Email Receiver', new DataType('text'));

		$this->registers($item);
	}
}