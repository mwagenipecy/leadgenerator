<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

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
        
        // Set locale in session (this takes priority)
        Session::put('locale', $locale);
        Session::save(); // Force save session immediately
        
        // Set locale for current request
        App::setLocale($locale);
        
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
        
        // Force a full page reload to ensure Livewire components pick up the new locale
        // Use redirect()->to() with the current URL to force a fresh page load
        $redirectUrl = url()->previous() ?: route('dashboard');
        
        return redirect($redirectUrl)->with('success', __('Language changed successfully'));
    }
}

