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
	],
];