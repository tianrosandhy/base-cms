<?php
namespace Module\Banner\Facades;

use Illuminate\Support\Facades\Facade;
use Module\Banner\Services\BannerInstance;

class BannerFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return BannerInstance::class;
    }
}
