<?php

use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\LenderManagementController;
use App\Http\Controllers\LoanApplicationController;
use App\Http\Controllers\LoanProductManagementController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\SystemSettingController;
use App\Http\Controllers\TRAController;
use App\Http\Controllers\UserManagementController;
use App\Models\NidaVerification;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NidaVerificationController;
use App\Livewire\VerificationMethodSelector;
use App\Livewire\PhonePhotoVerification;
use App\Livewire\QrCodeVerification;
use App\Http\Controllers\Auth\RegisterController;


// LANDING PAGE
Route::get('/', function () {return view('welcome');});



    //ONBOARDING ROUTES OR LOGIN
    Route::get('register',[OnboardingController::class,'registerNewUser'])->name('user.register');

Route::middleware([  'auth:sanctum',config('jetstream.auth_session'), ])->group(function () {

    Route::group(['prefix'=>'onboarding'],function(){



    Route::get('verification/option',[OnboardingController::class,'verificationOption'])->name('verification.options');
    Route::get('/verification/phone-photo', [OnboardingController::class,'phoneVerification'])->name('verification.phone-photo');

    Route::get('/verification/phone-photo_', [OnboardingController::class,'phoneVerificationByLink'])->name('verification.phone-photo.link');

    
    Route::get('/verification/qr-code', [OnboardingController::class,'qrCodeVerification'])->name('verification.qr-code');
    Route::get('/verification/questionnaire', function() { return view('verification.questionnaire'); })->name('verification.questionnaire');
    


    });

});



Route::get('/verify-token/{token}', function ($token) {
    $verification = NidaVerification::where('verification_token', $token)
        ->where('token_expires_at', '>', now())
        ->where('status', 'pending')
        ->first();
    
    if (!$verification) {
        return response()->json([
            'valid' => false,
            'message' => 'Token not found or expired'
        ], 404);
    }
    
    return response()->json([
        'valid' => true,
        'expires_at' => $verification->token_expires_at,
        'time_remaining' => $verification->token_expires_at->diffInSeconds(now()),
        'phone_connected' => $verification->phone_connected,
        'status' => $verification->status
    ]);
});

// Real-time status endpoint for polling
Route::get('/verification-status/{token}', function ($token) {
    $verification = NidaVerification::where('verification_token', $token)->first();
    
    if (!$verification) {
        return response()->json([
            'found' => false,
            'message' => 'Verification not found'
        ], 404);
    }
    
    $timeRemaining = $verification->token_expires_at->diffInSeconds(now());
    $isExpired = $verification->isTokenExpired();
    
    return response()->json([
        'found' => true,
        'status' => $verification->status,
        'phone_connected' => $verification->phone_connected,
        'time_remaining' => max(0, $timeRemaining),
        'expired' => $isExpired,
        'expires_at' => $verification->token_expires_at->timestamp * 1000, // JS timestamp
        'updated_at' => $verification->updated_at->timestamp * 1000
    ]);
});






Route::middleware([  'auth:sanctum',config('jetstream.auth_session'), 'verified',])->group(function () {

   
   // dashboard routes
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');


    /// lender managenent section
    Route::get('lender-list',[LenderManagementController::class,'index'])->name('lenders.index');

    // Loan product management
    Route::get('loan-product',[LoanProductManagementController::class,'index'])->name('loan.product.index');


    // LOAN APPLICATION MANAGEMENT 
    Route::get('loanApplication',[LoanApplicationController::class,'index'])->name('user.loan.application');
    Route::get('application-list',[LoanApplicationController::class,'applicationList'])->name('application.list');






    /*********************************** USERMANAGEMENT ****************************************/
    Route::get('user-management',[UserManagementController::class,'index'])->name('user.management');



    /********************************** SYSTEM SETTINGS *************************/
    Route::get('system-settings',[SystemSettingController::class,'index'])->name('system.settings');


    /************************************* WEBHOOK INTEGRATION *********************************/
    Route::get('webhook-integration',[IntegrationController::class,'webhookIntegration'])->name('webhook.integration');




    /********************************* VERIFICATION CHECK ********************************/
    Route::get('lincense-verification',[TRAController::class,'lincenseVerification'])->name('lincense.verification');
    Route::get('taxpayer-verification',[TRAController::class,'taxpayerVerification'])->name('taxpayer.verification');
   Route::get('motor-vehicle-verification',[TRAController::class,'motorVehicleVerification'])->name('motor.vehicle.verification');

    Route::get('/verification', VerificationMethodSelector::class)->name('verification.method');
    
    // Individual verification method pages
    // Route::get('/verification/phone-photo', PhonePhotoVerification::class)->name('verification.phone-photo');
    // Route::get('/verification/qr-code', QrCodeVerification::class)->name('verification.qr-code');
    // Route::get('/verification/questionnaire', function() { return view('verification.questionnaire'); })->name('verification.questionnaire');
    
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
      // return view('login2');
       // return view('onboarding');

      //  return view('pages.onboarding.register');

      //  return view('dashboard3');
         return view('nida');
});





