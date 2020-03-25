<?php
Route::get('/', 'SiteController@index')->name('front.homepage');
Route::get('blog/{category?}', 'SiteController@blog')->name('front.blog');
Route::get('{slug}', 'SiteController@slugDetail')->name('front.detail');
Route::get('contact', 'SiteController@contact')->name('front.contact');
Route::post('contact', 'SiteController@sendContact')->name('front.send-contact');