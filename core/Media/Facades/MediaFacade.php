<?php
namespace Core\Media\Facades;

use Illuminate\Support\Facades\Facade;
use Core\Media\Services\MediaInstance;

class MediaFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return MediaInstance::class;
    }
}
