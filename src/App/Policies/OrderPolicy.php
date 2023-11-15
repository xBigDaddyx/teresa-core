<?php

namespace App\Policies;

use Domain\Purchases\Models\Order;
use Domain\Users\Models\User;

class OrderPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function view(User $user, Order $order): bool
    {

        return $user->hasRole('purchase-officer');
    }
    public function viewAny(User $user): bool
    {

        return $user->hasRole('purchase-officer');
    }
}
