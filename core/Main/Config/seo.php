<?php
return [
	//serve as default SEO variables
	'default' => [
		'fb_app' => env('FACEBOOK_APP_ID'),
		'type' => 'website',
		'author' => 'Name',
		'locale' => 'id_ID',
		'twitter_username' => '',

		//dynamically overwritten
		'title' => '',
		'description' => '',
		'keywords' => '',
		'image' => '',
	],
];
