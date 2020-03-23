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
		$installed = true;
		try{
		    $check = \DB::table('cms_installs')->get();
		}catch(\Exception $e){
		    $installed = false;
		}

		if(!$installed){
			if(env('APP_ENV') == 'local'){
				$router->get('/', function(){
			        return redirect()->route('cms.install');		
				});
			}
			else{
				$router->get('/', function(){
					return 'Website is still not installed';
				});
			}
		}
		else{
			//site route only will be called when CMS has been installed
			$router->group([
				'namespace' => $this->namespace, 
				'middleware' => [
					'web',
					\Module\Site\Http\Middleware\LanguageManagement::class,
					\Module\Site\Http\Middleware\ThemeManagement::class,
				]
			], function($router){
				require realpath(__DIR__."/Routes/api.php");
				require realpath(__DIR__."/Routes/web.php");
			});
		}

	}


	public function register(){
    $this->loadHelpers(__DIR__);
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
        $this->app->bind('blank-facade', function ($app) {
            return new Services\BlankInstance($app);
        });

        $aliasData = [
	        'SiteInstance' => \Module\Site\Facades\SiteFacade::class,
	        'BlankInstance' => \Module\Site\Facades\BlankFacade::class,
        ];

        foreach($aliasData as $al => $src){
        	AliasLoader::getInstance()->alias($al, $src);
        }
	}

}