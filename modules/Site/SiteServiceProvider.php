<?php
namespace Module\Site;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Module\Main\BaseServiceProvider;
use Illuminate\Foundation\AliasLoader;

class SiteServiceProvider extends BaseServiceProvider
{
	protected $namespace = 'Module\Site\Http\Controllers';

	public function boot(){
		$this->loadMigrationsFrom(realpath(__DIR__."/Migrations"));
	}

	protected function mapping(Router $router){
		$router->group([
			'namespace' => $this->namespace, 
			'middleware' => [
				'web'
			]
		], function($router){
			require realpath(__DIR__."/Routes/api.php");
			require realpath(__DIR__."/Routes/web.php");
		});
	}


	public function register(){
		$this->mapping($this->app->router);
		$this->loadViewsFrom(realpath(__DIR__."/Views"), 'site');

		//merge config
	    $this->mergeConfigFrom(
	        __DIR__.'/Config/module-setting.php', 'module-setting'
	    );

	    $this->registerAlias();
	}


	protected function registerAlias(){
		$this->app->bind('site-facade', function ($app) {
            return new Services\SiteInstance($app);
        });

        $aliasData = [
	        'SiteInstance' => \Module\Site\Facades\SiteFacade::class,
        ];

        foreach($aliasData as $al => $src){
        	AliasLoader::getInstance()->alias($al, $src);
        }
	}

}