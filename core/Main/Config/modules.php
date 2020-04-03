<?php
//if there is a module you want to unregister, just hide the array values below
return [
	'load' => [
		\Core\Media\MediaServiceProvider::class,
		\Core\Language\LanguageServiceProvider::class,
		\Core\Themes\ThemesServiceProvider::class,

	    //register new module service provider here 
	    //or just add to provider lists in config/app.php
	],
];