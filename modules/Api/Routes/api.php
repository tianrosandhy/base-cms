<?php
//for test connection purpose
Route::get('/', 'ApiBaseController@index');




//fallback : object fetch, filter, & detail
Route::get('list/{object}', 'ApiBaseController@listObject');
Route::get('{object}/{id}', 'ApiBaseController@objectDetail');
