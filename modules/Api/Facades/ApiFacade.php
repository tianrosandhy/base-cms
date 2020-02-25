<?php
namespace Module\Api\Facades;

use Illuminate\Support\Facades\Facade;
use Module\Api\Services\ApiInstance;

class ApiFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ApiInstance::class;
    }
}
