<?php
return [
	'api' => [
		'upload_path' => 'api',
		'export_excel' => false,
		'lang_data' => [
			'index.title' => 'API Credential Data',
			'create.title' => 'Add New API Credential',
			'edit.title' => 'Edit API Credential Data',

			'store.success' => 'API Credential data has been saved',
			'update.success' => 'API Credential data has been updated',
			'delete.success' => 'API Credential data has been deleted',
		],
		'view' => [
			'index' => 'main::master-table',
			'create' => 'main::master-crud',
			'edit' => 'main::master-crud',
		],
	],
];