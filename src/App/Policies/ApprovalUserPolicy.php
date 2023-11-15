<?php

namespace App\Policies;

use Domain\Purchases\Models\ApprovalUser;
use Domain\Users\Models\User;

class ApprovalUserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function view(User $user, ApprovalUser $category): bool
    {

        return $user->hasRole('super-admin');
    }
    public function viewAny(User $user): bool
    {

        return $user->hasRole('super-admin');
    }
}
