<?php

namespace App\Policies;

use Domain\Purchases\Models\ApprovalFlow;
use Domain\Users\Models\User;
use Xbigdaddyx\HarmonyFlow\Models\Flow;

class ApprovalFlowPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function view(User $user, Flow $category): bool
    {

        return $user->hasRole('super-admin');
    }
    public function viewAny(User $user): bool
    {

        return $user->hasRole('super-admin');
    }
}
