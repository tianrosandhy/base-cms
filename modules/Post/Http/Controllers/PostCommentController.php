<?php
namespace Module\Post\Http\Controllers;

use Core\Main\Http\Repository\CrudRepository;
use Core\Main\Http\Controllers\AdminBaseController;
use Module\Post\Http\Skeleton\PostCommentSkeleton;
use Core\Main\Transformer\Exportable;
use Core\Main\Contracts\Crud;
use Core\Main\Http\Traits\BasicCrud;

class PostCommentController extends AdminBaseController implements Crud
{
	use BasicCrud;
	use Exportable;
	public $module = 'post';
	public $hint = 'post_comment';

	public function repo(){
		return $this->hint;
	}

	public function skeleton(){
		return new PostCommentSkeleton;
	}

}