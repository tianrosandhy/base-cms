<?php
return [
	'max_filesize' => [
		//sekalipun sudah dilimit (dalam MB), tapi kalau dari servernya cuma support dibawah X MB, yg dipakai adalah nilai terkecil
		'image' => 10,
		'file' => 15
	],

	'social_login' => true,
	'social_driver' => [
		'facebook',
		'google'
	],

	'front' => [
		'logo' => 'img/fe_logo.png'
	],
	'themes' => [
		'paths' => [
			realpath(public_path('themes')),
		],
		'activate' => null,
	],
	
	'admin' => [
		'auth_guard_name' => 'admin',
		'prefix' => 'p4n3lb04rd',
		'assets' => 'admin_theme',
		'google_analytic_dashboard' => true,
		'logo' => 'img/logo.png',
		'components' => [
			'register' => true,
			'forgot_password' => true,
			'userinfo' => true,
		],
		'email_receiver' => 'tianrosandhy@gmail.com',
		'menu' => [
			'Dashboard' => [
				'url' => '',
				'icon' => 'fa fa-home',
				'sort' => -9999,
			],

			'Settings' => [
				'url' => '#',
				'icon' => 'fa fa-cog',
				'sort' => 10000,
				'submenu' => [
					'General' => [
						'route' => 'admin.setting.index',
					],
					'Logs' => [
						'route' => 'admin.log.index',
					],
				],
			],

			'User Managements' => [
				'url' => '#',
				'icon' => 'fa fa-users',
				'sort' => 9999,
				'submenu' => [
					'Priviledge' => [
						'route' => 'admin.permission.index',
					],
					'User Lists' => [
						'route' => 'admin.user.index',
					]

				]
			],

		],

	],

];