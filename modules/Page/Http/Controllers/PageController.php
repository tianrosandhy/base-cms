<?php
namespace Module\Page\Http\Controllers;

use Module\Main\Http\Repository\CrudRepository;
use Module\Main\Http\Controllers\AdminBaseController;
use Module\Page\Http\Skeleton\PageSkeleton;
use Module\Main\Transformer\Exportable;
use Module\Main\Contracts\WithRevision;

class PageController extends AdminBaseController implements WithRevision
{
	use Exportable;
	public $hint = 'page';

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

	//untuk override store format. default : by struktur model
	public function storeRevisionFormat($instance){
		return null;
	}

	//manage revision shown fields
	public function reformatRevision($data=[]){
		return [
			'id' => $data['id'],
			'Title' => $data['title'],
			'Description' => descriptionMaker($data['description']),
			'Updated' => date('d M Y H:i:s', strtotime($data['updated_at']))
		];
	}

	//revision activate condition
	public function activateRevision($instance, $revision_data=[]){
		//dynamically restore revision data
		foreach($revision_data as $field => $value){
			if(in_array($field, ['id', 'created_at', 'updated_at', 'is_active'])){
				continue;
			}
			$instance->{$field} = $value;
		}
		$instance->save();
	}

}