<?php
namespace Module\Post\Facades;

use Illuminate\Support\Facades\Facade;
use Module\Post\Services\PostInstance;

class PostFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return PostInstance::class;
    }
}
