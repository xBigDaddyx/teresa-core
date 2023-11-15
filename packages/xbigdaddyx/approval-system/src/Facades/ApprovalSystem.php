<?php

namespace Xbigdaddyx\ApprovalSystem\Facades;

use Illuminate\Support\Facades\Facade;

class ApprovalSystem extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'approval-system';
    }
}
