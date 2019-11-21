<?php
return [
	'page' => [
		'upload_path' => 'page',
		'export_excel' => false,
		'lang_data' => [
			'index.title' => 'Page Data',
			'create.title' => 'Add New Page',
			'edit.title' => 'Edit Page Data',

			'store.success' => 'Page data has been saved',
			'update.success' => 'Page data has been updated',
			'delete.success' => 'Page data has been deleted',
		],
		'view' => [
			'index' => 'main::master-table',
			'create' => 'main::master-crud',
			'edit' => 'main::master-crud',
		],
	],
];