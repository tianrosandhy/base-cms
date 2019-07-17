<?php
return [
	'main' => [
		'upload_path' => 'default',
	],
	'user' => [
		'upload_path' => 'user',
		'export_excel' => true
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