<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RequireNidaVerification
{
    /**
     * Handle an incoming request.
     *
     * This middleware ensures that users who are not NIDA verified
     * are redirected to the verification options page.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for non-authenticated users (let auth middleware handle it)
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        $routeName = $request->route()?->getName();

        // Define routes that should be accessible even without NIDA verification
        $allowedRoutes = [
            'verification.options',
            'verification.phone-photo',
            'verification.phone-photo.link',
            'verification.qr-code',
            'verification.questionnaire',
            'logout',
            'mobile.verification',
            'company.kyc', // Allow company KYC page
        ];

        // Allow access to verification routes
        if (in_array($routeName, $allowedRoutes)) {
            return $next($request);
        }

        // For company users, allow them to complete NIDA verification as part of company KYC
        // Don't block them from accessing verification routes
        if ($user->registration_type === 'company') {
            // Company users can access verification routes during KYC process
            // They will be blocked from other routes by RequireCompanyVerification middleware
            return $next($request);
        }

        // Check if user is NIDA verified (only for individual users)
        if (!$user->isNidaVerified()) {
            Log::info('User not NIDA verified, redirecting to verification options', [
                'user_id' => $user->id,
                'route' => $routeName,
                'nida_verified_at' => $user->nida_verified_at,
                'verification_status' => $user->verification_status,
                'registration_type' => $user->registration_type,
            ]);

            // Redirect to verification options page
            return redirect()->route('verification.options')
                ->with('info', 'Please complete NIDA verification to access this page.');
        }

        Log::info('User is NIDA verified, allowing access', [
            'user_id' => $user->id,
            'route' => $routeName,
        ]);

        return $next($request);
    }
}

