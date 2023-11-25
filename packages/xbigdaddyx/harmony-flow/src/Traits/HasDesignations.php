<?php

namespace Xbigdaddyx\HarmonyFlow\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Xbigdaddyx\HarmonyFlow\Contracts\DesignationContract;
use Xbigdaddyx\HarmonyFlow\HarmonyFlow;
use Illuminate\Support\Arr;
use Xbigdaddyx\HarmonyFlow\Contracts\ApprovalContract;

trait HasDesignations
{

    private ?string $designationClass = null;
    public static function bootHasDesignations()
    {
        static::deleting(function ($model) {
            $model->designations()->detach();
        });
    }
    public function approvals(): MorphMany
    {
        return $this->morphMany(config('harmony-flow.models.approvals'), 'chargeable');
    }
    public function designations(): BelongsToMany
    {
        return $this->belongsToMany(
            config('harmony-flow.models.designations'),
            config('harmony-flow.table_names.user_has_designation'),
            'chargeable_id',
            'designation_id'
        )->withPivot(['department_id'])->withTimestamps();
    }
    // public function designations(): MorphMany
    // {
    //     return $this->morphMany(config('harmony-flow.models.designations'), 'designationable');
    // }
    public function getDesignationClass(): string
    {
        if (!$this->designationClass) {
            $this->designationClass = app(HarmonyFlow::class)->getDesignationClass();
        }

        return $this->designationClass;
    }
    public function scopeDesignation(Builder $query, $designations, $without = false): Builder
    {
        if ($designations instanceof Collection) {
            $designations = $designations->all();
        }

        $designations = array_map(function ($designation) {
            if ($designation instanceof DesignationContract) {
                return $designation;
            }

            if ($designation instanceof \BackedEnum) {
                $designation = $designation->value;
            }

            $method = is_int($designation)  ? 'findById' : 'findByName';

            return $this->getDesignationClass()::{$method}($designation);
        }, Arr::wrap($designations));

        $key = (new ($this->getDesignationClass())())->getKeyName();

        return $query->{!$without ? 'whereHas' : 'whereDoesntHave'}(
            'designations',
            fn (Builder $subQuery) => $subQuery
                ->whereIn(config('permission.table_names.designations') . ".$key", \array_column($designations, $key))
        );
    }
    public function scopeWithoutDesignation(Builder $query, $designations): Builder
    {
        return $this->scopeDesignation($query, $designations, true);
    }
    private function collectDesignations(...$designations): array
    {
        return collect($designations)
            ->flatten()
            ->reduce(function ($array, $designation) {
                if (empty($designation)) {
                    return $array;
                }

                $designation = $this->getStoredDesignation($designation);
                if (!$designation instanceof DesignationContract) {
                    return $array;
                }

                $this->ensureModelSharesGuard($designation);

                $array[] = $designation->getKey();

                return $array;
            }, []);
    }
    public function assignDesignation(...$designations)
    {
        $designations = $this->collectDesignations($designations);

        $model = $this->getModel();


        if ($model->exists) {
            $currentDesignations = $this->designations->map(fn ($designation) => $designation->getKey())->toArray();

            $this->designations()->attach(array_diff($designations, $currentDesignations));
            $model->unsetRelation('designations');
        } else {
            $class = \get_class($model);

            $class::saved(
                function ($object) use ($designations, $model) {
                    if ($model->getKey() != $object->getKey()) {
                        return;
                    }
                    $model->designations()->attach($designations);
                    $model->unsetRelation('designations');
                }
            );
        }

        // if (is_a($this, HarmonyFlow::class)) {
        //     $this->forgetCachedPermissions();
        // }

        return $this;
    }
    public function removeDesignation($designation)
    {
        $this->designations()->detach($this->getStoredDesignation($designation));

        $this->unsetRelation('designations');

        // if (is_a($this, Permission::class)) {
        //     $this->forgetCachedPermissions();
        // }

        return $this;
    }
    public function hasActionTo($document): bool
    {

        $approval = $this->approvals()->where('approvable_id', $document->id)->where('is_completed', false);

        if ($approval->count() === 1) {

            return $approval->first()->hasActionTo($this);
        }
        return false;
    }
    public function approvalRequest()
    {
        return $this->approvals()->where('is_completed', false);
    }
    public function hasDesignation($designations): bool
    {
        $this->loadMissing('designations');

        if (is_string($designations) && strpos($designations, '|') !== false) {
            $designations = $this->convertPipeToArray($designations);
        }

        if ($designations instanceof \BackedEnum) {
            $designations = $designations->value;
        }

        if (is_int($designations)) {
            $key = (new ($this->getDesignationClass())())->getKeyName();

            return $this->designations->contains($key, $designations);
        }

        if (is_string($designations)) {
            return $this->designations->contains('name', $designations);
        }

        if ($designations instanceof DesignationContract) {
            return $this->designations->contains($designations->getKeyName(), $designations->getKey());
        }

        if (is_array($designations)) {
            foreach ($designations as $designation) {
                if ($this->hasDesignation($designation)) {
                    return true;
                }
            }

            return false;
        }

        if ($designations instanceof Collection) {
            return $designations->intersect($this->designations)->isNotEmpty();
        }

        throw new \TypeError('Unsupported type for $designations parameter to hasDesignation().');
    }
    public function hasAnyDesignation(...$designations): bool
    {
        return $this->hasDesignation($designations);
    }
    public function hasAllDesignations($designations): bool
    {
        $this->loadMissing('designations');

        if ($designations instanceof \BackedEnum) {
            $designations = $designations->value;
        }

        if (is_string($designations) && strpos($designations, '|') !== false) {
            $designations = $this->convertPipeToArray($designations);
        }

        if (is_string($designations)) {
            return $this->designations->contains('name', $designations);
        }

        if ($designations instanceof DesignationContract) {
            return $this->designations->contains($designations->getKeyName(), $designations->getKey());
        }

        $designations = collect()->make($designations)->map(function ($designation) {
            if ($designation instanceof \BackedEnum) {
                return $designation->value;
            }

            return $designation instanceof DesignationContract ? $designation->name : $designation;
        });

        return $designations->intersect(
            $this->getDesignationNames()
        ) == $designations;
    }
    public function hasExactDesignations($designations): bool
    {
        $this->loadMissing('designations');

        if (is_string($designations) && strpos($designations, '|') !== false) {
            $designations = $this->convertPipeToArray($designations);
        }

        if (is_string($designations)) {
            $designations = [$designations];
        }

        if ($designations instanceof DesignationContract) {
            $designations = [$designations->name];
        }

        $designations = collect()->make($designations)->map(
            fn ($designation) => $designation instanceof DesignationContract ? $designation->name : $designation
        );

        return $this->designations->count() == $designations->count() && $this->hasAlldesignations($designations);
    }
    public function getDesignationNames(): Collection
    {
        $this->loadMissing('designations');

        return $this->designations->pluck('name');
    }
    protected function getStoredDesignation($designation): DesignationContract
    {
        if ($designation instanceof \BackedEnum) {
            $designation = $designation->value;
        }

        if (is_int($designation)) {
            return $this->getDesignationClass()::findById($designation);
        }

        if (is_string($designation)) {
            return $this->getDesignationClass()::findByName($designation);
        }

        return $designation;
    }
    // protected function convertPipeToArray(string $pipeString)
    // {
    //     $pipeString = trim($pipeString);

    //     if (strlen($pipeString) <= 2) {
    //         return [str_replace('|', '', $pipeString)];
    //     }

    //     $quoteCharacter = substr($pipeString, 0, 1);
    //     $endCharacter = substr($quoteCharacter, -1, 1);

    //     if ($quoteCharacter !== $endCharacter) {
    //         return explode('|', $pipeString);
    //     }

    //     if (!in_array($quoteCharacter, ["'", '"'])) {
    //         return explode('|', $pipeString);
    //     }

    //     return explode('|', trim($pipeString, $quoteCharacter));
    // }
}
