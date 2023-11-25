<?php

namespace Xbigdaddyx\HarmonyFlow\Exceptions;

use InvalidArgumentException;

class DesignationDoesNotExist extends InvalidArgumentException
{
    public static function named(string $designationName)
    {
        return new static("There is no designation named `{$designationName}`.");
    }

    /**
     * @param  int|string  $designationId
     * @return static
     */
    public static function withId($designationId)
    {
        return new static("There is no designation with ID `{$designationId}`.");
    }
}
