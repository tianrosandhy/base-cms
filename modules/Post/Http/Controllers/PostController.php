<?php
namespace Module\Post\Http\Controllers;

use Core\Main\Http\Repository\CrudRepository;
use Core\Main\Http\Controllers\AdminBaseController;
use Module\Post\Http\Skeleton\PostSkeleton;
use Core\Main\Transformer\Exportable;
use PostInstance;
use Core\Main\Transformer\Seo;
use Core\Main\Contracts\Crud;
use Core\Main\Http\Traits\BasicCrud;

class PostController extends AdminBaseController implements Crud
{
	use BasicCrud;
	use Exportable, Seo;
	use Extensions\PostExtension;


	public $hint = 'post';
	public $multi_language = true;

	public function repo(){
		return $this->hint;
	}

	public function skeleton(){
		return new PostSkeleton;
	}

	public function image_field(){
		return ['image'];
	}


	public function detail($id){
		$data = $this->repo->show($id);
		if(empty($data)){
			abort(404);
		}
		$title = $data->title;
		$structure = PostInstance::setData($data)->structure();
		return view('post::detail', compact(
			'title',
			'data',
			'structure'
		));
	}

	public function comment($id){
		$data = $this->repo->show($id);
		if(empty($data)){
			abort(404);
		}

		$this->request->validate([
			'message' => 'required'
		], [
			'message.required' => 'Please fill the message'
		]);

		//karena ini route admin, jadi comment semuanya as admin ya..
		$pass = [
			'message' => $this->request->message
		];

		if($this->request->reply_to){
			$pass['reply_to'] = $this->request->reply_to;
		}

		PostInstance::setData($data)->comment($pass, true);
		return back()->with('success', 'Comment data has been saved.');
	}


}