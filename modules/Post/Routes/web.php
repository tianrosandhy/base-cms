<?php
//default CRUD route
generateAdminRoute('post', 'PostController', 'post');
Route::get('post/detail/{id}', 'PostController@detail')->name('admin.post.detail');
Route::post('post/comment/{id}', 'PostController@comment')->name('admin.post.comment');


generateAdminRoute('post_category', 'PostCategoryController', 'post_category');
