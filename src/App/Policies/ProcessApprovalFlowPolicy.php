<?php

namespace App\Policies;


use Domain\Users\Models\User;
use RingleSoft\LaravelProcessApproval\Models\ProcessApprovalFlow;

class ProcessApprovalFlowPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function view(User $user, ProcessApprovalFlow $flow): bool
    {

        return $user->hasRole('super-admin');
    }
    public function viewAny(User $user): bool
    {

        return $user->hasRole('super-admin');
    }
}
