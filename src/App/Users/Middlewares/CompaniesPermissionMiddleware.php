<?php

namespace App\Users\Middlewares;

use Closure;
use Illuminate\Http\Request;

class CompaniesPermissionMiddleware
{
    public function handle($request, Closure $next)
    {
        if (! empty(auth()->user())) {
            // session value set on login
            setPermissionsTeamId(session('company_id'));
        }
        // other custom ways to get company_id
        /*if(!empty(auth('api')->user())){
            // `getTeamIdFromToken()` example of custom method for getting the set company_id
            setPermissionsTeamId(auth('api')->user()->getTeamIdFromToken());
        }*/

        return $next($request);
    }
    // public function handle(Request $request, Closure $next)
    // {
    //     return $next($request);
    // }
}
