<?php
namespace Core\Language\Facades;

use Illuminate\Support\Facades\Facade;
use Core\Language\Services\LanguageInstance;

class LanguageFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return LanguageInstance::class;
    }
}
