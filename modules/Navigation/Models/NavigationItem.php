<?php
namespace Module\Navigation\Models;

use Illuminate\Database\Eloquent\Model;
use Module\Main\Transformer\Resizeable;

class NavigationItem extends Model
{
	use Resizeable;

    protected $fillable = [
    ];

    public function children(){
    	return $this->hasMany('Module\Navigation\Models\NavigationItem', 'parent');
    }
}
