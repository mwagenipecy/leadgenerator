<?php


namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{


public function boot()
{
    if ($this->app->runningInConsole()) {
        Passport::loadKeysFrom(base_path('storage/oauth'));
    }
}


}