<?php
//combine menu structure
return [
	'admin' => [
		'menu' => [
			'Settings' => [
        'submenu' => [
          'API Management' => [
            'route' => 'admin.api.index',
          ]
        ]
			],
		],
	]
];