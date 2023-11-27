<?php

namespace Xbigdaddyx\HarmonyFlow\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Xbigdaddyx\HarmonyFlow\Contracts\DepartmentContract;
use Illuminate\Support\Arr;

trait HasDepartments
{
    // public function departments(): BelongsToMany
    // {
    //     return $this->belongsToMany(config('harmony-flow.models.departments'), config('harmony-flow.tables.model_has_department'));
    // }
    public static function bootHasDepartments()
    {
        static::deleting(function ($model) {
            $model->roles()->detach();
        });
    }

    public function departments(): BelongsToMany
    {
        return $this->morphToMany(
            config('harmony-flow.models.departments'),
            'model',
            config('harmony-flow.table_names.model_has_departments'),
            'model_id',
            'department_id'
        );
    }
    public function getDepartmentClass(): string
    {
        if (!$this->departmentClass) {
            $this->departmentClass = app(HarmonyFlow::class)->getDepartmentClass();
        }

        return $this->departmentClass;
    }
    public function scopeDepartment(Builder $query, $departments, $without = false): Builder
    {
        if ($departments instanceof Collection) {
            $departments = $departments->all();
        }

        $departments = array_map(function ($department) {
            if ($department instanceof DepartmentContract) {
                return $department;
            }

            if ($department instanceof \BackedEnum) {
                $department = $department->value;
            }

            $method = is_int($department)  ? 'findById' : ['findByName', 'findByShortName'];

            return config('harmony-flow.models.departments')::{$method}($department);
        }, Arr::wrap($departments));

        $key = (new (config('harmony-flow.models.departments'))())->getKeyName();

        return $query->{!$without ? 'whereHas' : 'whereDoesntHave'}(
            'departments',
            fn (Builder $subQuery) => $subQuery
                ->whereIn(config('permission.table_names.departments') . ".$key", \array_column($departments, $key))
        );
    }
    public function scopeWithoutDepartment(Builder $query, $departments): Builder
    {
        return $this->scopeDepartment($query, $departments, true);
    }
    private function collectDepartments(...$departments): array
    {
        return collect($departments)
            ->flatten()
            ->reduce(function ($array, $department) {
                if (empty($department)) {
                    return $array;
                }

                $department = $this->getStoredDepartment($department);
                if (!$department instanceof DepartmentContract) {
                    return $array;
                }

                $this->ensureModelSharesGuard($department);

                $array[] = $department->getKey();

                return $array;
            }, []);
    }
    public function assignDepartment(...$departments)
    {
        $departments = $this->collectDepartments($departments);

        $model = $this->getModel();


        if ($model->exists) {
            $currentDepartments = $this->departments->map(fn ($department) => $department->getKey())->toArray();

            $this->departments()->attach(array_diff($departments, $currentDepartments));
            $model->unsetRelation('departments');
        } else {
            $class = \get_class($model);

            $class::saved(
                function ($object) use ($departments, $model) {
                    if ($model->getKey() != $object->getKey()) {
                        return;
                    }
                    $model->departments()->attach($departments);
                    $model->unsetRelation('departments');
                }
            );
        }

        // if (is_a($this, HarmonyFlow::class)) {
        //     $this->forgetCachedPermissions();
        // }

        return $this;
    }
    public function removeDepartment($department)
    {
        $this->departments()->detach($this->getStoredDepartment($department));

        $this->unsetRelation('departments');

        // if (is_a($this, Permission::class)) {
        //     $this->forgetCachedPermissions();
        // }

        return $this;
    }
    public function hasDepartment($departments): bool
    {
        $this->loadMissing('departments');

        if (is_string($departments) && strpos($departments, '|') !== false) {
            $departments = $this->convertPipeToArray($departments);
        }

        if ($departments instanceof \BackedEnum) {
            $departments = $departments->value;
        }

        if (is_int($departments)) {
            $key = (new (config('harmony-flow.models.departments'))())->getKeyName();

            return $this->departments->contains($key, $departments);
        }

        if (is_string($departments)) {
            if (!$this->departments->contains('name', $departments)) {
                return $this->departments->contains('short_name', $departments);
            }
            return $this->departments->contains('name', $departments);
        }

        if ($departments instanceof DepartmentContract) {
            return $this->departments->contains($departments->getKeyName(), $departments->getKey());
        }

        if (is_array($departments)) {
            foreach ($departments as $department) {
                if ($this->hasDepartment($department)) {
                    return true;
                }
            }

            return false;
        }

        if ($departments instanceof Collection) {
            return $departments->intersect($this->departments)->isNotEmpty();
        }

        throw new \TypeError('Unsupported type for $departments parameter to hasDepartment().');
    }
    public function hasAnyDepartment(...$departments): bool
    {
        return $this->hasDepartment($departments);
    }
    public function hasAllDepartments($departments): bool
    {
        $this->loadMissing('departments');

        if ($departments instanceof \BackedEnum) {
            $departments = $departments->value;
        }

        if (is_string($departments) && strpos($departments, '|') !== false) {
            $departments = $this->convertPipeToArray($departments);
        }

        if (is_string($departments)) {
            return $this->departments->contains('name', $departments);
        }

        if ($departments instanceof DepartmentContract) {
            return $this->departments->contains($departments->getKeyName(), $departments->getKey());
        }

        $departments = collect()->make($departments)->map(function ($department) {
            if ($department instanceof \BackedEnum) {
                return $department->value;
            }

            return $department instanceof DepartmentContract ? $department->name : $department;
        });

        return $departments->intersect(
            $this->getDepartmentNames()
        ) == $departments;
    }
    public function hasExactDepartments($departments): bool
    {
        $this->loadMissing('departments');

        if (is_string($departments) && strpos($departments, '|') !== false) {
            $departments = $this->convertPipeToArray($departments);
        }

        if (is_string($departments)) {
            $departments = [$departments];
        }

        if ($departments instanceof DepartmentContract) {
            $departments = [$departments->name];
        }

        $departments = collect()->make($departments)->map(
            fn ($department) => $department instanceof DepartmentContract ? $department->name : $department
        );

        return $this->departments->count() == $departments->count() && $this->hasAlldepartments($departments);
    }
    public function getDepartmentNames(): Collection
    {
        $this->loadMissing('departments');

        return $this->departments->pluck('name');
    }
    protected function getStoredDepartment($department): DepartmentContract
    {
        if ($department instanceof \BackedEnum) {
            $department = $department->value;
        }

        if (is_int($department)) {
            return config('harmony-flow.models.departments')::findById($department);
        }

        if (is_string($department)) {
            if (empty(config('harmony-flow.models.departments')::findByName($department))) {
                return config('harmony-flow.models.departments')::findByShortName($department);
            }
            return config('harmony-flow.models.departments')::findByName($department);
        }

        return $department;
    }
}
