<?php
namespace Module\Blank\Facades;
use Illuminate\Support\Facades\Facade;
/**
 * @see \Illuminate\Foundation\Application
 */
class BlankFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'blank-facade';
    }
}
