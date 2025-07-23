<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RequireOtpVerification
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()?->getName();
        
        Log::info('OTP Middleware check', [
            'route' => $routeName,
            'is_authenticated' => Auth::check(),
            'user_id' => Auth::id(),
            'otp_verified' => Session::get('otp_verified', false)
        ]);

        // Skip OTP check for OTP-related routes and logout
        if (in_array($routeName, ['otp.show', 'otp.verify', 'otp.resend', 'logout'])) {
            Log::info('Skipping OTP check for route', ['route' => $routeName]);
            return $next($request);
        }

        // Skip for non-authenticated users (let auth middleware handle it)
        if (!Auth::check()) {
            Log::info('User not authenticated, proceeding');
            return $next($request);
        }

        // Check if OTP has been verified in this session
        if (!Session::get('otp_verified', false)) {
            Log::warning('User accessing protected route without OTP verification', [
                'user_id' => Auth::id(),
                'route' => $routeName,
                'session_otp_verified' => Session::get('otp_verified'),
                'all_session_data' => Session::all()
            ]);
            
            // Log out the user
            Auth::logout();
            
            // Clear session data
            Session::invalidate();
            Session::regenerateToken();
            
            // Redirect to login with message
            return redirect()->route('login')
                ->with('error', 'Please complete the verification process to access your account.');
        }

        Log::info('OTP verification confirmed, allowing access', [
            'user_id' => Auth::id(),
            'route' => $routeName
        ]);

        return $next($request);
    }
}