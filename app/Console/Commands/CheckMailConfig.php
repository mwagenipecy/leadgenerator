<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class CheckMailConfig extends Command
{
    protected $signature = 'mail:check-config';
    protected $description = 'Check mail configuration';

    public function handle()
    {
        $this->info('Checking Mail Configuration...');
        $this->newLine();

        $defaultMailer = config('mail.default');
        $this->info("Default Mailer: {$defaultMailer}");

        if ($defaultMailer !== 'outlook') {
            $this->error("⚠️  WARNING: Default mailer is set to '{$defaultMailer}' instead of 'outlook'");
            $this->warn("Please set MAIL_MAILER=outlook in your .env file");
        } else {
            $this->info("✓ Default mailer is correctly set to 'outlook'");
        }

        $this->newLine();
        $this->info('Outlook Configuration:');
        
        $clientId = config('services.outlook.client_id');
        $clientSecret = config('services.outlook.client_secret');
        $tenantId = config('services.outlook.tenant_id');
        $sharedMailbox = config('services.outlook.shared_mailbox');
        $fromEmail = config('services.outlook.from_email');

        $this->info("Client ID: " . ($clientId ? '✓ Set' : '✗ Missing'));
        $this->info("Client Secret: " . ($clientSecret ? '✓ Set' : '✗ Missing'));
        $this->info("Tenant ID: " . ($tenantId ? '✓ Set' : '✗ Missing'));
        $this->info("Shared Mailbox: " . ($sharedMailbox ? $sharedMailbox : '✗ Missing'));
        $this->info("From Email: " . ($fromEmail ? $fromEmail : '✗ Missing'));

        if (empty($clientId) || empty($clientSecret) || empty($tenantId) || empty($sharedMailbox) || empty($fromEmail)) {
            $this->newLine();
            $this->error('⚠️  Outlook configuration is incomplete!');
            $this->warn('Please ensure all Outlook environment variables are set in your .env file:');
            $this->line('  - OUTLOOK_CLIENT_ID');
            $this->line('  - OUTLOOK_CLIENT_SECRET');
            $this->line('  - TENANT_ID');
            $this->line('  - SHARED_MAILBOX');
            $this->line('  - FROM_EMAIL');
            return 1;
        }

        $this->newLine();
        $this->info('✓ All Outlook configuration is set correctly!');
        return 0;
    }
}

