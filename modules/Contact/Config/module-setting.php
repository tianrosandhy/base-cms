<?php
return [
	'contact' => [
		'upload_path' => 'contact',
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