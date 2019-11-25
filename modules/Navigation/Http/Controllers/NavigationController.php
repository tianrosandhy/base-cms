<?php
namespace Module\Navigation\Http\Controllers;

use Module\Main\Http\Repository\CrudRepository;
use Module\Main\Http\Controllers\AdminBaseController;
use Module\Navigation\Http\Skeleton\NavigationSkeleton;
use Module\Main\Transformer\Exportable;
use NavigationInstance;
use Module\Navigation\Http\Processors\NavigationProcessor;

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

	public function getEditForm($navid=0){
		$navigation = app(config('model.navigation_item'))->find($navid);
		if(empty($navigation)){
			abort(404);
		}

		$data = $navigation->group;
		return view('navigation::partials.navigation-item-form', compact(
			'data',
			'navigation'
		))->render();
	}

	public function storeManaged($id){
		$data = $this->repo->show($id);
		if(empty($data)){
			abort(404);
		}

		$processor = new NavigationProcessor($this->request);
		$processor->save();
		return redirect()->back()->with('success', 'Navigation item data has been saved');
	}

}