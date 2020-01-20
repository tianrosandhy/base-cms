<?php
//default CRUD route
Route::post('themes/set-active', 'ThemesController@setActive')->name('admin.themes.set_active');
generateAdminRoute('themes', 'ThemesController', 'themes');