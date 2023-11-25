<?php

namespace Xbigdaddyx\HarmonyFlow\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Xbigdaddyx\HarmonyFlow\Contracts\ApprovalContract;
use Xbigdaddyx\HarmonyFlow\Contracts\DesignationContract;
use Xbigdaddyx\HarmonyFlow\Exceptions\DesignationDoesNotExist;
use Xbigdaddyx\HarmonyFlow\HarmonyFlow;

class Designation extends Model implements DesignationContract
{
    protected $guarded = [];
    public function __construct()
    {
        $this->table = config('harmony-flow.table_names.designations') ?: parent::getTable();
    }


    public function department(): BelongsTo
    {
        return $this->belongsTo(config('harmony-flow.models.departments'), 'department_id', 'id');
    }
    public static function findByName(string $name): DesignationContract
    {
        $designation = static::findByParam(['name' => $name]);

        if (!$designation) {
            throw DesignationDoesNotExist::named($name);
        }

        return $designation;
    }
    protected static function findByParam(array $params = []): ?DesignationContract
    {
        $query = static::query();

        foreach ($params as $key => $value) {
            $query->where($key, $value);
        }

        return $query->first();
    }
    public static function findOrCreate(string $name): DesignationContract
    {


        $designation = static::findByParam(['name' => $name]);

        if (!$designation) {
            return static::query()->create(['name' => $name]);
        }

        return $designation;
    }
    public static function findById(int|string $id): DesignationContract
    {


        $designation = static::findByParam([(new static())->getKeyName() => $id]);

        if (!$designation) {
            throw DesignationDoesNotExist::withId($id);
        }

        return $designation;
    }
    public function company(): BelongsTo
    {
        return $this->belongsTo(config('harmony-flow.models.companies'));
    }
}
