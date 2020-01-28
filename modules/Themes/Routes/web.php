<?php
//default CRUD route
Route::post('themes/set-active', 'ThemesController@setActive')->name('admin.themes.set_active');
Route::match(['get', 'post'], 'themes', 'ThemesController@index')->name('admin.themes.index');