<?php
//combine menu structure
return [
	'admin' => [
		'menu' => [
			'Posts' => [
				'icon' => 'fa fa-check-circle',
				'sort' => 0,
				'submenu' => [
					'Categories' => [
						'route' => 'admin.post_category.index',
					],
					'Posts Data' => [
						'route' => 'admin.post.index',
					],
					'Post Comments' => [
						'route' => 'admin.post_comment.index'
					],
				]
			],
		],
	]
];