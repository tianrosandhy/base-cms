<?php
//default CRUD route
Route::group(['prefix' => 'media'], function(){
	Route::get('/', 'MediaController@index')->name('admin.media.index');
	Route::match(['get', 'post'], 'load', 'MediaController@load');
	Route::post('delete', 'MediaController@delete')->name('admin.media.delete');
});