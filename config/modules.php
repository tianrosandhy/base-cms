<?php
//if there is a module you want to unregister, just hide the array values below
return [
	'load' => [
		\Module\Post\PostServiceProvider::class,
	],
];