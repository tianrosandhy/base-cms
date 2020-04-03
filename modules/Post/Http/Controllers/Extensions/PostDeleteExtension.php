<?php
namespace Module\Post\Http\Controllers\Extensions;

// optional extendable class for delete  
trait PostDeleteExtension
{

	public function beforeDelete($instance){
		//logic before deleted instance
	}

	public function afterDelete($ids){
		// logic after deleted ids
	}

}