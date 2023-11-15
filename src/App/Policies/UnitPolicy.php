<?php

namespace App\Policies;

use Domain\Purchases\Models\Unit;
use Domain\Users\Models\User;

class UnitPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function view(User $user, Unit $unit): bool
    {

        return $user->hasRole('purchase-officer');
    }
    public function viewAny(User $user): bool
    {

        return $user->hasRole('purchase-officer');
    }
}
