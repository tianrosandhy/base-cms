<?php
namespace Module\Contact\Facades;

use Illuminate\Support\Facades\Facade;
use Module\Contact\Services\ContactInstance;

class ContactFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ContactInstance::class;
    }
}
