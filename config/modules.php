<?php
//if there is a module you want to unregister, just hide the array values below
return [
	'load' => [
		\Module\Navigation\NavigationServiceProvider::class,
		\Module\Post\PostServiceProvider::class,
		\Module\Page\PageServiceProvider::class,
		\Module\Banner\BannerServiceProvider::class,
		\Module\Service\ServiceServiceProvider::class,
		\Module\Media\MediaServiceProvider::class,
		\Module\Site\SiteServiceProvider::class,
		\Module\Themes\ThemesServiceProvider::class,
	],
];