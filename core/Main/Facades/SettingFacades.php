<?php
namespace Core\Main\Facades;
use Illuminate\Support\Facades\Facade;
/**
 * @see \Illuminate\Foundation\Application
 */
class SettingFacades extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Core\Main\Services\Setting::class;
    }
}
