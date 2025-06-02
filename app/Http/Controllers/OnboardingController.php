<?php

namespace App\Http\Controllers;

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


    public function qrCodeVerification(){

        return view('pages.onboarding.qr-code');
    }
}
