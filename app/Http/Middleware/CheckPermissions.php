<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user has required permissions
        if ($this->hasRequiredPermissions($user, $permissions)) {
            return $next($request);
        }

        // Store attempted URL and redirect to no-permissions page
        session(['attempted_url' => $request->url()]);
        session(['required_permissions' => $permissions]);
        
        return redirect()->route('no-permissions', [
            'url' => $request->url(),
            'permission' => implode(',', $permissions)
        ]);
    }

    /**
     * Check if user has required permissions
     */
    private function hasRequiredPermissions($user, $permissions)
    {
        // If no specific permissions required, check basic role access
        if (empty($permissions)) {
            return true;
        }

        // Check role-based permissions
        foreach ($permissions as $permission) {
            if ($this->checkRolePermission($user, $permission)) {
                return true; // User has at least one required permission
            }
        }

        return false;
    }

    /**
     * Check role-based permissions
     */
    private function checkRolePermission($user, $permission)
    {
        $userRole = $user->role ?? 'user';

        // Define role permissions
        $rolePermissions = [
            'admin' => [
                'admin.dashboard',
                'admin.users',
                'admin.lenders',
                'admin.applications',
                'admin.reports',
                'admin.settings',
                'admin.*'
            ],
            'lender' => [
                'lender.dashboard',
                'lender.applications',
                'lender.profile',
                'lender.reports'
            ],
            'user' => [
                'user.dashboard',
                'user.applications',
                'user.profile'
            ]
        ];

        // Check if user role has the required permission
        $allowedPermissions = $rolePermissions[$userRole] ?? [];
        
        // Check exact match or wildcard match
        return in_array($permission, $allowedPermissions) || 
               in_array($userRole . '.*', $allowedPermissions);
    }
}

// Alternative middleware for specific roles
class RequireRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $userRole = $user->role ?? 'user';

        if (!in_array($userRole, $roles)) {
            return redirect()->route('no-permissions', [
                'url' => $request->url(),
                'permission' => 'role:' . implode(',', $roles)
            ]);
        }

        return $next($request);
    }
}

// Middleware for admin only access
class RequireAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        if ($user->role !== 'admin') {
            return redirect()->route('no-permissions', [
                'url' => $request->url(),
                'permission' => 'admin_access'
            ]);
    }

        return $next($request);
    }
}