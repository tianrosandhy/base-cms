<?php
//combine menu structure
return [
	'admin' => [
		'menu' => [
			'Article' => [
				'icon' => 'fa fa-check-circle',
				'sort' => 0,
				'submenu' => [
					'Categories' => [
						'route' => 'admin.post_category.index',
					],
					'Posts' => [
						'submenu' => [
							'Data' => [
								'route' => 'admin.post.index',
							],
							'Comments' => [
								'route' => 'admin.post_comment.index'
							],
						]
					],
				]
			],
		],
	]
];