<?php

namespace App\Jobs;

use App\Mail\CreditInfoUnsubscribeNotification;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendCreditInfoUnsubscribeEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 60;

    public function __construct(
        public User $user,
        public string $serviceName
    ) {}

    public function handle(): void
    {
        try {
            Mail::to($this->user->email)->send(
                new CreditInfoUnsubscribeNotification(
                    $this->user,
                    $this->serviceName,
                    now()->format('d M Y H:i'),
                )
            );
        } catch (\Throwable $e) {
            Log::error('SendCreditInfoUnsubscribeEmailJob failed', [
                'user_id' => $this->user->id,
                'service_name' => $this->serviceName,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
