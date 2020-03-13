<?php
Route::post('api/store-files', 'Api\FileStore@index');
Route::post('api/remove-files', 'Api\FileStore@removeFile');
