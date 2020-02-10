<?php
return [
	'product' => [
		'upload_path' => 'product',
		'export_excel' => false,
		'lang_data' => [
			'index.title' => 'Product Data',
			'create.title' => 'Add New Product',
			'edit.title' => 'Edit Product Data',

			'store.success' => 'Product data has been saved',
			'update.success' => 'Product data has been updated',
			'delete.success' => 'Product data has been deleted',
		],
		'view' => [
			'index' => 'main::master-table',
			'create' => 'main::master-crud',
			'edit' => 'main::master-crud',
		],
	],
];