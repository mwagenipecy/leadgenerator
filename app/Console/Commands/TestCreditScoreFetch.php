<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\CreditInfoService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestCreditScoreFetch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:credit-score {--user-id= : User ID to test with} {--nida= : NIDA number to test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test CreditInfo API to fetch credit score';

    /**
     * Execute the console command.
     */
    public function handle(CreditInfoService $creditInfoService)
    {
        $this->info('Testing CreditInfo API...');
        $this->newLine();

        // Get test data
        $userId = $this->option('user-id');
        $nidaNumber = $this->option('nida');

        $user = null;
        if ($userId) {
            $user = User::find($userId);
            if (!$user) {
                $this->error("User with ID {$userId} not found.");
                return 1;
            }
        } else {
            // Try to find a user with NIDA number
            $user = User::where('registration_type', 'individual')
                ->whereNotNull('nida_number')
                ->first();
            
            if (!$user) {
                $this->error('No user with NIDA number found. Please provide --user-id or --nida option.');
                return 1;
            }
        }

        // Override NIDA if provided
        if ($nidaNumber) {
            $testNida = $nidaNumber;
        } else {
            $testNida = $user->nida_number;
        }

        if (empty($testNida)) {
            $this->error('No NIDA number available for testing.');
            return 1;
        }

        $this->info("Testing with:");
        $this->line("  User ID: {$user->id}");
        $this->line("  Name: {$user->name}");
        $this->line("  NIDA Number: {$testNida}");
        $this->line("  Phone: " . ($user->phone ?? 'N/A'));
        $this->line("  Date of Birth: " . ($user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : 'N/A'));
        $this->newLine();

        // Prepare data
        $firstName = $user->first_name ?? 'Test';
        $lastName = $user->last_name ?? 'User';
        $fullName = $user->name ?? ($firstName . ' ' . $lastName);
        $dateOfBirth = $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : null;
        $phoneNumber = $user->phone;

        $this->info('Sending request to CreditInfo API...');
        $this->line("Endpoint: " . config('services.creditinfo.endpoint'));
        $this->newLine();

        try {
            // Fetch credit score
            $result = $creditInfoService->fetchCreditScore(
                $testNida,
                $firstName,
                $lastName,
                $fullName,
                $dateOfBirth,
                $phoneNumber
            );

            if ($result === null) {
                $this->error('Failed to fetch credit score. Check logs for details.');
                return 1;
            }

            $this->info('✓ Response received successfully!');
            $this->newLine();

            // Display results
            $this->table(
                ['Field', 'Value'],
                [
                    ['CIP Score', $result['cip_score'] ?? 'N/A'],
                    ['Rating', $result['rating'] ?? 'N/A'],
                    ['Has Raw Response', isset($result['raw_response']) ? 'Yes' : 'No'],
                ]
            );

            if (isset($result['cip_score']) && $result['cip_score'] !== null) {
                $rating = $result['rating'] ?? $creditInfoService->getRatingLabel($result['cip_score']);
                $this->newLine();
                $this->info("Credit Score: {$result['cip_score']}");
                $this->info("Rating: {$rating}");
                $this->newLine();
                $this->info('✓ Test completed successfully!');
            } else {
                $this->newLine();
                $this->warn('⚠ Credit score not found in response.');
                $this->line('This could mean:');
                $this->line('  - The NIDA number is not in the CreditInfo database');
                $this->line('  - The API response structure is different');
                $this->line('  - Check the logs for the full response');
            }

            // Show raw response structure (first level)
            if (isset($result['raw_response']) && is_array($result['raw_response'])) {
                $this->newLine();
                $this->line('Raw response structure (top level keys):');
                $this->line('  ' . implode(', ', array_keys($result['raw_response'])));
            }

            return 0;

        } catch (\Exception $e) {
            $this->error('Exception occurred: ' . $e->getMessage());
            $this->line('Stack trace:');
            $this->line($e->getTraceAsString());
            return 1;
        }
    }

    /**
     * Recursively search for score in response array
     */
    private function searchForScore($data, $depth = 0, $maxDepth = 5, $path = '')
    {
        if ($depth > $maxDepth || !is_array($data)) {
            return;
        }

        $scoreKeys = ['CIPScore', 'cip_score', 'CIP', 'Score', 'score', 'MobileScore'];
        
        foreach ($data as $key => $value) {
            $currentPath = $path ? "$path.$key" : $key;
            
            if (in_array($key, $scoreKeys) && (is_numeric($value) || (is_array($value) && isset($value['_value'])))) {
                $score = is_array($value) ? ($value['_value'] ?? $value[0] ?? null) : $value;
                if (is_numeric($score)) {
                    $this->line("  Found score at: $currentPath = $score");
                }
            }
            
            if (is_array($value) && $depth < $maxDepth) {
                $this->searchForScore($value, $depth + 1, $maxDepth, $currentPath);
            }
        }
    }
}
