<?php
namespace Module\Main\Facades;
use Illuminate\Support\Facades\Facade;
/**
 * @see \Illuminate\Foundation\Application
 */
class ImageFacades extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'image-facade';
    }
}
