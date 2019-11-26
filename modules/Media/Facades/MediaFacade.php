<?php
namespace Module\Media\Facades;
use Illuminate\Support\Facades\Facade;
/**
 * @see \Illuminate\Foundation\Application
 */
class MediaFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'media-facade';
    }
}
