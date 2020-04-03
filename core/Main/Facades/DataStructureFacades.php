<?php
namespace Core\Main\Facades;
use Core\Main\Facades\RefreshableFacade;
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
