<?php
return [
	'max_filesize' => [
		//sekalipun sudah dilimit (dalam MB), tapi kalau dari servernya cuma support dibawah X MB, yg dipakai adalah nilai terkecil
		'image' => 5,
		'file' => 10
	],

	'social_login' => true,
	'social_driver' => [
		'facebook',
		// 'twitter',
		'google'
	],

	'front' => [
		'logo' => 'img/fe_logo.png'
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
		'styling' => [
			'header' => [
				'background' => '#fff',
				'line_color' => '#fff',
				'line_height' => 0,
				'text_color' => '#333',
			],
		],

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
						'icon' => '',
					],
					'Logs' => [
						'route' => 'admin.log.index',
						'icon' => '',
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
						'icon' => ''
					],
					'User Lists' => [
						'route' => 'admin.user.index',
						'icon' => ''
					]

				]
			],

		],

	],

];