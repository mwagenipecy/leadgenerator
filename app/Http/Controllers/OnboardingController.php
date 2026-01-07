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

        return view('pages.onboarding.verification-method');
    }


    public function phoneVerification(){
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
        
        // Redirect to verification options page
        return redirect()->route('verification.options')
            ->with('success', 'Successfully logged in! Please choose your verification method to continue.');
    }





    public function qrCodeVerification(){

        return view('pages.onboarding.qr-code');
    }

    public function questionnaireVerification(){

        return view('pages.onboarding.questionnaire');
    }
}
