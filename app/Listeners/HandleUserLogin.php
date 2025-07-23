<?php

namespace App\Listeners;

use App\Services\OtpService;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class HandleUserLogin
{
    protected OtpService $otpService;

    /**
     * Create the event listener.
     */
    public function __construct(OtpService $otpService)
    {
      
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;
        
        
    }
}