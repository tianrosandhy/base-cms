<?php
namespace Module\Page\Facades;

use Illuminate\Support\Facades\Facade;
use Module\Page\Services\PageInstance;

class PageFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return PageInstance::class;
    }
}
