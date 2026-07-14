<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\CreditInfoService;
use App\Services\LogService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FetchCreditScore implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $userId
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(CreditInfoService $creditInfoService): void
    {
        try {
            $user = User::find($this->userId);

            if (!$user) {
                Log::warning('FetchCreditScore: User not found', ['user_id' => $this->userId]);
                return;
            }

            // Only fetch for individual users with NIDA number
            if ($user->registration_type !== 'individual' || empty($user->nida_number)) {
                Log::info('FetchCreditScore: Skipping - not individual user or no NIDA number', [
                    'user_id' => $this->userId,
                    'registration_type' => $user->registration_type,
                    'has_nida' => !empty($user->nida_number),
                ]);
                return;
            }

            Log::info('FetchCreditScore: Starting credit score fetch', [
                'user_id' => $this->userId,
                'nida_number' => $user->nida_number,
            ]);

            // Prepare user data
            $firstName = $user->first_name ?? '';
            $lastName = $user->last_name ?? '';
            $fullName = $user->name ?? ($firstName . ' ' . $lastName);
            $dateOfBirth = $user->date_of_birth ? Carbon::parse($user->date_of_birth)->format('Y-m-d') : null;
            $phoneNumber = $user->phone;

            // Fetch credit score
            $result = $creditInfoService->fetchCreditScore(
                $user->nida_number,
                $firstName,
                $lastName,
                $fullName,
                $dateOfBirth,
                $phoneNumber
            );

            if ($result && isset($result['cip_score']) && $result['cip_score'] !== null) {
                // Update user with credit score
                $rating = $result['rating'] ?? $creditInfoService->getRatingLabel($result['cip_score']);

                $user->update([
                    'credit_score' => $result['cip_score'],
                    'credit_score_updated_at' => now(),
                    'credit_score_rating' => $rating,
                ]);

                Log::info('FetchCreditScore: Successfully updated credit score', [
                    'user_id' => $this->userId,
                    'credit_score' => $result['cip_score'],
                    'rating' => $rating,
                ]);

                // Log activity
                LogService::log(
                    'credit_score_fetched',
                    "Credit score fetched successfully: {$result['cip_score']} ({$rating})",
                    'medium',
                    $user,
                    null,
                    ['credit_score' => $result['cip_score'], 'rating' => $rating],
                    null,  
                );
            } else {
                Log::warning('FetchCreditScore: No credit score returned', [
                    'user_id' => $this->userId,
                    'result' => $result,
                ]);

                // Log failed attempt
                LogService::log(
                    'credit_score_fetch_failed',
                    $user,
                    null,
                    null,
                    null,
                    'medium',
                    'Credit score fetch completed but no score was returned'
                );
            }

        } catch (\Exception $e) {
            Log::error('FetchCreditScore: Exception occurred', [
                'user_id' => $this->userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Log error
            if (isset($user)) {
                LogService::log(
                    'credit_score_fetch_error',
                    $user,
                    null,
                    null,
                    ['error' => $e->getMessage()],
                    'high',
                    'Error fetching credit score: ' . $e->getMessage()
                );
            }

            throw $e; // Re-throw to trigger retry
        }
    }
}
