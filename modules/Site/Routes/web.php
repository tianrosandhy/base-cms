<?php
Route::get('/', 'SiteController@index')->name('front.homepage');
Route::get('page/{slug}', 'SiteController@page')->name('front.page.detail');
Route::get('post', 'SiteController@blog')->name('front.post');
Route::get('post/{slug}', 'SiteController@post')->name('front.post.detail');
