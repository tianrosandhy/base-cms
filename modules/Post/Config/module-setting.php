<?php
return [
	'post' => [
		'upload_path' => 'post',
		'export_excel' => true,
		'view' => [
			'index' => 'main::master-table',
			'create' => 'main::master-crud',
			'edit' => 'main::master-crud',
		],
	],

	'post_category' => [
		'upload_path' => 'post',
		'export_excel' => false,
		'view' => [
			'index' => 'main::master-table',
			'create' => 'main::master-crud',
			'edit' => 'main::master-crud',
		],
	],

	'post_comment' => [
		'upload_path' => 'post',
		'export_excel' => false,
		'view' => [
			'index' => 'main::master-table',
			'create' => 'main::master-crud',
			'edit' => 'main::master-crud',
		],
	],
];