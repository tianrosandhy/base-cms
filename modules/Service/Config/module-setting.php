<?php
return [
	'service' => [
		'upload_path' => 'service',
		'export_excel' => false,
		'lang_data' => [
			'index.title' => 'Service Data',
			'create.title' => 'Add New Service',
			'edit.title' => 'Edit Service Data',

			'store.success' => 'Service data has been saved',
			'update.success' => 'Service data has been updated',
			'delete.success' => 'Service data has been deleted',
		],
		'view' => [
			'index' => 'main::master-table',
			'create' => 'main::master-crud',
			'edit' => 'main::master-crud',
		],
	],
	'service_category' => [
		'upload_path' => 'service_category',
		'export_excel' => false,
		'lang_data' => [
			'index.title' => 'Service Category Data',
			'create.title' => 'Add New Service Category',
			'edit.title' => 'Edit Service Category Data',

			'store.success' => 'Service Category data has been saved',
			'update.success' => 'Service Category data has been updated',
			'delete.success' => 'Service Category data has been deleted',
		],
		'view' => [
			'index' => 'main::master-table',
			'create' => 'main::master-crud',
			'edit' => 'main::master-crud',
		],
	],

];