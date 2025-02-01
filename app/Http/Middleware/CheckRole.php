<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Role;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = auth()->user();

        // Check if user has role_id
        if (!$user->role_id) {
            return response()->json(['error' => 'No role assigned'], 403);
        }

        // Get user's role from the roles table
        $userRole = Role::find($user->role_id);

        if (!$userRole || $userRole->name !== $role) {
            return response()->json([
                'error' => 'Forbidden - Insufficient permissions',
                'required_role' => $role,
                'user_role' => $userRole ? $userRole->name : 'none'
            ], 403);
        }

        return $next($request);
    }
}
