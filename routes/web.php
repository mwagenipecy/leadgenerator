<?php


use App\Http\Controllers\BillingController;
use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\LenderManagementController;
use App\Http\Controllers\LoanApplicationController;
use App\Http\Controllers\LoanProductManagementController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SystemSettingController;
use App\Http\Controllers\TRAController;
use App\Http\Controllers\UserManagementController;
use App\Models\NidaVerification;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NidaVerificationController;
use App\Livewire\VerificationMethodSelector;
use App\Livewire\PhonePhotoVerification;
use App\Livewire\Lender\Dashboard;
use App\Livewire\QrCodeVerification;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\OtpController;
use App\Http\Middleware\CheckPermissions;


use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

use App\Livewire\NoPermissions;




Route::get('/no-permissions', NoPermissions::class)
    ->name('no-permissions')
    ->middleware('auth');


// LANDING PAGE
Route::get('/', function () {return view('welcome');});




// Authentication Routes
Route::middleware('guest')->group(function () {
    // Login form
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    // Handle login submission
    Route::post('/login', function (Request $request) {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);


        $login_type = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';


       // $credentials = $request->only('email', 'password');

        $credentials = [
            $login_type => $request->input('login'),
            'password' => $request->input('password'),
        ];


        $remember = $request->boolean('remember');

        Log::info('Login attempt', ['login' =>$request->input('login')]);

        // Attempt authentication
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            Log::info('Authentication successful, starting OTP flow', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            // Regenerate session for security
            $request->session()->regenerate();

            // Store user before logout
            $userId = $user->id;
            $userModel = $user;

            // Immediately log out for OTP verification
            Auth::logout();

            // Clear any previous OTP verification
            Session::forget('otp_verified');

            // Store user info for OTP process
            Session::put('otp_user_id', $userId);
            Session::put('login_timestamp', now()->timestamp);

            // Generate and send OTP
            $otpService = app(OtpService::class);
            if ($otpService->generateAndSendOtp($userModel)) {
                Log::info('OTP sent successfully, redirecting to OTP page', ['user_id' => $userId]);
                
                return redirect()->route('otp.show')
                    ->with('success', 'Please check your email for the verification code.');
            } else {
                Log::error('Failed to send OTP', ['user_id' => $userId]);
                
                // Clean up session if OTP fails
                Session::forget(['otp_user_id', 'login_timestamp']);
                
                return back()->withErrors([
                    'email' => 'Failed to send verification code. Please try again.',
                ])->withInput($request->except('password'));
            }
        }

        Log::warning('Authentication failed', ['email' => $credentials['email']]);

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    });

    // OTP verification routes
    Route::get('/otp', [OtpController::class, 'show'])->name('otp.show');
    Route::post('/otp/verify', [OtpController::class, 'verify'])->name('otp.verify');
    Route::post('/otp/resend', [OtpController::class, 'resend'])->name('otp.resend');
});



// Logout route
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout');



// Protected routes
Route::middleware(['auth', 'otp.required'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::get('/profile', function () {
        return view('profile.show');
    })->name('profile.show');
});





// Route::middleware(['guest'])->group(function () {
//     Route::get('/otp', [OtpController::class, 'show'])->name('otp.show');
//     Route::post('/otp/verify', [OtpController::class, 'verify'])->name('otp.verify');
//     Route::post('/otp/resend', [OtpController::class, 'resend'])->name('otp.resend');
// });



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
    Route::get('lenders/{lender}/dashboard', [LenderManagementController::class,'viewLender'])->name('lender.dashboard');

    // Loan product management
    Route::get('loan-product/list',[LoanProductManagementController::class,'index'])->name('loan.product.index');
    Route::get('loan-product/create',[LoanProductManagementController::class,'createProduct'])->name('loan-products.create');
    Route::get('loan-product/{id}/show',[LoanProductManagementController::class,'showProduct'])->name('loan-products.show');
    Route::get('loan-product/{id}/edit',[LoanProductManagementController::class,'editProduct'])->name('loan-products.edit');

    
    


    // LOAN APPLICATION MANAGEMENT 
    Route::get('loanApplication',[LoanApplicationController::class,'index'])->name('user.loan.application');
    Route::get('application-list',[LoanApplicationController::class,'applicationList'])->name('application.list');




      /*********************************** USER  PROFILE ***********************/
      Route::group(['prefix'=> 'user'], function () {
        Route::get('profile',[ProfileController::class,'viewProfile'])->name('user.profile');
        Route::get('setting',[ProfileController::class,'userSetting'])->name('user.setting');
    
       });


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



   /********************************** BILLING SECTION  ***************************************/
   Route::get('billing-section',[BillingController::class,'billingSection'])->name('billing.section');




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

         return view('nida');
});






