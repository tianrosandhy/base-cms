<?php
namespace Module\Page\Http\Controllers\Extensions;

// optional extendable class for index  
trait PageIndexExtension
{

	public function prependIndex(){
		//return html rendered view before table data
	}
	
	public function appendIndex(){
		//return html rendered view after table data
	}
	
	public function appendIndexControlButton(){
		//return html rendered view in control buttons
	}

}