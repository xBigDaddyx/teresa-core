<?php

namespace Xbigdaddyx\HarmonyFlow\Traits;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use RingleSoft\LaravelProcessApproval\Models\ProcessApproval;
use RingleSoft\LaravelProcessApproval\Traits\Approvable as TraitsApprovable;

trait Approvable
{
    use TraitsApprovable;
    use HasComment;


    public function createdBy()
    {
        $user = resolve(config('harmony-flow.models.users'));
        return $user->find($this->approvalStatus->creator_id);
    }

    /**
     * Check if Approval process is completed
     * @return bool
     */
    public function isApprovalCompleted(): bool
    {
        foreach (collect($this->approvalStatus->steps ?? []) as $index => $item) {
            if ($item['process_approval_action'] === null || $item['process_approval_id'] === null) {
                return false;
            }
        }
        return true;
    }

    public function getNextApprovers(): Collection
    {
        $nextStep = $this->nextApprovalStep();
        return (config('harmony-flow.models.users'))::role($nextStep?->role)->whereHas('purchaseDepartments', function (Builder $query) {
            $query->where('department_id', $this->department_id);
        })->get();
    }
    public function canBeApprovedBy(Authenticatable|null $user): bool|null
    {
        $nextStep = $this->nextApprovalStep();
        $hasDept = $user->hasPurchaseDepartment($this->department_id);
        if ($hasDept) {
            return !$this->approvalsPaused && $this->isSubmitted() && $nextStep && $user?->hasRole($nextStep->role);
        }
        return false;
    }

    public function onApprovalCompleted(ProcessApproval $approval): bool
    {
        // Write logic to be executed when the approval process is completed
        return true;
    }
}
