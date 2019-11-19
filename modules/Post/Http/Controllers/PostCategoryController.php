<?php
namespace Module\Post\Http\Controllers;

use Module\Main\Http\Repository\CrudRepository;
use Module\Main\Http\Controllers\AdminBaseController;
use Module\Post\Http\Skeleton\PostCategorySkeleton;
use Module\Main\Transformer\Exportable;
use Module\Main\Contracts\WithRevision;

class PostCategoryController extends AdminBaseController implements WithRevision
{
	use Exportable;
	public $hint = 'post_category';

	public function repo(){
		return $this->hint;
	}

	public function storeRevisionFormat($instance){
		return null;
	}

	//manage revision shown fields
	public function reformatRevision($data=[]){
		return [
			'id' => $data['id'],
			'Name' => $data['name'],
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


	public function skeleton(){
		return new PostCategorySkeleton;
	}

	public function afterValidation($mode='create'){

	}

	public function afterCrud($instance){

	}

	public function image_field(){
		return ['image'];
	}

}