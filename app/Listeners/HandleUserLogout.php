<?php

namespace App\Listeners;

use App\Services\LogService;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class HandleUserLogout
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Logout $event): void
    {
        // Log user logout
        if ($event->user) {
            LogService::logLogout($event->user);
        }
    }
}