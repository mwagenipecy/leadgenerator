<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Promotion;
use App\Notifications\PromotionNotification;
use Illuminate\Support\Facades\Mail;

class SendTestEmailPromotion extends Command
{
    protected $signature = 'promotion:test-email {email?}';
    protected $description = 'Send a test email promotion to a user';

    public function handle()
    {
        $email = $this->argument('email');
        
        if ($email) {
            $user = User::where('email', $email)->first();
        } else {
            $user = User::where('role', 'super_admin')->first();
        }
        
        if (!$user) {
            $this->error('User not found');
            return 1;
        }
        
        $this->info("Creating test email promotion for: {$user->email}");
        
        // Create a test promotion with email enabled
        $promotion = Promotion::create([
            'title' => 'Test Email Promotion',
            'message' => 'This is a test email promotion to verify the email sending functionality is working correctly.',
            'target_audience' => ['roles' => []],
            'send_email' => true,
            'send_notification' => false, // Only email for this test
            'status' => 'draft',
            'total_recipients' => 1,
            'created_by' => $user->id,
        ]);
        
        try {
            $this->info("Sending email promotion...");
            
            // Send notification (which will queue the email)
            $user->notify(new PromotionNotification($promotion));
            
            // Update promotion stats
            $promotion->update([
                'status' => 'sent',
                'sent_at' => now(),
                'emails_sent' => 1,
            ]);
            
            $this->info('✓ Email promotion queued successfully!');
            $this->info('');
            $this->warn('IMPORTANT: Email is queued and will be sent when the queue worker processes it.');
            $this->info('To process the queue, run: php artisan queue:work');
            $this->info('Or to process once: php artisan queue:work --once');
            $this->info('');
            $this->info("Check the email at: {$user->email}");
            
        } catch (\Exception $e) {
            $this->error('Failed to send email promotion: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            
            // Update promotion with error
            $promotion->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            
            return 1;
        }
        
        return 0;
    }
}

