<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RequireCompanyVerification
{
    /**
     * Handle an incoming request.
     *
     * This middleware ensures that company users who are not verified
     * are redirected to the company KYC page and cannot access other pages.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for non-authenticated users (let auth middleware handle it)
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        $routeName = $request->route()?->getName();
        $path = $request->path();

        // Always allow Livewire internal routes (file uploads, updates, etc.)
        if (str_starts_with($path, 'livewire/')) {
            return $next($request);
        }

        // Define routes that should be accessible even without company verification
        $allowedRoutes = [
            'company.kyc',
            'logout',
            'verification.options',
            'verification.phone-photo',
            'verification.phone-photo.link',
            'verification.qr-code',
            'verification.questionnaire',
            'mobile.verification',
            // Livewire routes
            'livewire.update',
            'livewire.upload-file',
            'livewire.preview-file',
        ];

        // Allow access to KYC and verification routes
        if (in_array($routeName, $allowedRoutes)) {
            return $next($request);
        }

        // Check if user is a company and not verified
        if ($user->registration_type === 'company' && !$user->isCompanyVerified()) {
            // If verification is rejected, show message
            if ($user->isCompanyVerificationRejected()) {
                return redirect()->route('company.kyc')
                    ->with('error', 'Your company verification was rejected. Please contact support for more information.');
            }

            // If pending, redirect to KYC page
            if ($user->isCompanyVerificationPending()) {
                return redirect()->route('company.kyc')
                    ->with('info', 'Please complete company verification to access this page.');
            }
        }

        return $next($request);
    }
}
