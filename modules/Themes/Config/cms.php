<?php
//combine menu structure
return [
	'admin' => [
		'menu' => [
			'Theme Option' => [
				'route' => 'admin.theme-option.index',
				'icon' => 'fa fa-paint-brush',
				'sort' => 200
			],
			'Settings' => [
				'submenu' => [
					'Themes' => [
						'route' => 'admin.themes.index',
						'icon' => 'fa fa-paint-brush',
						'sort' => 19
					],
				]
			],
		],
	]
];