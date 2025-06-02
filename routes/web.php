<?php

use App\Http\Controllers\LenderManagementController;
use App\Http\Controllers\LoanApplicationController;
use App\Http\Controllers\LoanProductManagementController;
use App\Http\Controllers\OnboardingController;
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
    Route::get('/verification/qr-code', [OnboardingController::class,'qrCodeVerification'])->name('verification.qr-code');
    Route::get('/verification/questionnaire', function() { return view('verification.questionnaire'); })->name('verification.questionnaire');
    


    });

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





