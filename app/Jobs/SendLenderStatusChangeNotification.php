<?php

namespace App\Jobs;

use App\Models\Lender;
use App\Models\User;
use App\Models\SystemSetting;
use App\Mail\LenderStatusChangeNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendLenderStatusChangeNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Lender $lender,
        public bool $isDisabled
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Get all users associated with this lender
            $lenderUsers = User::where('lender_id', $this->lender->id)->get();
            
            // Send email to all lender users
            foreach ($lenderUsers as $user) {
                try {
                    Mail::to($user->email)->send(
                        new LenderStatusChangeNotification($this->lender, $this->isDisabled, $user)
                    );
                } catch (\Exception $e) {
                    Log::error('Failed to send lender status change email to user', [
                        'user_id' => $user->id,
                        'user_email' => $user->email,
                        'lender_id' => $this->lender->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Get system admin email from settings or use default
            $adminEmail = SystemSetting::where('key', 'notification_email')->value('value');
            
            if (!$adminEmail) {
                // Fallback to admin email from env or default
                $adminEmail = env('ADMIN_EMAIL', 'admin@leadgenerator.com');
            }

            // Also get admin users
            $adminUsers = User::where('role', 'admin')
                ->orWhere('role', 'super_admin')
                ->where('is_active', true)
                ->get();

            // Send email to system admin(s)
            foreach ($adminUsers as $admin) {
                try {
                    Mail::to($admin->email)->send(
                        new LenderStatusChangeNotification($this->lender, $this->isDisabled, null, true)
                    );
                } catch (\Exception $e) {
                    Log::error('Failed to send lender status change email to admin', [
                        'admin_id' => $admin->id,
                        'admin_email' => $admin->email,
                        'lender_id' => $this->lender->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Also send to the notification email if it's different
            if ($adminEmail && !$adminUsers->pluck('email')->contains($adminEmail)) {
                try {
                    Mail::to($adminEmail)->send(
                        new LenderStatusChangeNotification($this->lender, $this->isDisabled, null, true)
                    );
                } catch (\Exception $e) {
                    Log::error('Failed to send lender status change email to notification email', [
                        'notification_email' => $adminEmail,
                        'lender_id' => $this->lender->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

        } catch (\Exception $e) {
            Log::error('Failed to send lender status change notifications', [
                'lender_id' => $this->lender->id,
                'is_disabled' => $this->isDisabled,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}

