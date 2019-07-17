<?php
namespace Module\Main\Facades;
use Module\Main\Facades\RefreshableFacade;
/**
 * @see \Illuminate\Foundation\Application
 */
class DataStructureFacades extends RefreshableFacade
{
    protected static function getFacadeAccessor()
    {
        return 'datastructure-facade';
    }

}
