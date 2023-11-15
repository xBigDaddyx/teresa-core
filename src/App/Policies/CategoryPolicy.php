<?php

namespace App\Policies;

use Domain\Purchases\Models\Category;
use Domain\Users\Models\User;

class CategoryPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function view(User $user, Category $category): bool
    {

        return $user->hasRole('purchase-officer');
    }
    public function viewAny(User $user): bool
    {

        return $user->hasRole('purchase-officer');
    }
}
