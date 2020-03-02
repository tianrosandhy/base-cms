<?php
namespace Module\Post\Http\Controllers;

use Module\Main\Http\Repository\CrudRepository;
use Module\Main\Http\Controllers\AdminBaseController;
use Module\Post\Http\Skeleton\PostCategorySkeleton;
use Module\Main\Transformer\Exportable;

class PostCategoryController extends AdminBaseController
{
	use Exportable;
	public $hint = 'post_category';
	public $multi_language = true;

	public function repo(){
		return $this->hint;
	}

	public function skeleton(){
		return new PostCategorySkeleton;
	}

	public function afterValidation($mode='create', $instance=null){

	}

	public function afterCrud($instance){

	}

	public function image_field(){
		return ['image'];
	}

}