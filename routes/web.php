<?php
Route::get('/', function () {
	$installed = true;
	try{
		$check = \DB::table('cms_install')->get();
	}catch(\Exception $e){
		$installed = false;
	}

	if(!$installed){
		return redirect()->route('cms.install');
	}
	else{
		return view('welcome');
	}
});
