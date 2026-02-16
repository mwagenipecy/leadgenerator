<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Priority: Session > User Preference > Config Default
        $locale = null;
        
        // First, check session (highest priority for manual language switching)
        if (Session::has('locale')) {
            $locale = Session::get('locale');
        }
        // Then check user preference if authenticated and no session locale
        elseif (auth()->check() && auth()->user()->preferred_language) {
            $locale = auth()->user()->preferred_language;
            // Also set it in session for consistency
            Session::put('locale', $locale);
        }
        // Finally, use config default
        else {
            $locale = config('app.locale');
        }
        
        // Validate locale (only allow supported languages)
        $supportedLocales = ['en', 'sw'];
        if (!in_array($locale, $supportedLocales)) {
            $locale = config('app.locale');
        }
        
        // Set locale in application FIRST (before any other operations)
        App::setLocale($locale);
        
        // Ensure locale is in session (this is the source of truth)
        if (!Session::has('locale') || Session::get('locale') !== $locale) {
            Session::put('locale', $locale);
            Session::save(); // Force save to ensure it persists
        }
        
        // Make sure locale is available to views and Livewire
        view()->share('currentLocale', $locale);
        
        return $next($request);
    }
}

