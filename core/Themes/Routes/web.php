<?php
//default CRUD route
Route::post('themes/set-active', 'ThemesController@setActive')->name('admin.themes.set_active');
Route::get('theme-option', 'ThemesController@themeOption')->name('admin.theme-option.index');
Route::post('theme-option', 'ThemesController@storeThemeOption')->name('admin.theme-option.store');
generateAdminRoute('themes', 'ThemesController', 'themes');

