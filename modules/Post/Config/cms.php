<?php
//combine menu structure
return [
	'admin' => [
		'menu' => [
			'Article' => [
				'icon' => 'fa fa-newspaper-o',
				'sort' => 0,
				'submenu' => [
					'Categories' => [
						'route' => 'admin.post_category.index',
					],
					'Posts' => [
						'route' => 'admin.post.index',
					],
					// 'Posts' => [
					// 	'submenu' => [
					// 		'Data' => [
					// 			'route' => 'admin.post.index',
					// 		],
					// 		'Comments' => [
					// 			'route' => 'admin.post_comment.index'
					// 		],
					// 	]
					// ],
				]
			],
		],
	]
];