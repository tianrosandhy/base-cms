<?php
namespace Module\Page\Facades;
use Illuminate\Support\Facades\Facade;
/**
 * @see \Illuminate\Foundation\Application
 */
class PageFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'page-facade';
    }
}
