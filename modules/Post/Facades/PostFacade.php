<?php
namespace Module\Post\Facades;
use Illuminate\Support\Facades\Facade;
/**
 * @see \Illuminate\Foundation\Application
 */
class PostFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'post-facade';
    }
}
