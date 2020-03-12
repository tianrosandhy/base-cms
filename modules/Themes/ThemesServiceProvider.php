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
        $this->loadHelpers(__DIR__);
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
     * Register all themes with activating them
     */
    private function registerAllThemes($active_theme = null)
    {
		$directories = $this->app['files']->directories(config('cms.themes.paths'));
        foreach ($directories as $directory) {
			if(preg_match('/'.$active_theme.'/',$directory)) {
				Appearances::registerPath($directory, true);
			}
        }
    }

	/**
     * Set the active theme based on the settings
     */
    private function setActiveTheme()
    {
		$installed = true;
		try{
		    $check = \DB::table('cms_installs')->get();
		}catch(\Exception $e){
		    $installed = false;
		}
		
		if($installed) {		
			$admin_prefix = config('cms.admin.prefix', 'p4n3lb04rd');
			$active_theme = setting('site.frontend_theme');
			if($active_theme){
        $this->registerAllThemes($active_theme);
        if(!preg_match('/'.$admin_prefix.'/',\Request::path())) {
          Appearances::activate($active_theme, true);
        }
      }

		}

        return true;
    }

}