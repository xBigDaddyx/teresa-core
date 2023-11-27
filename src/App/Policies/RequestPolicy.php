<?php

namespace App\Policies;

use Domain\Purchases\Models\Request;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Builder;

class RequestPolicy
{
    public bool $hasPermission;
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function view(User $user, Request $request): bool
    {
        return true;
        // $purchaseDepartment = $user->purchaseDepartments;
        // if ($purchaseDepartment->contains('department-user') || ) {
        //     $hasPermission = true;
        // } else {
        //     $hasPermission = false;
        // }
        // return $user->hasRole('purchase-user') && $hasPermission;
    }
    public function viewAny(User $user): bool
    {
        return true;
        // $approvalUser = $user->approvalUser;
        // if ($approvalUser->contains('level', 'User')) {
        //     $hasPermission = true;
        // } else {
        //     $hasPermission = false;
        // }
        // return $user->hasRole('purchase-user') && $hasPermission;
    }
}
