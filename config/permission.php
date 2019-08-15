<?php
//format permission : 
//group -> item -> lists permission
return [
	'Settings' => [
		'General' => [
			'admin.setting.index',
			'admin.setting.store',
			'admin.setting.update',
			'admin.setting.delete',
		],

		'Log' => [
			'admin.log.index',
			'admin.log.delete',
		],
	],

	'User Management' => [
		'Permission' => [
			'admin.permission.index',
			'admin.permission.store',
			'admin.permission.update',
			'admin.permission.manage',
			'admin.permission.delete',
		],

		'User Lists' => [
			'admin.user.index',
			'admin.user.store',
			'admin.user.update',
			'admin.user.delete',
		],
		
	],
];