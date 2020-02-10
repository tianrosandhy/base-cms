<?php
namespace Module\Product\Http\Controllers;

use Module\Main\Http\Repository\CrudRepository;
use Module\Main\Http\Controllers\AdminBaseController;
use Module\Product\Http\Skeleton\ProductSkeleton;
use Module\Main\Transformer\Exportable;

class ProductController extends AdminBaseController
{
	use Exportable;
	//hint => used as route name, url name, view alias
	public $hint = 'product';

	public function repo(){
		//repo => model alias used (default : same as hint)
		return $this->hint;
	}

	public function skeleton(){
		return new ProductSkeleton;
	}

	public function afterValidation($mode='create', $instance=null){

	}

	public function afterCrud($instance){
		if(is_array($this->request->category)){
			$current_category = app(config('model.product_to_category'))->where('product_id', $instance->id)->get();
			foreach($this->request->category as $cat){
				if($current_category->where('category_id', $cat)->count() == 0){
					app(config('model.product_to_category'))->insert([
						'product_id' => $instance->id,
						'category_id' => $cat
					]);
				}
			}
			app(config('model.product_to_category'))->where('product_id', $instance->id)->whereNotIn('category_id', $this->request->category)->delete();
		}
	}

	public function image_field(){
		return ['image'];
	}

}