<?php
Route::group(['prefix' => 'api'], function(){
	Route::get('post', 'SiteController@apiPost');
});