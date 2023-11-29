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
    public function create(User $user)
    {
        return $user->hasRole(['purchase-user', 'super-admin']);
    }
    public function update(User $user, Request $request): bool
    {
        return $request->created_by === $user->id && !$request->isSubmitted();
    }
    public function view(User $user, Request $request): bool
    {
        return $user->hasRole(['purchase-user', 'purchase-officer', 'purchase-approver']);
    }
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['purchase-user', 'purchase-officer', 'purchase-approver']);
    }
    public function submit(User $user, Request $request)
    {
        return $request->created_by === $user->id;
    }
}
