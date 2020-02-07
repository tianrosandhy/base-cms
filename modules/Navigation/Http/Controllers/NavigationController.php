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

	public function reorder($id){
		$data = $this->repo->show($id);
		if(empty($data)){
			abort(404);
		}

		$order_data = json_decode($this->request->data, true);
		if(!$order_data){
			abort(400);
		}

		$iteration = 1;
		foreach($order_data as $row){
			$processor = new NavigationProcessor($this->request);
			$processor->reorderData($row, $iteration++);
		}

		return [
			'type' => 'success',
			'message' => 'Reorder finish'
		];
	}

	public function refresh($id){
		$data = $this->repo->show($id);
		if(empty($data)){
			abort(404);
		}
		$structure = NavigationInstance::setData($data)->generateStructure();
		return view('navigation::partials.navigation-item-list', compact(
			'data',
			'structure'
		))->render();
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
			'structure',
			'id'
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
		if(!$this->request->title){
			return back()->with('error', 'Please fill the menu title')->withInput();
		}

		$processor = new NavigationProcessor($this->request);
		$processor->save();
		return redirect()->back()->with('success', 'Navigation item data has been saved');
	}

	public function deleteItem($id){
		$data = app(config('model.navigation_item'))->find($id);
		if(empty($data)){
			abort(404);
		}

		//cek peranakannya dulu. anak2nya diset ke parent yg sama spt data saat ini dulu
		$current_parent = $data->parent;
		if($data->children){
			foreach($data->children as $child){
				$child->parent = $current_parent;
				$child->save();
			}
		}
		$data->delete();
		return [
			'type' => 'success',
			'message' => 'Navigation item data has been deleted successfully'
		];
	}

}