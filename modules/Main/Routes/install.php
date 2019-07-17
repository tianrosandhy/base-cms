<?php
Route::get('/', 'InstallController@index')->name('cms.install');
Route::post('/', 'InstallController@process');