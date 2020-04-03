<?php
//combine menu structure
return [
	'admin' => [
		'menu' => [
			'Themes' => [
				'url' => '#',
				'icon' => 'fa fa-paint-brush',
				'sort' => 200,
				'submenu' => [
					'Theme Option' => [
						'route' => 'admin.theme-option.index',
					],
					'Theme Selection' => [
						'route' => 'admin.themes.index',
					]
				]
			]
		],
	]
];