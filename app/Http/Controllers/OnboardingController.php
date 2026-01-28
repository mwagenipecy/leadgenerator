<?php

namespace App\Http\Controllers;

use App\Models\NidaVerification;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    public function registerNewUser(){

        return view('pages.onboarding.register');
    }

    public function registerCompany(){

        return view('pages.onboarding.register-company');
    }

    public function verificationOption()
    {
        $user = auth()->user();
        
        // Restrict admin and lender from accessing verification options
        $isAdmin = $user->isAdmin() || $user->hasRole('admin') || $user->hasRole('super_admin');
        $isLender = $user->isLender() || $user->hasRole('lender');
        
        if ($isAdmin || $isLender) {
            return redirect()->route('dashboard')
                ->with('info', 'Verification is not required for admin and lender accounts.');
        }
        
        // For company users from non-Tanzania countries, redirect to company KYC page
        // They don't need NIDA verification, only document submission
        if ($user->registration_type === 'company' && !$user->isFromTanzania()) {
            return redirect()->route('company.kyc')
                ->with('info', 'Please complete company verification by submitting required documents.');
        }

        return view('pages.onboarding.verification-method');
    }


    public function phoneVerification(){
        $user = auth()->user();
        
        // Restrict admin and lender from accessing phone verification
        $isAdmin = $user->isAdmin() || $user->hasRole('admin') || $user->hasRole('super_admin');
        $isLender = $user->isLender() || $user->hasRole('lender');
        
        if ($isAdmin || $isLender) {
            return redirect()->route('dashboard')
                ->with('info', 'Verification is not required for admin and lender accounts.');
        }
        
        // For company users from non-Tanzania countries, redirect to company KYC page
        // They don't need NIDA verification, only document submission
        if ($user->registration_type === 'company' && !$user->isFromTanzania()) {
            return redirect()->route('company.kyc')
                ->with('info', 'NIDA verification is not required for non-Tanzania companies. Please submit your documents instead.');
        }
        
        return view('pages.onboarding.phone-verification');
    }


    public function phoneVerificationByLink(Request $request)
    {
        $token = $request->get('token');
        
        if (!$token) {
            return redirect()->route('login')
                ->with('error', 'Invalid verification link. Please log in to continue.');
        }
        
        // Validate token and get verification record
        $verification = NidaVerification::where('verification_token', $token)
            ->where('token_expires_at', '>', now())
            ->first();
            
        if (!$verification) {
            return redirect()->route('login')
                ->with('error', 'Verification link has expired. Please log in to continue.');
        }
        
        // Get the user associated with this verification
        $user = $verification->user;
        
        if (!$user) {
            \Log::error('QR Code Login: User not found for verification', [
                'verification_id' => $verification->id,
                'token' => $token
            ]);
            
            return redirect()->route('login')
                ->with('error', 'User not found. Please log in manually.');
        }
        
        // Security check: Ensure user account is active
        if ($user->status === 'suspended' || $user->status === 'inactive') {
            \Log::warning('QR Code Login: Attempt to login with suspended/inactive account', [
                'user_id' => $user->id,
                'email' => $user->email,
                'status' => $user->status
            ]);
            
            return redirect()->route('login')
                ->with('error', 'Your account is not active. Please contact support.');
        }
        
        // Mark phone as connected
        $verification->update([
            'phone_connected' => true,
            'phone_connected_at' => now()
        ]);
        
        // Log the user in securely
        auth()->login($user, true); // 'true' enables "remember me"
        
        // Regenerate session for security
        $request->session()->regenerate();
        
        // Log successful QR code authentication
        \Log::info('QR Code Login: User successfully authenticated', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        // Check if user is admin or lender - redirect to dashboard instead
        $isAdmin = $user->isAdmin() || $user->hasRole('admin') || $user->hasRole('super_admin');
        $isLender = $user->isLender() || $user->hasRole('lender');
        
        if ($isAdmin || $isLender) {
            // Set OTP verified flag for admin/lender
            \Illuminate\Support\Facades\Session::put('otp_verified', true);
            
            return redirect()->route('dashboard')
                ->with('success', 'Successfully logged in! Welcome back.');
        }
        
        // For company users from non-Tanzania countries, skip NIDA verification
        // They will be handled by RequireCompanyVerification middleware
        if ($user->registration_type === 'company' && !$user->isFromTanzania()) {
            \Illuminate\Support\Facades\Session::put('otp_verified', true);
            
            \Log::info('QR Code Login: Non-Tanzania company user - skipping NIDA verification', [
                'user_id' => $user->id,
                'country' => $user->country,
                'registration_type' => $user->registration_type,
            ]);
            
            // Redirect to intended page (will be intercepted by RequireCompanyVerification middleware if needed)
            return redirect()->intended(route('dashboard'))
                ->with('success', 'Successfully logged in! Welcome back.');
        }
        
        // Regular users go to verification options
        return redirect()->route('verification.options')
            ->with('success', 'Successfully logged in! Please choose your verification method to continue.');
    }





    public function qrCodeVerification(){
        $user = auth()->user();
        
        // Restrict admin and lender from accessing QR code verification
        $isAdmin = $user->isAdmin() || $user->hasRole('admin') || $user->hasRole('super_admin');
        $isLender = $user->isLender() || $user->hasRole('lender');
        
        if ($isAdmin || $isLender) {
            return redirect()->route('dashboard')
                ->with('info', 'Verification is not required for admin and lender accounts.');
        }
        
        // For company users from non-Tanzania countries, redirect to company KYC page
        // They don't need NIDA verification, only document submission
        if ($user->registration_type === 'company' && !$user->isFromTanzania()) {
            return redirect()->route('company.kyc')
                ->with('info', 'NIDA verification is not required for non-Tanzania companies. Please submit your documents instead.');
        }
        
        return view('pages.onboarding.qr-code');
    }

    public function questionnaireVerification(){
        $user = auth()->user();
        
        // Restrict admin and lender from accessing questionnaire verification
        $isAdmin = $user->isAdmin() || $user->hasRole('admin') || $user->hasRole('super_admin');
        $isLender = $user->isLender() || $user->hasRole('lender');
        
        if ($isAdmin || $isLender) {
            return redirect()->route('dashboard')
                ->with('info', 'Verification is not required for admin and lender accounts.');
        }
        
        // For company users from non-Tanzania countries, redirect to company KYC page
        // They don't need NIDA verification, only document submission
        if ($user->registration_type === 'company' && !$user->isFromTanzania()) {
            return redirect()->route('company.kyc')
                ->with('info', 'NIDA verification is not required for non-Tanzania companies. Please submit your documents instead.');
        }
        
        return view('pages.onboarding.questionnaire');
    }
}
