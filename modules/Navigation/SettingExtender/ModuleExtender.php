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
    }

    //set navigation default value.
    $default_value = Navigation::first()->id;

    //register basic CMS default setting
    $item[] = new Item('site.navigation', 'Default Navigation Group', new DataType('select', function(){
      $out = [];
      foreach(Navigation::get(['id', 'group_name']) as $row){
        $out[$row->id] = $row->group_name;
      }
      return $out;
    }), $default_value);
    $this->registers($item);
  }
}