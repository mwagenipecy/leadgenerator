<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Promotion;
use App\Notifications\PromotionNotification;

class TestNotification extends Command
{
    protected $signature = 'test:notification {email?}';
    protected $description = 'Create a test notification for a user';

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
        
        $this->info("Creating test notification for: {$user->email}");
        
        // Ask if user wants email or notification
        $sendEmail = $this->confirm('Do you want to send email?', true);
        $sendNotification = $this->confirm('Do you want to send in-app notification?', true);
        
        if (!$sendEmail && !$sendNotification) {
            $this->error('You must select at least one delivery method (email or notification)');
            return 1;
        }
        
        // Create a test promotion
        $promotion = Promotion::create([
            'title' => 'Test Promotion - ' . ($sendEmail ? 'Email' : '') . ($sendEmail && $sendNotification ? ' + ' : '') . ($sendNotification ? 'Notification' : ''),
            'message' => 'This is a test promotion to verify the promotion system is working. ' . ($sendEmail ? 'You should receive an email.' : '') . ($sendNotification ? ' You should see an in-app notification.' : ''),
            'target_audience' => ['roles' => []],
            'send_email' => $sendEmail,
            'send_notification' => $sendNotification,
            'status' => 'sent',
            'sent_at' => now(),
            'total_recipients' => 1,
            'notifications_sent' => 0,
            'emails_sent' => 0,
            'created_by' => $user->id,
        ]);
        
        try {
            $this->info("Sending promotion to: {$user->email}");
            $this->info("Email: " . ($sendEmail ? 'Yes' : 'No'));
            $this->info("Notification: " . ($sendNotification ? 'Yes' : 'No'));
            
            $user->notify(new PromotionNotification($promotion));
            
            // Update promotion stats
            if ($sendEmail) {
                $promotion->update(['emails_sent' => 1]);
            }
            if ($sendNotification) {
                $promotion->update(['notifications_sent' => 1]);
            }
            
            $this->info('✓ Promotion sent successfully!');
            
            if ($sendNotification) {
                $this->info("Unread notifications: " . $user->unreadNotifications()->count());
            }
            
            if ($sendEmail) {
                $this->info("Note: Email is queued. Make sure queue worker is running: php artisan queue:work");
            }
            
        } catch (\Exception $e) {
            $this->error('Failed to send promotion: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return 1;
        }
        
        return 0;
    }
}

