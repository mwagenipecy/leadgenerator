<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
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
        // Priority: Query Parameter > Session > User Preference > Config Default
        $locale = null;
        $supportedLocales = ['en', 'sw'];
        
        // Helper function to safely get locale - try session and cookie
        $getLocale = function() use ($request) {
            $locale = null;
            
            // Method 1: Try Session facade (most reliable)
            try {
                if (Session::has('locale')) {
                    $locale = Session::get('locale');
                    if ($locale && in_array($locale, ['en', 'sw'])) {
                        return $locale;
                    }
                }
            } catch (\Exception $e) {
                // Continue to next method
            }
            
            // Method 2: Try request session
            try {
                if ($request->hasSession()) {
                    $session = $request->session();
                    if ($session->isStarted() && $session->has('locale')) {
                        $locale = $session->get('locale');
                        if ($locale && in_array($locale, ['en', 'sw'])) {
                            return $locale;
                        }
                    }
                }
            } catch (\Exception $e) {
                // Continue
            }
            
            // Method 3: Try reading directly from session store
            try {
                $locale = session('locale');
                if ($locale && in_array($locale, ['en', 'sw'])) {
                    return $locale;
                }
            } catch (\Exception $e) {
                // Continue
            }
            
            // Method 4: Try cookie as fallback (more persistent)
            try {
                $cookieLocale = $request->cookie('app_locale');
                if ($cookieLocale && in_array($cookieLocale, ['en', 'sw'])) {
                    return $cookieLocale;
                }
            } catch (\Exception $e) {
                // Continue
            }
            
            return null;
        };
        
        // Store locale value to set cookie on response
        $localeToSet = null;
        
        // Helper function to safely set locale - use session and cookie
        $setLocale = function($value) use ($request, &$localeToSet) {
            // Only set if different to avoid unnecessary session writes
            $currentSessionLocale = null;
            try {
                $currentSessionLocale = Session::get('locale');
            } catch (\Exception $e) {
                // Continue
            }
            
            // Only update if different
            if ($currentSessionLocale !== $value) {
                // Method 1: Use Session facade (primary method)
                try {
                    Session::put('locale', $value);
                } catch (\Exception $e) {
                    // Continue
                }
                
                // Method 2: Use request session if available
                try {
                    if ($request->hasSession()) {
                        $session = $request->session();
                        if ($session->isStarted()) {
                            $session->put('locale', $value);
                        }
                    }
                } catch (\Exception $e) {
                    // Continue
                }
                
                // Method 3: Use session() helper
                try {
                    session(['locale' => $value]);
                } catch (\Exception $e) {
                    // Continue
                }
            }
            
            // Store value to set cookie on response
            $localeToSet = $value;
        };
        
        // First, check query parameter (for language switcher redirects)
        if ($request->has('locale') && in_array($request->get('locale'), $supportedLocales)) {
            $locale = $request->get('locale');
            // Immediately save to session and cookie using all methods
            $setLocale($locale);
        }
        // Then check session/cookie (highest priority for persistence across pages)
        elseif (($storedLocale = $getLocale()) && in_array($storedLocale, $supportedLocales)) {
            $locale = $storedLocale;
            // If we got locale from cookie but not session, sync session with cookie
            $sessionHasLocale = false;
            try {
                $sessionHasLocale = Session::has('locale') || 
                    ($request->hasSession() && $request->session()->has('locale'));
            } catch (\Exception $e) {
                // Continue
            }
            
            // If session doesn't have locale but cookie does, update session
            if (!$sessionHasLocale) {
                try {
                    Session::put('locale', $storedLocale);
                } catch (\Exception $e) {
                    // Continue
                }
            }
            
            // Ensure it's persisted (in case it wasn't saved properly before)
            $setLocale($locale);
        }
        // Then check user preference if authenticated and no stored locale
        elseif (auth()->check() && auth()->user()->preferred_language) {
            $userLocale = auth()->user()->preferred_language;
            if (in_array($userLocale, $supportedLocales)) {
                $locale = $userLocale;
                // Also set it in session and cookie for consistency
                $setLocale($locale);
            }
        }
        
        // If still no valid locale, use config default
        if (!$locale || !in_array($locale, $supportedLocales)) {
            $locale = config('app.locale');
            // Only set default if there's no existing stored locale
            // This prevents overwriting a valid stored locale
            $storedLocale = $getLocale();
            if (!$storedLocale) {
                $setLocale($locale);
            }
        } else {
            // ALWAYS ensure valid locale is persisted on every request
            // This ensures the locale persists across all page navigations
            $setLocale($locale);
        }
        
        // Set locale in application FIRST (before any other operations)
        App::setLocale($locale);
        
        // Make sure locale is available to views and Livewire
        view()->share('currentLocale', $locale);
        
        // Get the response first (don't save session here to avoid CSRF issues)
        $response = $next($request);
        
        // Only save session and set cookie after getting response
        // This avoids interfering with CSRF token validation
        if ($localeToSet && in_array($localeToSet, $supportedLocales)) {
            // Save session once after response is created
            try {
                if ($request->hasSession() && $request->session()->isStarted()) {
                    // Only save if locale was actually changed
                    if ($request->session()->get('locale') !== $localeToSet) {
                        $request->session()->put('locale', $localeToSet);
                    }
                }
            } catch (\Exception $e) {
                // Continue
            }
            
            // Attach cookie to response
            try {
                // Create cookie that lasts 1 year (525600 minutes)
                $cookie = Cookie::make('app_locale', $localeToSet, 525600, '/', null, false, false);
                $response->withCookie($cookie);
            } catch (\Exception $e) {
                // Continue if cookie can't be set
            }
        }
        
        return $response;
    }
}

