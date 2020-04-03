<?php
namespace Module\Page\Http\Controllers\Extensions;

// optional extendable class for index  
trait PageCrudExtension
{

	public function afterCrud($instance){
		//logic after instance stored/updated
	}

}