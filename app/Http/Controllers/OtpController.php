<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class OtpController extends Controller
{
    protected OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
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
            'provided_otp' => $request->otp,
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
                'session_id' => Session::getId()
            ]);
            
            return redirect()->intended(route('dashboard'))
                ->with('success', 'Login successful! Welcome back.');
        } else {
            Log::warning('Invalid OTP verification attempt', [
                'user_id' => $user->id,
                'provided_otp' => $request->otp
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
}