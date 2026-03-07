<?php

namespace Database\Seeders;

use App\Models\User;
use App\Notifications\CreditInfoAlertNotification;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreditInfoAlertNotificationSeeder extends Seeder
{
    /**
     * Populate sample Notifications & activities for the CreditInfo Alert page.
     * Skips if any creditinfo_alert notifications already exist (safe for CD re-runs).
     */
    public function run(): void
    {
        if (DB::table('notifications')->where('type', CreditInfoAlertNotification::class)->exists()) {
            $this->command->info('CreditInfo alert notifications already exist. Skipping...');
            return;
        }

        $users = User::limit(5)->get();
        if ($users->isEmpty()) {
            return;
        }

        $alertTypes = [
            [
                'alert_type' => 'alert_when_credit_info_searched',
                'title' => 'Credit info search alert',
                'message' => 'A credit information search was performed for your profile.',
                'link' => '/credit-report',
            ],
            [
                'alert_type' => 'alert_when_score_changed',
                'title' => 'Score change alert',
                'message' => 'Your credit score has been updated. Check your report for details.',
                'link' => '/credit-report',
            ],
            [
                'alert_type' => 'alert_when_report_retrieved',
                'title' => 'Report retrieved',
                'message' => 'Your credit report was retrieved by a lender.',
                'link' => '/credit-report',
            ],
            [
                'alert_type' => 'alert_when_lender_can_find_loan',
                'title' => 'Loan match found',
                'message' => 'A lender can match you to a loan based on your submitted data.',
                'link' => '/dashboard',
            ],
            [
                'alert_type' => 'alert_when_reach_visible_notify_email',
                'title' => 'Reach visibility updated',
                'message' => 'Your reach/visibility has been updated. You have been notified via email.',
                'link' => '/dashboard',
            ],
        ];

        $now = Carbon::now();
        $inserted = 0;

        foreach ($users as $user) {
            $count = random_int(2, 5);
            for ($i = 0; $i < $count; $i++) {
                $alert = $alertTypes[array_rand($alertTypes)];
                $createdAt = $now->copy()->subDays(rand(0, 7))->subHours(rand(0, 23));
                $readAt = (rand(0, 1)) ? $createdAt->copy()->addMinutes(rand(5, 60)) : null;

                $data = [
                    'title' => $alert['title'],
                    'message' => $alert['message'],
                    'type' => 'creditinfo_alert',
                    'alert_type' => $alert['alert_type'],
                    'link' => url($alert['link']),
                ];

                DB::table('notifications')->insert([
                    'id' => Str::uuid()->toString(),
                    'type' => CreditInfoAlertNotification::class,
                    'notifiable_type' => User::class,
                    'notifiable_id' => $user->id,
                    'data' => json_encode($data),
                    'read_at' => $readAt,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
                $inserted++;
            }
        }

        $this->command->info("CreditInfo alert notifications: {$inserted} sample notifications created.");
    }
}
