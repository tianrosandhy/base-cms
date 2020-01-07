<?php
namespace Module\Page\Http\Controllers;

use Module\Main\Http\Repository\CrudRepository;
use Module\Main\Http\Controllers\AdminBaseController;
use Module\Page\Http\Skeleton\PageSkeleton;
use Module\Main\Transformer\Exportable;
use Module\Main\Transformer\Seo;

class PageController extends AdminBaseController
{
	use Exportable, Seo;
	public 
		$hint = 'page',
		$as_ajax = false;


	public function repo(){
		return $this->hint;
	}

	public function skeleton(){
		return new PageSkeleton;
	}

	public function detail($id=0){
		$data = $this->repo->show($id);
		if(empty($data)){
			abort(404);
		}

		$title = 'Page Detail';
		return view('page::detail', compact(
			'title',
			'data'
		));
	}

}