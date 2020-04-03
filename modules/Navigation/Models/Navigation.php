<?php
namespace Module\Navigation\Models;

use Illuminate\Database\Eloquent\Model;
use Core\Main\Transformer\Resizeable;
use Core\Main\Transformer\Translator;

class Navigation extends Model
{
	use Resizeable;
  use Translator;

  protected $fillable = [
  ];

  public function lists(){
  	return $this->hasMany('Module\Navigation\Models\NavigationItem', 'group_id');
  }

}
