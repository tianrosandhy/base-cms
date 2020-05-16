<?php
namespace Module\Post\Http\Controllers;

use Core\Main\Http\Repository\CrudRepository;
use Core\Main\Http\Controllers\AdminBaseController;
use Module\Post\Http\Skeleton\PostCategorySkeleton;
use Core\Main\Transformer\Exportable;
use Core\Main\Contracts\Crud;
use Core\Main\Http\Traits\BasicCrud;

class PostCategoryController extends AdminBaseController implements Crud
{
	use BasicCrud;
	use Exportable;
	public $hint = 'post_category';
	public $multi_language = true;

	public function translationModuleAlias(){
		return 'post';
	}

	public function repo(){
		return $this->hint;
	}

	public function skeleton(){
		return new PostCategorySkeleton;
	}


}