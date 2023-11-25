<?php

namespace Xbigdaddyx\HarmonyFlow\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Xbigdaddyx\HarmonyFlow\Exceptions\NextPersonDoesNotExist;
use Xbigdaddyx\HarmonyFlow\HarmonyFlow;
use Xbigdaddyx\HarmonyFlow\Jobs\CreateApprovalHistories;
use ReflectionClass;

trait HasApproval
{
    private ?string $approvalClass = null;
    private ?string $flowClass = null;
    public function comments()
    {
        return $this->morphMany(config('harmony-flow.models.comments'), 'commentable');
    }
    public static function bootHasApproval()
    {
        static::updated(function ($model) {
            // Periksa apakah is_approved atau is_rejected diupdate ok ok
            if ($model->isDirty('is_submited')) {
                CreateApprovalHistories::dispatch($model, (new ReflectionClass($model))->getShortName() . ' document submited', $model->created_by, 'submit');
            }
        });
    }
    public function approvalHistories(): MorphMany
    {
        return $this->morphMany(config('harmony-flow.models.histories'), 'historable');
    }
    public function approvals(): MorphMany
    {
        return $this->morphMany(config('harmony-flow.models.approvals'), 'approvable');
    }
    public function getNextPerson()
    {
        $designation = $this->getNextDesignation(0);
        $department = (int)$this->getApproval()->department_id;
        $users = resolve(config('harmony-flow.models.users'))->whereHas('designations', function (Builder $query) use ($designation, $department) {
            $query->where('name', $designation->name)->where('department_id', $department);
        })->first();
        if (!$users) {
            if (!$this->getNextFlow()->is_skipable) {
                if ($this->getNextFlow($this->getNextFlow()->order + 1) === 0) {
                    throw NextPersonDoesNotExist::named($designation->name, $department);
                }
            }
            if ($this->getNextFlow()->is_last) {
                return 'Completed';
            }
            $designation = $this->getNextDesignation((int)$this->getNextFlow()->order + 1);
            $users = resolve(config('harmony-flow.models.users'))->whereHas('designations', function (Builder $query) use ($designation, $department) {
                $query->where('name', $designation->name)->where('department_id', $department);
            })->first();
            return $users;
        }
        return $users;
    }
    public function getNextDesignation(int $order = null)
    {
        return $this->getNextFlow($order > 0 ? null : $order)->designation;
    }
    public function getNextFlow(int $order = null)
    {
        $now = $this->getFlow();
        if ($now) {
            return resolve($this->getFlowClass())->where('type', $now->type)->where('order', $order > 0 ? $order : $now->order + 1)->first();
        }
        return resolve($this->getFlowClass())->where('type', $this->type)->where('order', $order > 0 ? $order : 1)->first();
    }
    public function getCharge()
    {
        return resolve(config('harmony-flow.models.users'))->find($this->getApproval()->chargeable_id);
    }
    public function getFlow()
    {
        if ($this->getApproval()) {
            return $this->getApproval()->flow;
        }
        return null;
    }
    public function getRequestedApproval()
    {
        return $this->approvals()->where('is_completed', false)->first();
    }
    public function getApproval()
    {
        return $this->approvals()->where('is_completed', true)->first();
    }
    public function getApprovalClass(): string
    {
        if (!$this->approvalClass) {
            $this->approvalClass = app(HarmonyFlow::class)->getapprovalClass();
        }

        return $this->approvalClass;
    }
    public function getFlowClass(): string
    {
        if (!$this->flowClass) {
            $this->flowClass = app(HarmonyFlow::class)->getFlowClass();
        }

        return $this->flowClass;
    }
    abstract public function getDirty();
    abstract public function getKeyName();
    abstract public function getKey();
}
