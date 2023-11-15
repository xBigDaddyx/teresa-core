<?php

namespace Teresa\CartonBoxGuard\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Teresa\PolybagGuard\PolybagGuard
 */
class PolybagFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        //return \Teresa\PolybagGuard\PolybagGuard::class;
        return 'PolybagRepository';
    }
}
