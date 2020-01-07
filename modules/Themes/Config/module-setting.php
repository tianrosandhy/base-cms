<?php
return [
	'themes' => [
		'upload_path' => 'themes',
		'export_excel' => false,
		'lang_data' => [
			'index.title' => 'Themes Data',
			'create.title' => 'Add New Themes',
			'edit.title' => 'Edit Themes Data',

			'store.success' => 'Themes data has been saved',
			'update.success' => 'Themes data has been updated',
			'delete.success' => 'Themes data has been deleted',
		],
		'view' => [
			'index' => 'main::master-table',
			'create' => 'main::master-crud',
			'edit' => 'main::master-crud',
		],
	],
];