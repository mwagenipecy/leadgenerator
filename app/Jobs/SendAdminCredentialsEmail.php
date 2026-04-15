<?php

namespace App\Jobs;

use App\Mail\AdminAccountCreated;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendAdminCredentialsEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    public function __construct(
        public User $user,
        public string $password
    ) {
    }

    public function handle(): void
    {
        try {
            Mail::to($this->user->email)->send(
                new AdminAccountCreated($this->user, $this->password)
            );
        } catch (\Exception $e) {
            Log::error('Failed to send admin credentials email', [
                'user_id' => $this->user->id,
                'user_email' => $this->user->email,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
