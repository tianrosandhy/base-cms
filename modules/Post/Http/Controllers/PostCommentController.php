<?php
namespace Module\Post\Http\Controllers;

use Core\Main\Http\Repository\CrudRepository;
use Core\Main\Http\Controllers\AdminBaseController;
use Module\Post\Http\Skeleton\PostCommentSkeleton;
use Core\Main\Transformer\Exportable;

class PostCommentController extends AdminBaseController
{
	use Exportable;
	public $hint = 'post_comment';

	public function repo(){
		return $this->hint;
	}

	public function skeleton(){
		return new PostCommentSkeleton;
	}

}