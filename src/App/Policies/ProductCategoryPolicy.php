<?php

namespace App\Policies;

use Domain\Purchases\Models\ProductCategory;
use Domain\Users\Models\User;

class ProductCategoryPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function view(User $user, ProductCategory $productCategory): bool
    {

        return $user->hasRole('purchase-officer');
    }
    public function viewAny(User $user): bool
    {

        return $user->hasRole('purchase-officer');
    }
}
