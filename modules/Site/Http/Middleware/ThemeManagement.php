<?php
namespace Module\Site\Http\Middleware;

use Closure;
use LanguageInstance;
use ThemesInstance;

class ThemeManagement
{
    public function handle($request, Closure $next)
    {   
    	//in case default navigation masih kosong
    	\NavigationInstance::generateDefaultNavigation();
    	//in case theme blm dibuild, build di middleware ini, lalu reload
    	if(!ThemesInstance::hasDefaultValues()){
    		ThemesInstance::createDefaultValues();
    		return redirect(url()->current());
    	}

    	return $next($request);
    }

}