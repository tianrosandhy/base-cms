<?php
//combine permission data
return [
	'Posts' => [
		'Post' => [
			'admin.post.index',
			'admin.post.store',
			'admin.post.update',
			'admin.post.delete',
			'admin.post.switch',
		],

		'Category' => [
			'admin.post_category.index',
			'admin.post_category.store',
			'admin.post_category.update',
			'admin.post_category.delete',
			'admin.post_category.switch',
		]
	],
];