<?php
namespace Module\Navigation\Http\Controllers;

use Module\Main\Http\Repository\CrudRepository;
use Module\Main\Http\Controllers\AdminBaseController;
use Module\Navigation\Http\Skeleton\NavigationSkeleton;
use Module\Main\Transformer\Exportable;
use NavigationInstance;

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

		$structure = NavigationInstance::setData($data)->generateStructure();

		return view('navigation::manage', compact(
			'title',
			'data',
			'structure'
		));
	}

	public function storeManaged($id){

	}

}