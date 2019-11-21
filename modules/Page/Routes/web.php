<?php
//default CRUD route
generateAdminRoute('page', 'PageController', 'page');
Route::get('page/detail/{id}', 'PageController@detail')->name('admin.page.detail');