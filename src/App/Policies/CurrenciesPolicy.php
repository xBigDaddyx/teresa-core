<?php

namespace App\Policies;

use Domain\Purchases\Models\Currency;
use Domain\Users\Models\User;

class CurrenciesPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function view(User $user, Currency $currency): bool
    {

        return $user->hasRole('purchase-officer');
    }
    public function viewAny(User $user): bool
    {

        return $user->hasRole('purchase-officer');
    }
}
