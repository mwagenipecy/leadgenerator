<?php

namespace App\Jobs;

use App\Models\CreditInfoRequest;
use App\Services\CreditInfoService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class RequestCreditReport implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $creditInfoRequestId
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(CreditInfoService $creditInfoService): void
    {
        try {
            $creditRequest = CreditInfoRequest::find($this->creditInfoRequestId);

            if (!$creditRequest) {
                Log::warning('RequestCreditReport: CreditInfoRequest not found', [
                    'credit_info_request_id' => $this->creditInfoRequestId,
                ]);
                return;
            }

            if (empty($creditRequest->national_id)) {
                Log::warning('RequestCreditReport: Missing NIDA number on credit request', [
                    'credit_info_request_id' => $this->creditInfoRequestId,
                    'loan_id' => $creditRequest->loan_id,
                ]);
                return;
            }

            // Prepare input for CreditInfoService based on the stored request data
            $firstName = $creditRequest->first_name ?? '';
            $lastName = $creditRequest->last_name ?? '';
            $fullName = $creditRequest->full_name ?? trim($firstName . ' ' . $lastName);
            $dateOfBirth = $creditRequest->date_of_birth ? $creditRequest->date_of_birth->format('Y-m-d') : null;
            $phoneNumber = $creditRequest->phone_number;

            $result = $creditInfoService->fetchCreditScore(
                $creditRequest->national_id,
                $firstName,
                $lastName,
                $fullName,
                $dateOfBirth,
                $phoneNumber
            );

            if ($result && ($result['success'] ?? false)) {
                $creditRequest->update([
                    'status' => 'success',
                    'response_payload' => $result,
                    'error_message' => null,
                    'responded_at' => now(),
                ]);

                Log::info('RequestCreditReport: Credit report fetched and stored', [
                    'credit_info_request_id' => $this->creditInfoRequestId,
                    'loan_id' => $creditRequest->loan_id,
                    'application_number' => $creditRequest->application_number,
                ]);
            } else {
                $creditRequest->update([
                    'status' => 'failed',
                    'error_message' => 'CreditInfoService did not return a valid response',
                    'responded_at' => now(),
                ]);

                Log::warning('RequestCreditReport: Credit report fetch returned no result', [
                    'credit_info_request_id' => $this->creditInfoRequestId,
                    'loan_id' => $creditRequest->loan_id,
                    'application_number' => $creditRequest->application_number,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('RequestCreditReport: Exception while requesting credit report', [
                'credit_info_request_id' => $this->creditInfoRequestId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if (isset($creditRequest)) {
                $creditRequest->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'responded_at' => now(),
                ]);
            }

            // Let the exception bubble up so the job can be retried
            throw $e;
        }
    }
}


