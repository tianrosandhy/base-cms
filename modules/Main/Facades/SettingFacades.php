<?php
namespace Module\Main\Facades;
use Illuminate\Support\Facades\Facade;
/**
 * @see \Illuminate\Foundation\Application
 */
class SettingFacades extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Module\Main\Services\Setting::class;
    }
}
