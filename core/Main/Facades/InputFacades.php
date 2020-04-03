<?php
namespace Core\Main\Facades;
use Illuminate\Support\Facades\Facade;
/**
 * @see \Illuminate\Foundation\Application
 */
class InputFacades extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Core\Main\Services\Input::class;
    }
}
