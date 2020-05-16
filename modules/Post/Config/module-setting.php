<?php
return [
	'post' => [
		'upload_path' => 'post',
		'export_excel' => true,
		'hide_create' => false,
		'save_always_exit' => true,
		'view' => [
			'index' => 'main::master-table',
			'create' => 'main::master-crud',
			'edit' => 'main::master-crud',
		],
	],

	'post_category' => [
		'upload_path' => 'post',
		'export_excel' => false,
		'hide_create' => false,
		'save_always_exit' => false,
		'view' => [
			'index' => 'main::master-table',
			'create' => 'main::master-crud',
			'edit' => 'main::master-crud',
		],
	],

	'post_comment' => [
		'upload_path' => 'post',
		'export_excel' => false,
		'hide_create' => true,
		'save_always_exit' => false,
		'view' => [
			'index' => 'main::master-table',
			'create' => 'main::master-crud',
			'edit' => 'main::master-crud',
		],
	],
];