<?php
namespace Core\Main\Facades;
use Illuminate\Support\Facades\Facade;
/**
 * @see \Illuminate\Foundation\Application
 */
class SlugInstanceFacades extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Core\Main\Services\SlugInstance::class;
    }
}
