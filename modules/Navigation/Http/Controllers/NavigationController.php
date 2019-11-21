<?php
namespace Module\Navigation\Http\Controllers;

use Module\Main\Http\Repository\CrudRepository;
use Module\Main\Http\Controllers\AdminBaseController;
use Module\Navigation\Http\Skeleton\NavigationSkeleton;
use Module\Main\Transformer\Exportable;

class NavigationController extends AdminBaseController
{
	use Exportable;
	public 
		$hint = 'navigation',
		$as_ajax = true;

	public function repo(){
		return $this->hint;
	}

	public function skeleton(){
		return new NavigationSkeleton;
	}

	public function manage($id){
		$title = 'Manage Navigation';
		$data = $this->repo->show($id);
		if(empty($data)){
			abort(404);
		}

		return view('navigation::manage', compact(
			'title',
			'data'
		));
	}

	public function storeManaged($id){

	}

}