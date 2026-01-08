<?php

namespace App\Providers;

use App\Services\OtpService;
use App\Services\OutlookMailService;
use App\Mail\Transport\OutlookTransport;
use App\Http\Middleware\RequireOtpVerification;
use Illuminate\Mail\MailManager;
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

        // Register Outlook Mail Service as singleton
        $this->app->singleton(OutlookMailService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register middleware alias for Laravel 12
        Route::aliasMiddleware('otp.required', RequireOtpVerification::class);

        // Register Outlook mail transport
        $this->app->make(MailManager::class)->extend('outlook', function (array $config) {
            return new OutlookTransport(
                $this->app->make(OutlookMailService::class)
            );
        });
    }
}