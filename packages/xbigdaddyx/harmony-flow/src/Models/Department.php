<?php

namespace Xbigdaddyx\HarmonyFlow\Models;

use Illuminate\Database\Eloquent\Model;
use Xbigdaddyx\HarmonyFlow\Contracts\DepartmentContract;
use Xbigdaddyx\HarmonyFlow\Exceptions\DepartmentDoesNotExist;

class Department extends Model implements DepartmentContract
{
    public static function findByShortName(string $name): DepartmentContract
    {
        $designation = static::findByParam(['short_name' => $name]);

        if (!$designation) {
            throw DepartmentDoesNotExist::named($name);
        }

        return $designation;
    }
    public static function findByName(string $name): DepartmentContract
    {
        $designation = static::findByParam(['name' => $name, 'short_name' => $name]);

        if (!$designation) {
            throw DepartmentDoesNotExist::named($name);
        }

        return $designation;
    }
    protected static function findByParam(array $params = []): ?DepartmentContract
    {
        $query = static::query();

        foreach ($params as $key => $value) {
            $query->where($key, $value);
        }

        return $query->first();
    }
    public static function findOrCreate(string $name): DepartmentContract
    {


        $designation = static::findByParam(['name' => $name]);

        if (!$designation) {
            return static::query()->create(['name' => $name]);
        }

        return $designation;
    }
    public static function findById(int|string $id): DepartmentContract
    {


        $designation = static::findByParam([(new static())->getKeyName() => $id]);

        if (!$designation) {
            throw DepartmentDoesNotExist::withId($id);
        }

        return $designation;
    }
}
