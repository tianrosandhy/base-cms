<?php
namespace Module\Api;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Module\Main\BaseServiceProvider;

class ApiServiceProvider extends BaseServiceProvider
{
	protected $namespace = 'Module\Api\Http\Controllers';

	public function boot(){
		$this->loadMigrationsFrom(realpath(__DIR__."/Migrations"));
	}

	protected function mapping(Router $router){
		$router->group([
			'namespace' => $this->namespace, 
			'middleware' => [
				'web',
				\Module\Main\Http\Middleware\PermissionManagement::class,				
			]
		], function($router){
			$router->group(['prefix' => admin_prefix()], function(){
				require realpath(__DIR__."/Routes/web.php");
			});
		});

		$router->group([
			'namespace' => $this->namespace,
			'middleware' => [
				'api',
				\Module\Api\Http\Middleware\KeyCheck::class
			],
			'prefix' => env('API_PREFIX', 'api')
		], function($router){
			require realpath(__DIR__."/Routes/api.php");
		});
	}


	public function register(){
    $this->loadHelpers(__DIR__);
		$this->mapping($this->app->router);
		$this->loadViewsFrom(realpath(__DIR__."/Views"), 'api');

		//merge config
	    $this->mergeConfigLists([
	    	'model' => __DIR__.'/Config/model.php',
	    	'cms' => __DIR__.'/Config/cms.php',
	    	'permission' => __DIR__.'/Config/permission.php',
	    	'module-setting' => __DIR__.'/Config/module-setting.php',
	    ]);

	    $this->registerFacadeAlias('ApiInstance', \Module\Api\Facades\ApiFacade::class);
	}

}