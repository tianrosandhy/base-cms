<?php
namespace Module\Post;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Core\Main\BaseServiceProvider;

class PostServiceProvider extends BaseServiceProvider
{
	protected $namespace = 'Module\Post\Http\Controllers';

	public function boot(){
		$this->loadMigrationsFrom(realpath(__DIR__."/Migrations"));
		$this->loadTranslationsFrom(__DIR__ . '/Translation', 'post');
	}

	protected function mapping(Router $router){
		$router->group([
			'namespace' => $this->namespace, 
			'middleware' => [
				'web',
				\Core\Main\Http\Middleware\PermissionManagement::class,				
			]
		], function($router){
			$router->group(['prefix' => admin_prefix()], function(){
				require realpath(__DIR__."/Routes/api.php");
				require realpath(__DIR__."/Routes/web.php");
			});
		});
	}


	public function register(){
    $this->loadHelpers(__DIR__);
		$this->mapping($this->app->router);
		$this->loadViewsFrom(realpath(__DIR__."/Views"), 'post');

	    //merge config
	    $this->mergeConfigLists([
	    	'model' => __DIR__.'/Config/model.php',
	    	'cms' => __DIR__.'/Config/cms.php',
	    	'permission' => __DIR__.'/Config/permission.php',
	    	'module-setting' => __DIR__.'/Config/module-setting.php',
	    ]);
	    $this->registerFacadeAlias('PostInstance', \Module\Post\Facades\PostFacade::class);
	}


}