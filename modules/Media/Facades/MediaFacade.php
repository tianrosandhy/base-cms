<?php
namespace Module\Media\Facades;

use Illuminate\Support\Facades\Facade;
use Module\Media\Services\MediaInstance;

class MediaFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return MediaInstance::class;
    }
}
