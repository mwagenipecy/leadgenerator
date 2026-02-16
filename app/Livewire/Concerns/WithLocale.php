<?php

namespace App\Livewire\Concerns;

trait WithLocale
{
    /**
     * Boot the component and set locale from session
     */
    public function boot(): void
    {
        // Ensure locale is set from session on every Livewire request
        if (session()->has('locale')) {
            $locale = session()->get('locale');
            if (in_array($locale, ['en', 'sw'])) {
                app()->setLocale($locale);
            }
        }
    }
}

