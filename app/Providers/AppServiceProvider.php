<?php

namespace App\Providers;

use App\Services\OtpService;
use App\Http\Middleware\RequireOtpVerification;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register OTP Service as singleton
        $this->app->singleton(OtpService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register middleware alias for Laravel 12
        Route::aliasMiddleware('otp.required', RequireOtpVerification::class);
    }
}