<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateLdap extends \Filament\Http\Middleware\Authenticate
{
    /**
     * @param  array<string>  $guards
     */
    protected function authenticate($request, array $guards): void
    {

        Filament::getCurrentPanel()->authGuard(session('guard', 'ldap'));
        $guard = Filament::auth();

        if (!$guard->check()) {
            $this->unauthenticated($request, $guards);
            return;
        }

        $this->auth->shouldUse(session('guard', 'ldap'));

        /** @var Model $user */
        $user = $guard->user();

        $panel = Filament::getCurrentPanel();

        abort_if(
            $user instanceof FilamentUser ?
                (!$user->canAccessPanel($panel)) : (config('app.env') !== 'local'),
            403,
        );
    }
}
