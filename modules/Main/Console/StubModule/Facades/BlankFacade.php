<?php
namespace Module\Blank\Facades;

use Illuminate\Support\Facades\Facade;
use Module\Blank\Services\BlankInstance;

class BlankFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return BlankInstance::class;
    }
}
