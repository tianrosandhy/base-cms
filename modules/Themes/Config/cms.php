<?php
//combine menu structure
return [
	'admin' => [
		'menu' => [
			'Settings' => [
				'submenu' => [
					'Themes' => [
						'route' => 'admin.themes.index',
						'icon' => 'fa fa-paint-brush',
						'sort' => 19
					]
				]
			],
		],
	]
];