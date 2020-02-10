<?php
return [
	'contact' => [
		'upload_path' => 'contact',
		'export_excel' => false,
		'lang_data' => [
			'index.title' => 'Contact Data',
			'create.title' => 'Add New Contact',
			'edit.title' => 'Edit Contact Data',

			'store.success' => 'Contact data has been saved',
			'update.success' => 'Contact data has been updated',
			'delete.success' => 'Contact data has been deleted',
		],
		'view' => [
			'index' => 'main::master-table',
			'create' => 'main::master-crud',
			'edit' => 'main::master-crud',
		],
	],
];