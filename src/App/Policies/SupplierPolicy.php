<?php

namespace App\Policies;

use Domain\Purchases\Models\Supplier;
use Domain\Users\Models\User;

class SupplierPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function view(User $user, Supplier $supplier): bool
    {

        return $user->hasRole('purchase-officer');
    }
    public function viewAny(User $user): bool
    {

        return $user->hasRole('purchase-officer');
    }
}
