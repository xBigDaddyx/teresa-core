<?php

namespace App\Policies;

use Domain\Purchases\Models\ApprovalRequest;
use Domain\Users\Models\User;

class ApprovalRequestPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function view(User $user, ApprovalRequest $approvalRequest): bool
    {
        if ((int)$approvalRequest->user_id === (int)$user->id) {
            $hasPermission = true;
        } else {
            $hasPermission = false;
        }
        return $user->hasRole('purchase-user') && $hasPermission  || $user->hasRole('purchase-officer') && $hasPermission;
    }
    public function viewAny(User $user): bool
    {

        return $user->hasRole('purchase-officer') || $user->hasRole('purchase-approver');
    }
}
