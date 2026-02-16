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
        
        // Set locale in session
        Session::put('locale', $locale);
        App::setLocale($locale);
        
        // Save user preference if authenticated
        if (Auth::check()) {
            $user = Auth::user();
            if (isset($user->preferred_language)) {
                $user->preferred_language = $locale;
                $user->save();
            }
        }
        
        return redirect()->back()->with('success', __('Language changed successfully'));
    }
}

