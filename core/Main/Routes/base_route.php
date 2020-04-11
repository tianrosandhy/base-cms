<?php
if(isset($bs_url) && isset($bs_controller) && isset($bs_route)){
	Route::post($bs_url.'/table', $bs_controller.'@table')->name('admin.'.$bs_route.'.datatable');
	Route::get($bs_url.'/export', $bs_controller.'@export')->name('admin.'.$bs_route.'.export');
	Route::get($bs_url.'/export-example', $bs_controller.'@exportExample')->name('admin.'.$bs_route.'.export-example');
	Route::post($bs_url.'/switch', $bs_controller.'@switch')->name('admin.'.$bs_route.'.switch');

	Route::get($bs_url.'', $bs_controller.'@index')->name('admin.'.$bs_route.'.index');
	Route::get($bs_url.'/create', $bs_controller.'@create')->name('admin.'.$bs_route.'.store');
	Route::post($bs_url.'/create', $bs_controller.'@store');
	Route::get($bs_url.'/edit/{id}', $bs_controller.'@edit')->name('admin.'.$bs_route.'.edit');
	Route::post($bs_url.'/edit/{id}', $bs_controller.'@update')->name('admin.'.$bs_route.'.update');
	Route::post($bs_url.'/delete/{id}', $bs_controller.'@delete')->name('admin.'.$bs_route.'.delete');
	Route::get($bs_url.'/revision/{id}/{rev}', $bs_controller.'@restoreRevision')->name('admin.'.$bs_route.'.revision');
}
