<?php
namespace Module\Main;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Validator;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Database\Schema\Builder;
use Module\Main\Models\SettingStructure;
use Module\Main\Models\Role;

class MainServiceProvider extends ServiceProvider
{
	protected $namespace = 'Module\Main\Http\Controllers';

	public function boot(){
		Builder::defaultStringLength(191);
		//load migrations table
		$this->loadMigrationsFrom(realpath(__DIR__."/Migrations"));
		$this->registerValidator();
		$this->publishAssets();
	}

	protected function publishAssets(){
		//register published config
		$this->publishes([
			__DIR__.'/Config/cms.php' => config_path('cms.php'),
			__DIR__.'/Config/seo.php' => config_path('seo.php'),
			__DIR__.'/Config/permission.php' => config_path('permission.php'),
			__DIR__.'/Config/model.php' => config_path('model.php'),
			__DIR__.'/Config/image.php' => config_path('image.php'),
			__DIR__.'/Config/modules.php' => config_path('modules.php'),
			__DIR__.'/Config/module-setting.php' => config_path('module-setting.php'),
		], 'tianrosandhy-cms');
	}

	

	protected function registerValidator(){
		//create custom validator
		Validator::extend('strict_mail', function($attr, $value, $param, $validator){

			//validasi standar
			if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
	    		return false;
	    	}

	    	//validasi dns record aktif
	    	$exp = explode("@", $value);
	    	$host = $exp[1]; //berhubung udah di filter_var, mestinya sih index 1 valid
	    	if(!checkdnsrr($host, "MX")){
	    		return false;
	    	}

	    	//validasi blacklist check
	    	$blacklist = config('blacklist.mailhost');  
	    	$list = preg_split("/[\r\n]+/", $blacklist);
	    	if(in_array($host, $list)){
	    		return false;
	    	}

	    	return true;
		});

	}

	






	public function register(){
		$this->loadHelpers(__DIR__);
		$this->mapping($this->app->router);
		$this->loadViewsFrom(realpath(__DIR__."/Views"), 'main');
		$this->loadModules();
		$this->mergeMainConfig();
		$this->registerAlias();
		$this->registerContainer();

		$this->commands([
			Console\UpdateStructure::class,
			Console\ModuleScaffold::class,
			Console\NewAdmin::class,
			Console\SetRole::class,
		]);
	}

	public function registerContainer(){
		$this->app->singleton('setting', function($app){
			return SettingStructure::get();
		});
		$this->app->singleton('role', function($app){
			return Role::with('owner', 'children')->get();
		});
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
				require realpath(__DIR__."/Routes/api.php");
			});
		});

		$router->group([
			'namespace' => $this->namespace, 
			'prefix' => 'install',
			'middleware' => [
				'web'
			]
		], function($router){
			require realpath(__DIR__."/Routes/install.php");
		});

	}

	protected function mergeMainConfig(){
		//main config files
		$this->mergeConfigFrom(
	        __DIR__.'/Config/permission.php', 'permission'
	    );
		$this->mergeConfigFrom(
	        __DIR__.'/Config/model.php', 'model'
	    );
		$this->mergeConfigFrom(
	        __DIR__.'/Config/blacklist.php', 'blacklist'
	    );
		$this->mergeConfigFrom(
	        __DIR__.'/Config/cms.php', 'cms'
	    );
	    $this->mergeConfigFrom(
	        __DIR__.'/Config/image.php', 'image'
	    );
	    $this->mergeConfigFrom(
	        __DIR__.'/Config/modules.php', 'modules'
	    );
	    $this->mergeConfigFrom(
	        __DIR__.'/Config/module-setting.php', 'module-setting'
	    );
	    $this->mergeConfigFrom(
	        __DIR__.'/Config/seo.php', 'seo'
	    );
	}


	protected function loadModules(){
	    $listModule = config('modules.load');
	    if($listModule){
		    foreach($listModule as $mod){
		    	if(class_exists($mod)){
				    $this->app->register($mod);
		    	}
		    }
	    }
	}


	protected function registerAlias(){
		$this->app->bind('image-facade', function ($app) {
            return new Services\ImageServices($app);
        });
        $this->app->bind('cms-facade', function ($app) {
            return new Services\CmsServices($app);
        });

        $this->app->bind('datastructure-facade', function ($app) {
            return new Services\DataStructure($app);
        });
        $this->app->bind('datasource-facade', function ($app) {
            return new Services\DataSource($app);
        });


        //automatically load alias
        $aliasData = [
	        'ImageService' => \Module\Main\Facades\ImageFacades::class,
	        'CMS' => \Module\Main\Facades\CmsFacades::class,
	        'DataStructure' => \Module\Main\Facades\DataStructureFacades::class,
	        'DataSource' => \Module\Main\Facades\DataSourceFacades::class,
	        'Setting' => \Module\Main\Facades\SettingFacades::class,
	        'Input' => \Module\Main\Facades\InputFacades::class,
	        'SlugInstance' => \Module\Main\Facades\SlugInstanceFacades::class,
        ];

        foreach($aliasData as $al => $src){
        	AliasLoader::getInstance()->alias($al, $src);
        }
	}

	protected function loadHelpers($dir){
		foreach (glob($dir.'/Helper/*.php') as $filename) {
			require_once $filename;
		}
	}

}