<?php

namespace Xbigdaddyx\HarmonyFlow\Facades;

use Illuminate\Support\Facades\Facade;

class HarmonyFlow extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'harmony-flow';
    }
}
