<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class HandleOtpRedirect
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if we need to redirect to OTP after login
        if (Session::has('otp_redirect_needed')) {
            Session::forget('otp_redirect_needed');
            
            // Only redirect if we have a user waiting for OTP verification
            if (Session::has('otp_user_id')) {
                return redirect()->route('otp.show');
            }
        }

        return $next($request);
    }
}