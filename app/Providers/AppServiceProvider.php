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
        
        // Set locale for Livewire on every component hydration
        // This ensures Livewire components respect the session locale
        if (class_exists(\Livewire\Livewire::class)) {
            try {
                \Livewire\Livewire::listen('component.hydrate', function ($component) {
                    if (session()->has('locale')) {
                        $locale = session()->get('locale');
                        if (in_array($locale, ['en', 'sw'])) {
                            app()->setLocale($locale);
                        }
                    }
                });
            } catch (\Exception $e) {
                // Silently fail if Livewire listener can't be registered
                // The middleware will still handle locale setting
            }
        }
    }
}
