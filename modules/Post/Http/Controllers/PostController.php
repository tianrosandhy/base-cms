<?php
namespace Module\Post\Http\Controllers;

use Module\Main\Http\Repository\CrudRepository;
use Module\Main\Http\Controllers\AdminBaseController;
use Module\Post\Http\Skeleton\PostSkeleton;
use Module\Main\Transformer\Exportable;
use Module\Main\Contracts\WithRevision;

class PostController extends AdminBaseController implements WithRevision
{
	use Exportable;
	public $hint = 'post';

	public function repo(){
		return $this->hint;
	}


	public function afterCrud($instance){
		if(is_array($this->request->category)){
			$this->handleStoreCategory($instance, $this->request->category);
		}
	}

	protected function handleStoreCategory($instance, $categories=[]){
		$catmodel = app(config('model.post_to_category'));
		$check = $catmodel->where('post_id', $instance->id)->get();
		foreach($categories as $cat){
			if($check->where('post_category_id', $cat)->count() == 0){
				app(config('model.post_to_category'))->insert([
					'post_id' => $instance->id,
					'post_category_id' => $cat
				]);
			}
		}

		if(!empty($categories)){
			$catmodel->where('post_id', $instance->id)->whereNotIn('post_category_id', $categories)->delete();
		}
		else{
			$catmodel->where('post_id', $instance->id)->delete();
		}
	}


	public function storeRevisionFormat($instance){
		$base = $instance->toArray();
		if($instance->category->count() > 0){
			foreach($instance->category as $cat){
				$base['category'][] = [
					'id' => $cat->id,
					'name' => $cat->name,
					'slug' => $cat->slug
				];
			}
		}
		
		if($instance->related->count() > 0){
			foreach($instance->related as $rel){
				$base['related'][] = [
					'id' => $rel->id,
					'title' => $rel->title,
					'slug' => $rel->slug
				];
			}
		}
		return $base;
	}

	//manage revision shown fields
	public function reformatRevision($data=[]){
		$cat = '';
		if(isset($data['category'])){
			foreach($data['category'] as $c){
				$cat .= '<span class="badge badge-primary mb-1">'.$c['name'].'</span> ';
			}
		}

		if(strlen($cat) == 0){
			$cat = '-';
		}
		else{
			$cat = '<div>'.$cat.'</div>';
		}

		return [
			'id' => $data['id'],
			'Title' => $data['title'],
			'Description' => descriptionMaker($data['description'], 10),
			'Category' => $cat,
			'Updated' => date('d M Y H:i:s', strtotime($data['updated_at']))
		];
	}

	//revision activate condition
	public function activateRevision($instance, $revision_data=[]){
		//dynamically restore revision data
		foreach($revision_data as $field => $value){
			if(in_array($field, ['id', 'created_at', 'updated_at', 'is_active']) || is_array($value)){
				continue;
			}
			$instance->{$field} = $value;
		}
		$instance->save();

		if(isset($revision_data['category'])){
			$cats = collect($revision_data['category'])->pluck('id')->toArray();
			$this->handleStoreCategory($instance, $cats);
		}
	}


	public function skeleton(){
		return new PostSkeleton;
	}

	public function afterValidation($mode='create'){

	}

	public function image_field(){
		return ['image'];
	}

}