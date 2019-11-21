<?php
namespace Module\Navigation\Facades;
use Illuminate\Support\Facades\Facade;
/**
 * @see \Illuminate\Foundation\Application
 */
class NavigationFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'navigation-facade';
    }
}
