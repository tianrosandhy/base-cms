<?php
Route::get('/', 'MainController@index')->name('admin.splash');
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('login/social/{mode}', 'Auth\LoginController@socialRedirect')->name('admin.social-login');
Route::match(['get', 'post'], 'callback/{mode}', 'Auth\LoginController@socialRedirectHandle');
Route::match(['get', 'post'], 'logout', 'Auth\LoginController@logout');

Route::get('register', 'Auth\RegisterController@showRegistrationForm');
Route::post('register', 'Auth\RegisterController@register');

Route::get('activate/{hash}', 'Auth\ActivationController@index');
Route::post('resend-validation', 'Auth\ActivationController@resend');
Route::post('reset-password', 'Auth\ForgotPasswordController@index');
Route::get('reset-password/{hash}', 'Auth\ForgotPasswordController@changePassword');
Route::post('reset-password/{hash}', 'Auth\ForgotPasswordController@applyPassword');


Route::get('my-profile', 'Auth\ProfileController@index');
Route::post('my-profile',  'Auth\ProfileController@store');
Route::post('lang/{type}', 'MainController@switchLang');


Route::post('setting/table/user', 'UserManagementController@table')->name('admin.user.datatable');
Route::get('setting', 'SettingController@index')->name('admin.setting.index');
Route::post('setting', 'SettingController@store')->name('admin.setting.store');
Route::post('setting/update', 'SettingController@update')->name('admin.setting.update');
Route::post('setting/delete/{id}', 'SettingController@delete')->name('admin.setting.delete');
Route::post('setting/artisan', 'SettingController@postArtisan')->name('admin.maintenance.artisan');


Route::get('setting/permission', 'PermissionController@index')->name('admin.permission.index');
Route::post('setting/permission', 'PermissionController@store')->name('admin.permission.store');
Route::post('setting/permission/update/{id}', 'PermissionController@update')->name('admin.permission.update');
Route::post('setting/show-permission/{id}', 'PermissionController@showPermission')->name('admin.permission.manage');
Route::post('setting/permission/delete/{id}', 'PermissionController@delete')->name('admin.permission.delete');
Route::post('setting/save-permission/{id}', 'PermissionController@savePermission');


Route::get('setting/user/export', 'UserManagementController@export')->name('admin.user.export');
Route::get('setting/user', 'UserManagementController@index')->name('admin.user.index');
Route::get('setting/user/create', 'UserManagementController@create')->name('admin.user.store');
Route::post('setting/user/create', 'UserManagementController@store');
Route::get('setting/user/{id}', 'UserManagementController@edit')->name('admin.user.edit');
Route::post('setting/user/{id}', 'UserManagementController@update')->name('admin.user.update');
Route::post('setting/user/delete/{id}', 'UserManagementController@delete')->name('admin.user.delete');

Route::get('log', 'LogController@index')->name('admin.log.index');
Route::get('log/export', 'LogController@export')->name('admin.log.export');
Route::post('setting/log/delete/{id}', 'LogController@delete')->name('admin.log.delete');
