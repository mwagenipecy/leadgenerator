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
        // Get locale from session, user preference, or default
        $locale = Session::get('locale', config('app.locale'));
        
        // Check if user has a preferred language
        if (auth()->check() && auth()->user()->preferred_language) {
            $locale = auth()->user()->preferred_language;
        }
        
        // Validate locale (only allow supported languages)
        $supportedLocales = ['en', 'sw'];
        if (!in_array($locale, $supportedLocales)) {
            $locale = config('app.locale');
        }
        
        App::setLocale($locale);
        Session::put('locale', $locale);
        
        return $next($request);
    }
}

