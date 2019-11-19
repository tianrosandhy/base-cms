<?php
return [
	'post' => [
		'upload_path' => 'post',
		'export_excel' => false,
		'lang_data' => [
			'index.title' => 'Post Data',
			'create.title' => 'Add New Post',
			'edit.title' => 'Edit Post Data',

			'store.success' => 'Post data has been saved',
			'update.success' => 'Post data has been updated',
			'delete.success' => 'Post data has been deleted',
		],
		'view' => [
			'index' => 'main::master-table',
			'create' => 'main::master-crud',
			'edit' => 'main::master-crud',
		],
	],

	'post_category' => [
		'upload_path' => 'post',
		'export_excel' => false,
		'lang_data' => [
			'index.title' => 'Category Data',
			'create.title' => 'Add New Category',
			'edit.title' => 'Edit Category Data',

			'store.success' => 'Category data has been saved',
			'update.success' => 'Category data has been updated',
			'delete.success' => 'Category data has been deleted',
		],
		'view' => [
			'index' => 'main::master-table',
			'create' => 'main::master-crud',
			'edit' => 'main::master-crud',
		],
	],
];