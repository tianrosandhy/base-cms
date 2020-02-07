<?php
namespace Module\Service\Facades;

use Illuminate\Support\Facades\Facade;
use Module\Service\Services\ServiceInstance;

class ServiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ServiceInstance::class;
    }
}
