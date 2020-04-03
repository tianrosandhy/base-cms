<?php
namespace Module\Post\Http\Controllers\Extensions;

// optional extendable class for index  
trait PostCrudExtension
{

	public function afterCrud($instance){
		$cats = $this->request->category;
		if(!$this->request->category){
			$cats = [];
		}
		$this->handleStoreCategory($instance, $cats);

		$rel = $this->request->related;
		if(!$this->request->related){
			$rel = [];
		}
		$this->handleStoreRelated($instance, $rel);
	}

	protected function handleStoreRelated($instance, $relateds=[]){
		$relateds = get_lang($relateds);
		$relmodel = model('post_related');
		$check = $relmodel->where('post_id', $instance->id)->get();
		if(empty($relateds)){
			$relateds = [];
		}
		foreach($relateds as $rel){
			if($check->where('post_related_id', $instance->id)->count() == 0){
				$relmodel->insert([
					'post_id' => $instance->id,
					'post_related_id' => $rel
				]);
			}
		}

		if(!empty($relateds)){
			$relmodel->where('post_id', $instance->id)->whereNotIn('post_related_id', $relateds)->delete();
		}
		else{
			$relmodel->where('post_id', $instance->id)->delete();
		}
	}

	protected function handleStoreCategory($instance, $categories=[]){
		$categories = get_lang($categories);
		$catmodel = model('post_to_category');
		$check = $catmodel->where('post_id', $instance->id)->get();
		if(!empty($categories)){
			foreach($categories as $cat){
				if($check->where('post_category_id', $cat)->count() == 0){
					$catmodel->insert([
						'post_id' => $instance->id,
						'post_category_id' => $cat
					]);
				}
			}
		}

		if(!empty($categories)){
			$catmodel->where('post_id', $instance->id)->whereNotIn('post_category_id', $categories)->delete();
		}
		else{
			$catmodel->where('post_id', $instance->id)->delete();
		}
	}



}