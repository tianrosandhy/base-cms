<?php
namespace Module\Language\Facades;

use Illuminate\Support\Facades\Facade;
use Module\Language\Services\LanguageInstance;

class LanguageFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return LanguageInstance::class;
    }
}
