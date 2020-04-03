<?php
namespace Module\Page\Http\Controllers\Extensions;

// optional extendable class for index  
trait PageFormExtension
{
	public function prependForm($instance=null){
		// return html rendered view before form box
	}

	public function appendForm($instance=null){
		// return html rendered view after form box
	}
}