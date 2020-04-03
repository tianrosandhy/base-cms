<?php
return [
	'api' => [
		'upload_path' => 'api',
		'export_excel' => false,
		'view' => [
			'index' => 'main::master-table',
			'create' => 'main::master-crud',
			'edit' => 'main::master-crud',
		],
	],
];