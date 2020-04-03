<?php
namespace Module\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Core\Main\Transformer\Resizeable;

class Product extends Model
{
	use Resizeable;

    protected $fillable = [
    ];

    public function categories(){
    	return $this->belongsToMany('Module\Service\Models\ServiceCategory', 'product_to_categories', 'product_id', 'category_id');
    }

}
