<?php
Route::get('blank/export', 'BlankController@export')->name('admin.blank.export');
Route::post('blank/switch', 'BlankController@switch')->name('admin.blank.switch');
Route::get('blank', 'BlankController@index')->name('admin.blank.index');
Route::get('blank/create', 'BlankController@create')->name('admin.blank.store');
Route::post('blank/create', 'BlankController@store');
Route::get('blank/{id}', 'BlankController@edit')->name('admin.blank.edit');
Route::post('blank/{id}', 'BlankController@update')->name('admin.blank.update');
Route::post('blank/delete/{id}', 'BlankController@delete')->name('admin.blank.delete');
