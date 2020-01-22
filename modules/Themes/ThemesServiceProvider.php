<?php
namespace Module\Themes;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Module\Main\BaseServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Module\Main\Models\SettingStructure;
use Appearances;

class ThemesServiceProvider extends BaseServiceProvider
{
	protected $namespace = 'Module\Themes\Http\Controllers';

	public function boot(){
		$this->loadMigrationsFrom(realpath(__DIR__."/Migrations"));
		$this->setActiveTheme();
		echo "boot";
		die();
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
				require realpath(__DIR__."/Routes/api.php");
				require realpath(__DIR__."/Routes/web.php");
			});
		});
	}


	public function register(){
		$this->mapping($this->app->router);
		$this->loadViewsFrom(realpath(__DIR__."/Views"), 'themes');

		//merge config
		$this->mergeConfigFrom(
	        __DIR__.'/Config/model.php', 'model'
	    );
	    $this->mergeConfigFrom(
	        __DIR__.'/Config/cms.php', 'cms'
	    );
		$this->mergeConfigFrom(
	        __DIR__.'/Config/permission.php', 'permission'
	    );
	    $this->mergeConfigFrom(
	        __DIR__.'/Config/module-setting.php', 'module-setting'
	    );

		$this->registerAlias();
	}


	protected function registerAlias(){
		$this->app->bind('themes-facade', function ($app) {
            return new Services\ThemesInstance($app);
        });

        $aliasData = [
	        'ThemesInstance' => \Module\Themes\Facades\ThemesFacade::class,
        ];

        foreach($aliasData as $al => $src){
        	AliasLoader::getInstance()->alias($al, $src);
        }
	}

	/**
     * Set the active theme based on the settings
     */
    private function setActiveTheme()
    {
		$active_theme = SettingStructure::where('param', 'frontend_theme')->first();
		$admin_prefix = config('cms.admin.prefix', 'p4n3lb04rd');
		if(!preg_match('/'.$admin_prefix.'/',\Request::path())) {
			Appearances::activate($active_theme->default_value, true);
        }

        return true;
    }

}