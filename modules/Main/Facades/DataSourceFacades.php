<?php
namespace Module\Main\Facades;

use Module\Main\Facades\RefreshableFacade;
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
