<?php
return [
	'navigation' => [
		'upload_path' => 'navigation',
		'export_excel' => false,
		'lang_data' => [
			'index.title' => 'Navigation Data',
			'create.title' => 'Add New Navigation',
			'edit.title' => 'Edit Navigation Data',

			'store.success' => 'Navigation data has been saved',
			'update.success' => 'Navigation data has been updated',
			'delete.success' => 'Navigation data has been deleted',
		],
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
			'url' => [
				'label' => 'Free URL',
				'url' => '',
				'fillable' => true,
			],
			'post category' => [
				'label' => 'Post Category',
				'url' => 'category/',
				'fillable' => true,
				'model_source' => 'post_category',
				'source_is_active_field' => 'is_active',
				'source_slug' => 'slug',
				'source_label' => 'name',
			],
			'post detail' => [
				'label' => 'Post Detail',
				'url' => 'blog/detail/',
				'fillable' => true,
				'model_source' => 'post',
				'source_is_active_field' => 'is_active',
				'source_slug' => 'slug',
				'source_label' => 'title',
			],
			'pages' => [
				'label' => 'Static Page',
				'url' => 'page/',
				'fillable' => true,
				'model_source' => 'page',
				'source_is_active_field' => 'is_active',
				'source_slug' => 'slug',
				'source_label' => 'title',
			]
		]
	],
];