<?php
namespace Module\Navigation\Facades;

use Illuminate\Support\Facades\Facade;
use Module\Navigation\Services\NavigationInstance;

class NavigationFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return NavigationInstance::class;
    }
}
