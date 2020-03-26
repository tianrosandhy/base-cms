<?php
namespace Module\Navigation\SettingExtender;

use Module\Main\SettingExtension\BaseSetting;
use Module\Main\SettingExtension\Item;
use Module\Main\SettingExtension\DataType;
use Module\Main\Exceptions\SettingExtensionException;
use Module\Navigation\Models\Navigation;

class ModuleExtender extends BaseSetting
{
  public function handle(){
    //create new blank navigation group if empty
    $navs = Navigation::count();
    if($navs == 0){
      $nav = new Navigation;
      $nav->group_name = 'Default';
      $nav->max_level = 1;
      $nav->is_active = 1;
      $nav->save();

      \NavigationInstance::generateDefaultNavigation();
    }

  }
}