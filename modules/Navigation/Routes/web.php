<?php
//default CRUD route
generateAdminRoute('navigation', 'NavigationController', 'navigation');
Route::get('navigation-refresh/{id}', 'NavigationController@refresh');
Route::get('navigation/manage/{id}', 'NavigationController@manage')->name('admin.navigation_item.manage');
Route::post('navigation/manage/{id}', 'NavigationController@storeManaged')->name('admin.navigation_item.store');
Route::get('navigation-form/{id}', 'NavigationController@getEditForm')->name('admin.navigation_item.edit');
Route::post('navigation-item/delete/{id}', 'NavigationController@deleteItem')->name('admin.navigation_item.delete');
Route::post('navigation-item/reorder/{id}', 'NavigationController@reorder')->name('admin.navigation_item.reorder');