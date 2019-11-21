<?php
namespace Module\Post\Http\Controllers;

use Module\Main\Http\Repository\CrudRepository;
use Module\Main\Http\Controllers\AdminBaseController;
use Module\Post\Http\Skeleton\PostCommentSkeleton;
use Module\Main\Transformer\Exportable;

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