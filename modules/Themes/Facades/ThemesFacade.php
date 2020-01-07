<?php
namespace Module\Themes\Facades;
use Illuminate\Support\Facades\Facade;
/**
 * @see \Illuminate\Foundation\Application
 */
class ThemesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'themes-facade';
    }
}
