<?php
return [
	'main' => [
		'upload_path' => 'default',
	],
	'user' => [
		'upload_path' => 'user',
		'export_excel' => false,
		'view' => [
			'index' => 'main::master-table',
			'create' => 'main::module.user-crud',
			'edit' => 'main::module.user-crud',
		],
	],
	'install' => [
		'used_env' => [
			'APP_URL',
			'APP_DEBUG',
			'DB_CONNECTION',
			'DB_HOST',
			'DB_PORT',
			'DB_DATABASE',
			'DB_USERNAME',
			'DB_PASSWORD'
		],
	],

];