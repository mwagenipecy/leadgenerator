<?php

namespace App\Observers;

use App\Models\Application;
use App\Events\ApplicationStatusChanged;

class ApplicationObserver
{
    public function updating(Application $application)
    {
        // Store the original status before update
        $application->_original_status = $application->getOriginal('status');
    }

    public function updated(Application $application)
    {
        // Check if status changed
        if ($application->isDirty('status') && isset($application->_original_status)) {
            event(new ApplicationStatusChanged($application, $application->_original_status));
        }
    }
}