<?php
//default CRUD route
generateAdminRoute('navigation', 'NavigationController', 'navigation');
Route::get('navigation/manage/{id}', 'NavigationController@manage')->name('admin.navigation.manage');
Route::post('navigation/manage/{id}', 'NavigationController@storeManaged');
