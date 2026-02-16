<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;

class LanguageController extends Controller
{
    /**
     * Switch application language
     */
    public function switch(Request $request, $locale)
    {
        // Validate locale
        $supportedLocales = ['en', 'sw'];
        
        if (!in_array($locale, $supportedLocales)) {
            return redirect()->back()->with('error', 'Unsupported language');
        }
        
        // Set locale in session using multiple methods to ensure persistence
        $request->session()->put('locale', $locale);
        Session::put('locale', $locale);
        
        // Set locale for current request
        App::setLocale($locale);
        
        // Force save session multiple times to ensure it persists
        $request->session()->save();
        Session::save();
        
        // Verify the session was saved
        if ($request->session()->get('locale') !== $locale) {
            // If session didn't save, try again with explicit commit
            $request->session()->put('locale', $locale);
            $request->session()->save();
        }
        
        // Save user preference if authenticated (for future sessions)
        if (Auth::check()) {
            $user = Auth::user();
            // Check if user model has preferred_language column
            if (method_exists($user, 'getFillable') && in_array('preferred_language', $user->getFillable())) {
                $user->preferred_language = $locale;
                $user->save();
            } elseif (property_exists($user, 'preferred_language')) {
                $user->preferred_language = $locale;
                $user->save();
            }
        }
        
        // Get the previous URL or referrer - use multiple methods for reliability
        $referer = $request->headers->get('referer');
        $previousUrl = $referer ?: url()->previous();
        
        // Determine redirect URL based on current page
        $redirectUrl = null;
        
        // Check if we're on the login page (check referer, previous URL, or route)
        $isLoginPage = str_contains($previousUrl ?? '', '/login') || 
                       str_contains($referer ?? '', '/login') ||
                       $request->routeIs('login');
        
        // Check if we're on the registration page
        $isRegisterPage = str_contains($previousUrl ?? '', '/register') || 
                          str_contains($referer ?? '', '/register') ||
                          $request->routeIs('user.register') || 
                          $request->routeIs('company.register') || 
                          request()->has('type');
        
        // Check if we're on the OTP page
        $isOtpPage = str_contains($previousUrl ?? '', '/otp') || 
                     str_contains($referer ?? '', '/otp') ||
                     $request->routeIs('otp.show');
        
        // Check if we're on the landing/welcome page (home page)
        // If previous URL is root or doesn't contain login/register/dashboard, assume landing page
        $isLandingPage = false;
        $refererPath = $referer ? parse_url($referer, PHP_URL_PATH) : null;
        $previousPath = $previousUrl ? parse_url($previousUrl, PHP_URL_PATH) : null;
        
        // Check if path is root or empty
        if (($refererPath === '/' || $refererPath === '' || $refererPath === null) ||
            ($previousPath === '/' || $previousPath === '' || $previousPath === null)) {
            // Make sure it's not login, register, or dashboard
            if (!str_contains($referer ?? '', '/login') && 
                !str_contains($referer ?? '', '/register') && 
                !str_contains($referer ?? '', '/dashboard') &&
                !str_contains($previousUrl ?? '', '/login') && 
                !str_contains($previousUrl ?? '', '/register') && 
                !str_contains($previousUrl ?? '', '/dashboard')) {
                $isLandingPage = true;
            }
        }
        
        // Add locale as query parameter for fallback
        $localeParam = 'locale=' . urlencode($locale);
        
        if ($isLoginPage) {
            $redirectUrl = route('login') . '?' . $localeParam;
        }
        elseif ($isOtpPage) {
            $redirectUrl = route('otp.show') . '?' . $localeParam;
        }
        elseif ($isRegisterPage) {
            $type = request()->get('type', 'individual');
            // Extract type from URL if not in query parameter
            if (!request()->has('type')) {
                if (preg_match('/type=([^&]+)/', $previousUrl ?? '', $matches)) {
                    $type = urldecode($matches[1]);
                } elseif (preg_match('/type=([^&]+)/', $referer ?? '', $matches)) {
                    $type = urldecode($matches[1]);
                }
            }
            // Validate type
            if (!in_array($type, ['individual', 'company'])) {
                $type = 'individual';
            }
            $redirectUrl = route('user.register') . '?type=' . urlencode($type) . '&' . $localeParam;
        }
        // For landing page, redirect back to home with locale
        elseif ($isLandingPage) {
            $redirectUrl = url('/') . '?' . $localeParam;
        }
        // For authenticated users, go to dashboard
        elseif (auth()->check()) {
            $redirectUrl = route('dashboard') . '?' . $localeParam;
        }
        // Default to previous URL or login page
        else {
            $separator = str_contains($previousUrl ?? '', '?') ? '&' : '?';
            $redirectUrl = ($previousUrl ?: route('login')) . $separator . $localeParam;
        }
        
        // Ensure session is fully committed before redirect
        $request->session()->save();
        Session::save();
        
        // Create cookie for locale persistence (1 year)
        $cookie = Cookie::make('app_locale', $locale, 525600, '/', null, false, false);
        
        // Force a hard redirect with cache prevention and cookie
        return redirect($redirectUrl)
            ->withCookie($cookie)
            ->withHeaders([
                'Cache-Control' => 'no-cache, no-store, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]);
    }
}

