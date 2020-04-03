<?php
//if there is a module you want to unregister, just hide the array values below
return [
	'load' => [
		\Core\Media\MediaServiceProvider::class,
		\Core\Language\LanguageServiceProvider::class,
		\Core\Themes\ThemesServiceProvider::class,
		\Module\Test\TestServiceProvider::class,

		\Module\Api\ApiServiceProvider::class,
		\Module\Ehehe\EheheServiceProvider::class,
		\Module\Navigation\NavigationServiceProvider::class,
		\Module\Post\PostServiceProvider::class,
		\Module\Page\PageServiceProvider::class,
		\Module\Contact\ContactServiceProvider::class,
		\Module\Site\SiteServiceProvider::class,
	],
];