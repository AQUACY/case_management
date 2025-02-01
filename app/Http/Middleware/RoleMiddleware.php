<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get the authenticated user's roles
        $userRoles = Auth::user()->roles->pluck('id')->toArray();

        // Check if the role matches
        if (($role === 'Administrator' && !in_array(2, $userRoles))) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
