<?php

namespace App\Policies;

use Domain\Purchases\Models\Product;
use Domain\Users\Models\User;

class ProductPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function view(User $user, Product $product): bool
    {

        return $user->hasRole('purchase-officer');
    }
    public function viewAny(User $user): bool
    {

        return $user->hasRole('purchase-officer');
    }
}
