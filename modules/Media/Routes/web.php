<?php
//default CRUD route
Route::group(['prefix' => 'media'], function(){
  Route::post('upload', 'MediaController@upload')->name('admin.media.upload');
  Route::post('upload-tinymce', 'MediaController@uploadTinyMce')->name('admin.media.upload-tiny-mce');
	Route::get('/', 'MediaController@index')->name('admin.media.index');
	Route::match(['get', 'post'], 'load', 'MediaController@load');
  Route::match(['get', 'post'], 'get-image-url', 'MediaController@getImageUrl')->name('get-image-url');
	Route::post('delete', 'MediaController@delete')->name('admin.media.delete');
});