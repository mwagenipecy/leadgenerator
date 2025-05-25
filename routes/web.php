<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NidaVerificationController;
use App\Livewire\VerificationMethodSelector;
use App\Livewire\PhonePhotoVerification;
use App\Livewire\QrCodeVerification;
use App\Http\Controllers\Auth\RegisterController;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');



    Route::get('/verification', VerificationMethodSelector::class)->name('verification.method');
    
    // Individual verification method pages
    Route::get('/verification/phone-photo', PhonePhotoVerification::class)->name('verification.phone-photo');
    Route::get('/verification/qr-code', QrCodeVerification::class)->name('verification.qr-code');
    Route::get('/verification/questionnaire', function() {
        return view('verification.questionnaire');
    })->name('verification.questionnaire');
    
    // API endpoints for verification status
    Route::get('/api/verification-status', [NidaVerificationController::class, 'getVerificationStatus']);
    Route::post('/api/mark-phone-connected/{token}', [NidaVerificationController::class, 'markPhoneConnected']);



});



Route::get('/mobile/verify/{token}', [NidaVerificationController::class, 'showMobileVerification'])->name('mobile.verification');




Route::get('test',function(){

   // return view('login');
   // return view('dome3');
     //  return view('demo');
           //  return view('demo2'); //kaliii

    //return view('welcome');

      //  return view('login2');
       // return view('onboarding');
        return view('dashboard3');

       // return view('nida');





});





