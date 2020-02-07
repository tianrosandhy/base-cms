<?php
return [
	'banner' => [
		'upload_path' => 'banner',
		'export_excel' => false,
		'lang_data' => [
			'index.title' => 'Banner Data',
			'create.title' => 'Add New Banner',
			'edit.title' => 'Edit Banner Data',

			'store.success' => 'Banner data has been saved',
			'update.success' => 'Banner data has been updated',
			'delete.success' => 'Banner data has been deleted',
		],
		'view' => [
			'index' => 'main::master-table',
			'create' => 'main::master-crud',
			'edit' => 'main::master-crud',
		],
	],
];