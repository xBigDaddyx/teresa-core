<?php

namespace Xbigdaddyx\HarmonyFlow\Exceptions;

use InvalidArgumentException;

class DepartmentDoesNotExist extends InvalidArgumentException
{
    public static function named(string $departmentName)
    {
        return new static("There is no department named `{$departmentName}`.");
    }

    /**
     * @param  int|string  $designationId
     * @return static
     */
    public static function withId($departmentId)
    {
        return new static("There is no department with ID `{$departmentId}`.");
    }
}
