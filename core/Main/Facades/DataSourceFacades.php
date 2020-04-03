<?php
namespace Core\Main\Facades;

use Core\Main\Facades\RefreshableFacade;
/**
 * @see \Illuminate\Foundation\Application
 */
class DataSourceFacades extends RefreshableFacade
{
    protected static function getFacadeAccessor()
    {
        return 'datasource-facade';
    }

}
