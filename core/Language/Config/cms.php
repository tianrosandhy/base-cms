<?php
//combine menu structure
return [
	'admin' => [
		'menu' => [
      'Settings' => [
        'submenu' => [
          'Language' => [
            'route' => 'admin.language.index',
          ],
        ]
      ]
		],
	]
];