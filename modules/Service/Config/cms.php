<?php
//combine menu structure
return [
	'admin' => [
		'menu' => [
			'Services' => [
				'url' => '#',
				'icon' => 'fa fa-smile-o',
				'sort' => 3,
				'submenu' => [
					'Data' => [
						'route' => 'admin.service.index'
					],
					'Category' => [
						'route' => 'admin.service_category.index'
					]
				]
			],
		],
	]
];