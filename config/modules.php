<?php
//if there is a module you want to unregister, just hide the array values below
return [
	'load' => [
		\Module\Api\ApiServiceProvider::class,
		\Module\Language\LanguageServiceProvider::class,
		\Module\Navigation\NavigationServiceProvider::class,
		\Module\Post\PostServiceProvider::class,
		\Module\Page\PageServiceProvider::class,
		\Module\Service\ServiceServiceProvider::class,
		\Module\Product\ProductServiceProvider::class,
		\Module\Contact\ContactServiceProvider::class,
		\Module\Media\MediaServiceProvider::class,
		\Module\Site\SiteServiceProvider::class,
		\Module\Themes\ThemesServiceProvider::class,
	],
];