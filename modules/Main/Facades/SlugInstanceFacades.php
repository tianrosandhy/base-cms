<?php
namespace Module\Main\Facades;
use Illuminate\Support\Facades\Facade;
/**
 * @see \Illuminate\Foundation\Application
 */
class SlugInstanceFacades extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Module\Main\Services\SlugInstance::class;
    }
}
