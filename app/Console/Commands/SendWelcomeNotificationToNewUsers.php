<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Notifications\WelcomeNotification;
use Carbon\Carbon;

class SendWelcomeNotificationToNewUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:notify-new-users 
                            {--days=7 : Number of days to look back for new users}
                            {--email= : Send to specific user email}
                            {--all : Send to all users (use with caution)}
                            {--count= : Send to specific number of users}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send welcome notifications to new users for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $email = $this->option('email');
        $all = $this->option('all');
        $count = $this->option('count');

        $this->info('🚀 Testing Welcome Notification System');
        $this->newLine();

        // Get users based on options
        $users = $this->getUsers($days, $email, $all, $count);

        if ($users->isEmpty()) {
            $this->warn('No users found matching the criteria.');
            return 1;
        }

        $this->info("Found {$users->count()} user(s) to notify:");
        $this->newLine();

        // Display users table
        $tableData = $users->map(function ($user) {
            return [
                'ID' => $user->id,
                'Name' => $user->name ?? ($user->first_name . ' ' . $user->last_name),
                'Email' => $user->email,
                'Role' => $user->role,
                'Created' => $user->created_at->format('Y-m-d H:i'),
            ];
        })->toArray();

        $this->table(['ID', 'Name', 'Email', 'Role', 'Created'], $tableData);
        $this->newLine();

        // Confirm before sending
        if (!$this->confirm('Do you want to send welcome notifications to these users?', true)) {
            $this->info('Cancelled.');
            return 0;
        }

        $this->newLine();
        $this->info('Sending notifications...');
        $this->newLine();

        $successCount = 0;
        $failCount = 0;
        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        foreach ($users as $user) {
            try {
                // Send notification
                $userName = $user->name ?? ($user->first_name . ' ' . $user->last_name) ?? 'there';
                $user->notify(new WelcomeNotification(
                    'Welcome to Fanikisha Marketplace!',
                    "Hello {$userName}! Welcome to our platform. Complete your profile to get started with loan applications and explore our services.",
                    url('/application/profile')
                ));

                $successCount++;
                $bar->advance();
            } catch (\Exception $e) {
                $failCount++;
                $this->newLine();
                $this->error("Failed to send notification to {$user->email}: " . $e->getMessage());
                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine(2);

        // Summary
        $this->info("✅ Successfully sent: {$successCount}");
        if ($failCount > 0) {
            $this->warn("❌ Failed: {$failCount}");
        }

        $this->newLine();
        $this->info('📬 Notifications have been queued. Users will see them in their notification dropdown.');
        $this->info('💡 Tip: Check the notification bell icon in the top navigation bar.');

        return 0;
    }

    /**
     * Get users based on provided options
     */
    private function getUsers($days, $email, $all, $count)
    {
        $query = User::query();

        // Specific email
        if ($email) {
            return User::where('email', $email)->get();
        }

        // All users
        if ($all) {
            $query = User::query();
        } else {
            // New users within specified days
            $dateThreshold = Carbon::now()->subDays($days);
            $query = User::where('created_at', '>=', $dateThreshold);
        }

        // Limit count if specified
        if ($count) {
            $query->limit((int) $count);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }
}

