<?php

namespace App\Policies;

use Domain\Purchases\Models\City;
use Domain\Users\Models\User;

class CityPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function view(User $user, City $city): bool
    {

        return $user->hasRole('purchase-officer');
    }
    public function viewAny(User $user): bool
    {

        return $user->hasRole('purchase-officer');
    }
}
