<?php
namespace Module\Navigation\Models;

use Illuminate\Database\Eloquent\Model;
use Core\Main\Transformer\Resizeable;
use Core\Main\Transformer\Translator;

class NavigationItem extends Model
{
	use Resizeable;
  use Translator;

  protected $fillable = [
  ];

  public function children(){
  	return $this->hasMany('Module\Navigation\Models\NavigationItem', 'parent');
  }

  public function group(){
  	return $this->belongsTo('Module\Navigation\Models\Navigation', 'group_id');
  }
}
