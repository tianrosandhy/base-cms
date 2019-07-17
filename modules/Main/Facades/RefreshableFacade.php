<?php
namespace Module\Main\Facades;
use Illuminate\Support\Facades\Facade;

class RefreshableFacade extends Facade
{
    // Editted from : Illuminate\Support\Facades\Facade
    //always refresh the instance in every call
	public static function __callStatic($method, $args)
    {
	    static::clearResolvedInstance(static::getFacadeAccessor());
        $instance = static::getFacadeRoot();

        if (! $instance) {
            throw new RuntimeException('A facade root has not been set.');
        }

        return $instance->$method(...$args);
    }
}
