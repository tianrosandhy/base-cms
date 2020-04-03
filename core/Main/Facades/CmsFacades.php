<?php
namespace Core\Main\Facades;
use Illuminate\Support\Facades\Facade;
/**
 * @see \Illuminate\Foundation\Application
 */
class CmsFacades extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'cms-facade';
    }
}
