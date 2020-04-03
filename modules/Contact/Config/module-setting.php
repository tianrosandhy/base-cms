<?php
return [
	'contact' => [
		'upload_path' => 'contact',
		'export_excel' => false,
		'view' => [
			'index' => 'main::master-table',
			'create' => 'main::master-crud',
			'edit' => 'main::master-crud',
		],
	],
];