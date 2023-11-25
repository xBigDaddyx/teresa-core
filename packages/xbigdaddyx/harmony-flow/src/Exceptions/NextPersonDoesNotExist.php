<?php

namespace Xbigdaddyx\HarmonyFlow\Exceptions;

use InvalidArgumentException;

class NextPersonDoesNotExist extends InvalidArgumentException
{
    public static function named(string $designationName, $departmentID)
    {
        $department = resolve(config('harmony-flow.models.departments'))->find($departmentID);
        return new static("There is no next user for approval designation named `{$designationName}` department `{$department->name}`.");
    }

    /**
     * @param  int|string  $userId
     * @return static
     */
    public static function withId($designationId, $departmentID)
    {
        $department = resolve(config('harmony-flow.models.departments'))->find($departmentID);
        return new static("There is no next user for approval designation ID `{$designationId}` department `{$department->name}`.");
    }
}
