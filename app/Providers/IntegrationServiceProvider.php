<?php


namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Application;
use App\Observers\ApplicationObserver;
use App\Events\ApplicationStatusChanged;
use App\Listeners\ProcessIntegrations;
use Illuminate\Support\Facades\Event;

class IntegrationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Register model observer
        Application::observe(ApplicationObserver::class);

        // Register event listener
        Event::listen(ApplicationStatusChanged::class, ProcessIntegrations::class);
    }

    public function register()
    {
        $this->app->singleton(\App\Services\IntegrationService::class);
    }
}
