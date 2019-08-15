<?php
return [
	'blank' => [
		'upload_path' => 'blank',
		'export_excel' => true,
		'lang_data' => [
			'index.title' => 'Blank Data',
			'create.title' => 'Add New Blank',
			'edit.title' => 'Edit Blank Data',

			'store.success' => 'Blank data has been saved',
			'update.success' => 'Blank data has been updated',
			'delete.success' => 'Blank data has been deleted',
		],
		'view' => [
			'index' => 'main::master-table',
			'create' => 'main::master-crud',
			'edit' => 'main::master-crud',
		],
	],
];