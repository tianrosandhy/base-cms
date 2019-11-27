<?php
namespace Module\Site\Facades;
use Illuminate\Support\Facades\Facade;
/**
 * @see \Illuminate\Foundation\Application
 */
class SiteFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'site-facade';
    }
}
