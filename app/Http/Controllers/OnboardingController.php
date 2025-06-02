<?php

namespace App\Http\Controllers;

use App\Models\NidaVerification;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    public function registerNewUser(){

        return view('pages.onboarding.register');
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
        return redirect()->route('verification.qr-code')
            ->with('error', 'Invalid verification link.');
    }
    
    // Validate token and proceed with verification
    $verification = NidaVerification::where('verification_token', $token)
        ->where('token_expires_at', '>', now())
        ->first();
        
    if (!$verification) {
        return redirect()->route('verification.qr-code')
            ->with('error', 'Verification link has expired.');
    }
    
    // Mark phone as connected
    $verification->update(['phone_connected' => true]);
    
    // Continue with your verification logic
    return view('pages.onboarding.phone-verification');
}





    public function qrCodeVerification(){

        return view('pages.onboarding.qr-code');
    }
}
