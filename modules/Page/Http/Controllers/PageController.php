<?php
namespace Module\Page\Http\Controllers;

use Core\Main\Http\Repository\CrudRepository;
use Core\Main\Http\Controllers\AdminBaseController;
use Module\Page\Http\Skeleton\PageSkeleton;
use Core\Main\Transformer\Exportable;
use Core\Main\Transformer\Seo;
use Core\Main\Contracts\Crud;
use Core\Main\Http\Traits\BasicCrud;

class PageController extends AdminBaseController implements Crud
{
	use BasicCrud;
	use Exportable, Seo;
	use Extension\PageExtension;

	public 
		$hint = 'page',
		$as_ajax = false,
		$multi_language = true;


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