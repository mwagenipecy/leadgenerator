<?php

namespace App\Listeners;

use App\Jobs\FetchCreditScore;
use App\Services\OtpService;
use App\Services\LogService;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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
        
        // Log user login
        LogService::logLogin($user);

        // Check if credit score needs to be fetched (for individual users only)
        if ($user->registration_type === 'individual' && !empty($user->nida_number)) {
            $shouldFetch = false;

            // Check if it's first login (no credit score yet)
            if ($user->credit_score === null) {
                $shouldFetch = true;
                Log::info('HandleUserLogin: First login - will fetch credit score', ['user_id' => $user->id]);
            }
            // Check if last update was more than 14 days ago
            elseif ($user->credit_score_updated_at === null || 
                    Carbon::parse($user->credit_score_updated_at)->addDays(14)->isPast()) {
                $shouldFetch = true;
                Log::info('HandleUserLogin: Credit score is older than 14 days - will fetch', [
                    'user_id' => $user->id,
                    'last_updated' => $user->credit_score_updated_at,
                ]);
            }

            if ($shouldFetch) {
                // Dispatch job to fetch credit score
                FetchCreditScore::dispatch($user->id);
                Log::info('HandleUserLogin: Dispatched FetchCreditScore job', ['user_id' => $user->id]);
            }
        }
    }
}