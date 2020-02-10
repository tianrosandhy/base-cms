<?php
namespace Module\Product\Facades;

use Illuminate\Support\Facades\Facade;
use Module\Product\Services\ProductInstance;

class ProductFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ProductInstance::class;
    }
}
