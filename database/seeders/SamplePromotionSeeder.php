<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Promotion;
use App\Models\User;
use App\Notifications\PromotionNotification;
use Illuminate\Support\Facades\DB;

class SamplePromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first super admin user or create a system user
        $admin = User::where('role', 'super_admin')->first();
        
        if (!$admin) {
            $this->command->warn('No super admin user found. Please create an admin user first.');
            return;
        }

        // Create a sample promotion for admins
        $promotion = Promotion::create([
            'title' => 'Welcome to Fanikisha Market place Admin Panel!',
            'message' => 'You now have access to the new promotion management system. You can create and send targeted promotions to users, lenders, and borrowers. Explore the new features and start engaging with your users!',
            'target_audience' => [
                'roles' => ['super_admin'],
                'new_customers' => false,
                'no_loans' => false,
                'incomplete_registration' => false,
            ],
            'send_email' => false, // Only send in-app notification for sample
            'send_notification' => true,
            'status' => 'sent',
            'sent_at' => now(),
            'total_recipients' => 0,
            'emails_sent' => 0,
            'notifications_sent' => 0,
            'emails_failed' => 0,
            'notifications_failed' => 0,
            'created_by' => $admin->id,
        ]);

        // Get all super admin users
        $admins = User::where('role', 'super_admin')->where('is_active', true)->get();
        $totalRecipients = $admins->count();
        
        $notificationsSent = 0;
        $notificationsFailed = 0;

        // Send notification to each admin
        foreach ($admins as $adminUser) {
            try {
                $adminUser->notify(new PromotionNotification($promotion));
                $notificationsSent++;
            } catch (\Exception $e) {
                $notificationsFailed++;
                \Log::error('Failed to send sample promotion notification', [
                    'user_id' => $adminUser->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Update promotion statistics
        $promotion->update([
            'total_recipients' => $totalRecipients,
            'notifications_sent' => $notificationsSent,
            'notifications_failed' => $notificationsFailed,
        ]);

        $this->command->info("Sample promotion created and sent to {$notificationsSent} admin user(s)!");
    }
}

