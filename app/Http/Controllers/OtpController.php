<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\OtpService;
use App\Services\SmsOtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class OtpController extends Controller
{
    protected OtpService $otpService;
    protected SmsOtpService $smsOtpService;

    public function __construct(OtpService $otpService, SmsOtpService $smsOtpService)
    {
        $this->otpService = $otpService;
        $this->smsOtpService = $smsOtpService;
    }

    /**
     * Show OTP verification form
     */
    public function show()
    {
        // Check if user is in the middle of login process
        if (!Session::has('otp_user_id')) {
            return redirect()->route('login')
                ->with('error', 'Please login first to access the verification page.');
        }

        $userId = Session::get('otp_user_id');
        $user = User::find($userId);

        if (!$user) {
            Session::forget(['otp_user_id', 'login_timestamp']);
            return redirect()->route('login')
                ->with('error', 'Invalid session. Please login again.');
        }

        // Check session timeout (30 minutes)
        $loginTimestamp = Session::get('login_timestamp');
        if (!$loginTimestamp || (now()->timestamp - $loginTimestamp) > 1800) {
            Session::forget(['otp_user_id', 'login_timestamp']);
            return redirect()->route('login')
                ->with('error', 'Session expired. Please login again.');
        }

        // Get remaining time for current OTP
        $remainingTime = $this->otpService->getOtpRemainingTime($user);
        
        Log::info('Showing OTP page', [
            'user_id' => $userId,
            'remaining_time' => $remainingTime
        ]);
        
        return view('auth.otp', [
            'user' => $user,
            'remainingTime' => $remainingTime ?? 0,
            'canResend' => $this->otpService->canResendOtp($user)
        ]);
    }

    /**
     * Verify OTP
     */
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6|regex:/^[0-9]{6}$/',
        ], [
            'otp.required' => 'Please enter the verification code.',
            'otp.size' => 'Verification code must be 6 digits.',
            'otp.regex' => 'Verification code must contain only numbers.',
        ]);

        if (!Session::has('otp_user_id')) {
            return redirect()->route('login')
                ->with('error', 'Session expired. Please login again.');
        }

        $userId = Session::get('otp_user_id');
        $user = User::find($userId);

        if (!$user) {
            Session::forget(['otp_user_id', 'login_timestamp']);
            return redirect()->route('login')
                ->with('error', 'Invalid session. Please login again.');
        }

        Log::info('Verifying OTP', [
            'user_id' => $userId,
            'session_before_verification' => [
                'otp_user_id' => Session::get('otp_user_id'),
                'otp_verified' => Session::get('otp_verified', 'not_set')
            ]
        ]);

        // Verify OTP
        if ($this->otpService->verifyOtp($user, $request->otp)) {
            Log::info('OTP verification successful, logging in user', ['user_id' => $userId]);
            
            // Clear OTP session data first
            Session::forget(['otp_user_id', 'login_timestamp']);
            
            // Log the user in
            Auth::login($user, true);
            
            // IMPORTANT: Set OTP verification flag AFTER login
            Session::put('otp_verified', true);
            
            // Regenerate session for security but keep the otp_verified flag
            $otpVerified = Session::get('otp_verified');
            $request->session()->regenerate();
            Session::put('otp_verified', $otpVerified);
            
            Log::info('User login completed after OTP verification', [
                'user_id' => $user->id,
                'is_authenticated' => Auth::check(),
                'current_user_id' => Auth::id(),
                'otp_verified_flag' => Session::get('otp_verified'),
                'session_id' => Session::getId(),
                'nida_verified' => $user->isNidaVerified(),
                'nida_verified_at' => $user->nida_verified_at,
            ]);
            
            // Check if user is admin or lender - skip NIDA verification
            $isAdmin = $user->isAdmin() || $user->hasRole('admin') || $user->hasRole('super_admin');
            $isLender = $user->isLender() || $user->hasRole('lender');
            
            // Admin and lender don't need NIDA verification
            if ($isAdmin || $isLender) {
                Log::info('Admin/Lender user - skipping NIDA verification', [
                    'user_id' => $user->id,
                    'is_admin' => $isAdmin,
                    'is_lender' => $isLender,
                ]);
                
                return redirect()->intended(route('dashboard'))
                    ->with('success', 'Login successful! Welcome back.');
            }
            
            // For company users from non-Tanzania countries, skip NIDA verification
            // They will be handled by RequireCompanyVerification middleware
            if ($user->registration_type === 'company' && !$user->isFromTanzania()) {
                Log::info('Non-Tanzania company user - skipping NIDA verification', [
                    'user_id' => $user->id,
                    'country' => $user->country,
                    'registration_type' => $user->registration_type,
                ]);
                
                // Redirect to intended page (will be intercepted by RequireCompanyVerification middleware if needed)
                return redirect()->intended(route('dashboard'))
                    ->with('success', 'Login successful! Welcome back.');
            }
            
            // Check if user is NIDA verified
            // If not verified, redirect to verification options page
            if (!$user->isNidaVerified()) {
                Log::info('User is not NIDA verified, redirecting to verification options', [
                    'user_id' => $user->id,
                ]);
                
                return redirect()->route('verification.options')
                    ->with('info', 'Please complete NIDA verification to access your dashboard.');
            }
            
            // User is verified, redirect to intended page (dashboard)
            return redirect()->intended(route('dashboard'))
                ->with('success', 'Login successful! Welcome back.');
        } else {
            Log::warning('Invalid OTP verification attempt', [
                'user_id' => $user->id,
            ]);
            
            throw ValidationException::withMessages([
                'otp' => 'The verification code is invalid or has expired. Please try again.',
            ]);
        }
    }

    /**
     * Resend OTP
     */
    public function resend(Request $request)
    {
        if (!Session::has('otp_user_id')) {
            return redirect()->route('login')
                ->with('error', 'Session expired. Please login again.');
        }

        $userId = Session::get('otp_user_id');
        $user = User::find($userId);

        if (!$user) {
            Session::forget(['otp_user_id', 'login_timestamp']);
            return redirect()->route('login')
                ->with('error', 'Invalid session. Please login again.');
        }

        // Check if we can resend (rate limiting)
        if (!$this->otpService->canResendOtp($user)) {
            return back()->with('error', 'Please wait before requesting a new verification code.');
        }

        Log::info('Resending OTP', ['user_id' => $user->id]);

        if ($this->otpService->generateAndSendOtp($user)) {
            Log::info('OTP resent successfully', ['user_id' => $user->id]);
            return back()->with('success', 'A new verification code has been sent to your email.');
        } else {
            Log::error('Failed to resend OTP', ['user_id' => $user->id]);
            return back()->with('error', 'Failed to send verification code. Please try again later.');
        }
    }


    /**
     * Verify phone number via OTP
     */
    public function verifySmsOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6|regex:/^[0-9]{6}$/',
        ], [
            'otp.required' => 'Please enter the verification code.',
            'otp.size' => 'Verification code must be 6 digits.',
            'otp.regex' => 'Verification code must contain only numbers.',
        ]);

        if (!Session::has('otp_user_id')) {
            return redirect()->route('login')
                ->with('error', 'Session expired. Please login again.');
        }

        $userId = Session::get('otp_user_id');
        $user = User::find($userId);

        if (!$user) {
            Session::forget(['otp_user_id', 'login_timestamp']);
            return redirect()->route('login')
                ->with('error', 'Invalid session. Please login again.');
        }

        Log::info('Verifying SMS OTP', [
            'user_id' => $userId,
            'session_before_verification' => [
                'otp_user_id' => Session::get('otp_user_id'),
                'otp_verified' => Session::get('otp_verified', 'not_set')
            ]
        ]);

        // Verify OTP
        if ($this->smsOtpService->verifyOtp($user, $request->otp)) {
            Log::info('SMS OTP verification successful, logging in user', ['user_id' => $userId]);
            
            // Clear OTP session data first
            Session::forget(['otp_user_id', 'login_timestamp']);
            
            // Log the user in
            Auth::login($user, true);
            
            // // IMPORTANT: Set OTP verification flag AFTER login
            // Session::put('otp_verified', false);
            
            // Regenerate session for security but keep the otp_verified flag
            $otpVerified = Session::get('otp_verified');
            $request->session()->regenerate();
            // Session::put('otp_verified', $otpVerified);
            
            Log::info('User phone verification completed after OTP verification', [
                'user_id' => $user->id,
                'is_authenticated' => Auth::check(),
                'current_user_id' => Auth::id(),
                'phone_verified_at' => $user->phone_verified_at,
                // 'otp_verified_flag' => Session::get('otp_verified'),
                // 'session_id' => Session::getId(),
                'nida_verified' => $user->isNidaVerified(),
                'nida_verified_at' => $user->nida_verified_at,
            ]);

            //redirect to email verification page
            return redirect()->intended(route('otp.show'))
                ->with('success', 'Phone verification successful! Login to your account.');
                   
        } else {
            Log::warning('Invalid OTP verification attempt', [
                'user_id' => $user->id,
            ]);
            
            throw ValidationException::withMessages([
                'otp' => 'The verification code is invalid or has expired. Please try again.',
            ]);
        }
    }


        /**
     * Show OTP verification form
     */
    public function showSmsOtp()
    {
        // Check if user is in the middle of login process
        if (!Session::has('otp_user_id')) {
            return redirect()->route('login')
                ->with('error', 'Please login first to access the verification page.');
        }

        $userId = Session::get('otp_user_id');
        $user = User::find($userId);

        if (!$user) {
            Session::forget(['otp_user_id', 'login_timestamp']);
            return redirect()->route('login')
                ->with('error', 'Invalid session. Please login again.');
        }

        // Check session timeout (30 minutes)
        $loginTimestamp = Session::get('login_timestamp');
        if (!$loginTimestamp || (now()->timestamp - $loginTimestamp) > 1800) {
            Session::forget(['otp_user_id', 'login_timestamp']);
            return redirect()->route('login')
                ->with('error', 'Session expired. Please login again.');
        }

        // Get remaining time for current OTP
        $remainingTime = $this->otpService->getOtpRemainingTime($user);
        
        Log::info('Showing OTP page', [
            'user_id' => $userId,
            'remaining_time' => $remainingTime
        ]);
        
        return view('auth.smsOtp', [
            'user' => $user,
            'remainingTime' => $remainingTime ?? 0,
            'canResend' => $this->otpService->canResendOtp($user)
        ]);
    }

     /**
     * Resend OTP SMS
     */
    public function resendSmsOtp(Request $request)
    {
        if (!Session::has('otp_user_id')) {
            return redirect()->route('login')
                ->with('error', 'Session expired. Please login again.');
        }

        $userId = Session::get('otp_user_id');
        $user = User::find($userId);

        if (!$user) {
            Session::forget(['otp_user_id', 'login_timestamp']);
            return redirect()->route('login')
                ->with('error', 'Invalid session. Please login again.');
        }

        // Check if we can resend (rate limiting)
        if (!$this->smsOtpService->canResendOtp($user)) {
            return back()->with('error', 'Please wait before requesting a new verification code.');
        }

        Log::info('Resending OTP', ['user_id' => $user->id]);

        if ($this->smsOtpService->generateAndSendOtp($user)) {
            Log::info('OTP resent successfully', ['user_id' => $user->id]);
            return back()->with('success', 'A new verification code has been sent to your phone.');
        } else {
            Log::error('Failed to resend OTP', ['user_id' => $user->id]);
            return back()->with('error', 'Failed to send verification code. Please try again later.');
        }
    }
}