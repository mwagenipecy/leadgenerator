<?php


use App\Http\Controllers\BillingController;
use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LenderManagementController;
use App\Http\Controllers\LoanApplicationController;
use App\Http\Controllers\LoanProductManagementController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SystemSettingController;
use App\Http\Controllers\TRAController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\CompanyVerificationController;
use App\Http\Controllers\TermsController;
use App\Models\NidaVerification;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NidaVerificationController;
use App\Livewire\VerificationMethodSelector;
use App\Livewire\PhonePhotoVerification;
use App\Livewire\Lender\Dashboard;
use App\Livewire\QrCodeVerification;
use App\Livewire\QuestionnaireVerification;
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
Route::get('/', function () {
    $heroSliders = \App\Models\HeroSlider::active()->ordered()->get();
    return view('welcome', compact('heroSliders'));
});

// PUBLIC BLOG ROUTES
Route::get('/blog', function () {
    return view('blog.index');
})->name('blog.index');
Route::get('/blog/{slug}', function ($slug) {
    return view('blog.show', ['slug' => $slug]);
})->name('blog.show');

// PUBLIC TERMS AND CONDITIONS
Route::get('/terms', [\App\Http\Controllers\TermsController::class, 'show'])->name('terms.show');

// LANGUAGE SWITCHING
Route::get('/language/{locale}', [\App\Http\Controllers\LanguageController::class, 'switch'])->name('language.switch');




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
            
            Log::info('Authentication successful', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role
            ]);

            // Regenerate session for security
            $request->session()->regenerate();

            // ALL users (including admin and lender) need OTP verification
            Log::info('User login - starting OTP flow', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role
            ]);

            // Store user before logout
            $userId = $user->id;
            $userModel = $user;

            // Immediately log out for OTP verification
            $guard = Auth::guard();
            if (method_exists($guard, 'logout')) {
                $guard->logout();
            }

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
    $guard = Auth::guard();
    if (method_exists($guard, 'logout')) {
        $guard->logout();
    }
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout');



// Protected routes (these routes require OTP verification)
// Note: NIDA verification is checked in OTP controller and middleware
Route::middleware(['auth', 'otp.required', 'nida.verified'])->group(function () {
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
    Route::get('register/company',[OnboardingController::class,'registerCompany'])->name('company.register');
    
    // QR Code verification link - Must be accessible without auth for mobile login
    Route::get('/verification/phone-photo_', [OnboardingController::class,'phoneVerificationByLink'])->name('verification.phone-photo.link');

Route::middleware([  'auth:sanctum',config('jetstream.auth_session'), ])->group(function () {
    Route::group(['prefix'=>'onboarding'],function(){
    Route::get('verification/option',[OnboardingController::class,'verificationOption'])->name('verification.options');
    Route::get('/verification/phone-photo', [OnboardingController::class,'phoneVerification'])->name('verification.phone-photo');
    Route::get('/verification/qr-code', [OnboardingController::class,'qrCodeVerification'])->name('verification.qr-code');
    Route::get('/verification/questionnaire', [OnboardingController::class,'questionnaireVerification'])->name('verification.questionnaire');
    });
    
    // Company KYC route
    Route::get('/company/kyc', \App\Livewire\Onboarding\CompanyKyc::class)->name('company.kyc');
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


Route::middleware([  'auth:sanctum',config('jetstream.auth_session'), 'verified', 'nida.verified'])->group(function () {

    /// lender managenent section
    Route::get('lender-list',[LenderManagementController::class,'index'])->name('lenders.index');
    Route::get('lenders/{lender}/view', [LenderManagementController::class,'viewLender'])->name('lenders.view');
    Route::get('lenders/{lender}/dashboard', [LenderManagementController::class,'viewLender'])->name('lender.dashboard');
    Route::post('lenders/{lender}/approve', [LenderManagementController::class,'approveLender'])->name('lenders.approve');
    Route::post('lenders/{lender}/reject', [LenderManagementController::class,'rejectLender'])->name('lenders.reject');
    Route::post('lenders/{lender}/suspend', [LenderManagementController::class,'suspendLender'])->name('lenders.suspend');
    Route::post('lenders/{lender}/reactivate', [LenderManagementController::class,'reactivateLender'])->name('lenders.reactivate');
    Route::post('lenders/{lender}/add-user', [LenderManagementController::class,'addUser'])->name('lenders.add-user');
    Route::delete('lenders/{lender}/delete', [LenderManagementController::class,'deleteLender'])->name('lenders.delete');

    // Loan product management
    Route::get('loan-product/list',[LoanProductManagementController::class,'index'])->name('loan.product.index');
    Route::get('loan-product/create',[LoanProductManagementController::class,'createProduct'])->name('loan-products.create');
    Route::get('loan-product/{id}/show',[LoanProductManagementController::class,'showProduct'])->name('loan-products.show');
    Route::get('loan-product/{id}/edit',[LoanProductManagementController::class,'editProduct'])->name('loan-products.edit');

    
    
    // LOAN APPLICATION MANAGEMENT 
    Route::get('loanApplication',[LoanApplicationController::class,'index'])->name('user.loan.application');
    Route::get('application-list',[LoanApplicationController::class,'applicationList'])->name('application.list');
    
    // REPORTS
    Route::get('reports/booking', \App\Livewire\Reports\BookingReports::class)->name('reports.booking');
    Route::get('application/{id}/view',[LoanApplicationController::class,'applicationView'])->name('loan-applications.view');

    Route::group(['prefix'=> 'application'], function () {

        Route::get('create',[LoanApplicationController::class,'createApplication'])->name('loan-application.create');
        Route::get('profile',[LoanApplicationController::class,'updateProfile'])->name('loan-application.profile');
        Route::get('completed',[LoanApplicationController::class,'completedApplications'])->name('loan-application.completed');
    });


    /// VIEW LEAD MAGEMENT 
    Route::get('view-loan-details/{id}',[LeadController::class,'viewLead'])->name('view.loan.details');



      /*********************************** USER  PROFILE ***********************/
      Route::group(['prefix'=> 'user'], function () {
        Route::get('profile',[ProfileController::class,'viewProfile'])->name('user.profile');
        Route::get('setting',[ProfileController::class,'userSetting'])->name('user.setting');
    
       });

      /*********************************** NOTIFICATIONS ***********************/
      Route::get('notifications', \App\Livewire\Notifications\Index::class)->name('notifications.index');


    /*********************************** USERMANAGEMENT ****************************************/
    Route::get('user-management',[UserManagementController::class,'index'])->name('user.management');
    Route::get('company-verification',[CompanyVerificationController::class,'index'])->name('admin.company.verification');
    Route::get('company-verification/{user}',[CompanyVerificationController::class,'show'])->name('admin.company.verification.show');
    
    /*********************************** LANGUAGE MANAGEMENT ****************************************/
    Route::get('language-management', \App\Livewire\Admin\LanguageManagement::class)->name('admin.language.management');
    
    /*********************************** LOAN CATEGORIES ****************************************/
    Route::resource('loan-categories', \App\Http\Controllers\Admin\LoanCategoryController::class)->names([
        'index' => 'admin.loan-categories.index',
        'create' => 'admin.loan-categories.create',
        'store' => 'admin.loan-categories.store',
        'show' => 'admin.loan-categories.show',
        'edit' => 'admin.loan-categories.edit',
        'update' => 'admin.loan-categories.update',
        'destroy' => 'admin.loan-categories.destroy',
    ]);
    Route::post('loan-categories/{loanCategory}/disable', [\App\Http\Controllers\Admin\LoanCategoryController::class, 'disable'])->name('admin.loan-categories.disable');
    Route::get('user-management/roles', function () {
        return view('pages.user-management.roles');
    })->name('user.management.roles');
    Route::get('user-management/permissions', function () {
        return view('pages.user-management.permissions');
    })->name('user.management.permissions');
    Route::get('system-logs', function () {
        return view('pages.system-logs');
    })->name('system.logs');



    /********************************** SYSTEM SETTINGS *************************/
    Route::get('system-settings',[SystemSettingController::class,'index'])->name('system.settings');


    /************************************* WEBHOOK INTEGRATION *********************************/
    Route::get('webhook-integration',[IntegrationController::class,'webhookIntegration'])->name('webhook.integration');




    /********************************* VERIFICATION CHECK ********************************/
    Route::get('lincense-verification',[TRAController::class,'lincenseVerification'])->name('lincense.verification');
    Route::get('taxpayer-verification',[TRAController::class,'taxpayerVerification'])->name('taxpayer.verification');
   Route::get('motor-vehicle-verification',[TRAController::class,'motorVehicleVerification'])->name('motor.vehicle.verification');
   Route::get('credit-report',[TRAController::class,'creditReport'])->name('credit.report');



   /********************************** BILLING SECTION  ***************************************/
   Route::get('billing-section',[BillingController::class,'billingSection'])->name('billing.section');

   /********************************** BLOG MANAGEMENT  ***************************************/
   Route::get('blog-management', \App\Livewire\Admin\BlogManagement::class)->name('admin.blog.management');
   Route::get('blog-management/create', \App\Livewire\Admin\BlogCreate::class)->name('admin.blog.create');
   Route::get('blog-management/{id}/edit', \App\Livewire\Admin\BlogEdit::class)->name('admin.blog.edit');

   /********************************** HERO SLIDER MANAGEMENT  ***************************************/
   Route::get('hero-slider-management', \App\Livewire\Admin\HeroSliderManagement::class)->name('admin.hero-slider.management');

   /********************************** PROMOTION MANAGEMENT  ***************************************/
   Route::get('promotion-management', \App\Livewire\Admin\PromotionManagement::class)->name('admin.promotion.management');




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







