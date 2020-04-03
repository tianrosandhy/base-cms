<?php
return [
	'navigation' => [
		'upload_path' => 'navigation',
		'export_excel' => false,
		'view' => [
			'index' => 'main::master-table',
			'create' => 'main::master-crud',
			'edit' => 'main::master-crud',
		],

		'action_type' => [
			'no action' => [
				'label' => 'No Action',
				'url' => '#',
				'fillable' => false,
			],
			'site' => [
				'label' => 'Website URL',
				'url' => '',
				'fillable' => true,
				'route_prefix' => 'front'
			],
			'url' => [
				'label' => 'Custom URL',
				'url' => '',
				'fillable' => true,
			],
			'post category' => [
				'label' => 'Post Category',
				'url' => 'blog/category/',
				'fillable' => true,
				'model_source' => 'post_category',
				'source_is_active_field' => 'is_active',
				'source_label' => 'name',
			],
			'post detail' => [
				'label' => 'Post Detail',
				'url' => '/',
				'fillable' => true,
				'model_source' => 'post',
				'source_is_active_field' => 'is_active',
				'source_label' => 'title',
			],
			'pages' => [
				'label' => 'Static Page',
				'url' => '/',
				'fillable' => true,
				'model_source' => 'page',
				'source_is_active_field' => 'is_active',
				'source_label' => 'title',
			]
		]
	],
];