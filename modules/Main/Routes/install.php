<?php
if(env('APP_ENV') == 'local'){
	Route::get('/', 'InstallController@index')->name('cms.install');
	Route::post('/', 'InstallController@process');	
}
