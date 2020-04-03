<?php
namespace Module\Page\Http\Controllers\Extensions;

// optional extendable class for delete  
trait PageDeleteExtension
{

	public function beforeDelete($instance){
		//logic before deleted instance
	}

	public function afterDelete($ids){
		// logic after deleted ids
	}

}