<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Thin HTTP client for the centralized user API described in
 * "centralization of users/CIT_APP APIs v5.postman_collection.json".
 *
 * Every method returns ['ok' => bool, 'status' => int, 'body' => array]
 * instead of throwing, so callers can branch on API error codes
 * (e.g. INVALID_OTP, NIN_ALREADY_REGISTERED) to drive the onboarding
 * flow described in the Web_flow/Unified_user_flow diagrams.
 */
class CentralUserApiClient
{
    protected string $baseUrl;

    protected int $timeout;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.central_user_api.base_url'), '/');
        $this->timeout = (int) config('services.central_user_api.timeout', 15);
    }

    public function verifyPhone(string $phoneNumber, string $channel = 'web'): array
    {
        return $this->post('/api/v1/users/verify-phone', [
            'phoneNumber' => $phoneNumber,
            'channel' => $channel,
        ]);
    }

    public function verifyOtp(string $identifier, string $otp, string $purpose = 'registration', string $channel = 'web', string $identifierType = 'phone'): array
    {
        return $this->post('/api/v1/users/verify-otp', [
            'identifier' => $identifier,
            'identifierType' => $identifierType,
            'purpose' => $purpose,
            'otp' => $otp,
            'channel' => $channel,
        ]);
    }

    public function startNinQa(string $nin, string $channel = 'web'): array
    {
        return $this->post('/api/v1/users/verify-nin-qa', [
            'nin' => $nin,
            'channel' => $channel,
        ]);
    }

    public function submitNinQaAnswer(string $nin, string $rqCode, string $answer): array
    {
        return $this->post('/api/v1/users/verify-nin-qa/submit', [
            'nin' => $nin,
            'rqCode' => $rqCode,
            'answer' => $answer,
        ]);
    }

    public function register(string $phoneNumber, string $secret, string $phoneProofToken, string $preRegToken): array
    {
        return $this->post('/api/v1/users/register', [
            'phoneNumber' => $phoneNumber,
            'secret' => $secret,
            'phoneProofToken' => $phoneProofToken,
        ], $preRegToken);
    }

    public function loginWeb(string $phoneNumber, string $password): array
    {
        return $this->post('/api/v1/users/login/web', [
            'phoneNumber' => $phoneNumber,
            'password' => $password,
        ]);
    }

    public function requestOnboardingOtp(string $phoneNumber): array
    {
        return $this->post('/api/v1/credentials/request-onboarding-otp', [
            'phoneNumber' => $phoneNumber,
        ]);
    }

    public function onboardWebChannel(string $identifierValue, string $secret, string $bearerToken): array
    {
        return $this->post('/api/v1/credentials/onboard-channel', [
            'channel' => 'web',
            'identifierValue' => $identifierValue,
            'secret' => $secret,
            'secretType' => 'password',
        ], $bearerToken);
    }

    public function getProfile(string $bearerToken): array
    {
        return $this->get('/api/v1/users/profile', $bearerToken);
    }

    protected function post(string $path, array $payload, ?string $bearerToken = null): array
    {
        return $this->send('post', $path, $payload, $bearerToken);
    }

    protected function get(string $path, ?string $bearerToken = null): array
    {
        return $this->send('get', $path, [], $bearerToken);
    }

    protected function send(string $method, string $path, array $payload, ?string $bearerToken): array
    {
        if ($this->baseUrl === '') {
            Log::error('Central user API base URL is not configured.');

            return [
                'ok' => false,
                'status' => 503,
                'body' => [
                    'status' => 'failure',
                    'message' => 'The identity service is not configured. Please contact support.',
                    'errorCode' => 'CENTRAL_API_NOT_CONFIGURED',
                ],
            ];
        }

        $request = Http::baseUrl($this->baseUrl)
            ->acceptJson()
            ->timeout($this->timeout);

        if ($bearerToken) {
            $request = $request->withToken(preg_replace('/^Bearer\s+/i', '', $bearerToken));
        }

        $correlationId = (string) Str::uuid();
        $startedAt = microtime(true);

        Log::channel('central_user_api')->info('Central user API request', [
            'correlation_id' => $correlationId,
            'method' => strtoupper($method),
            'path' => $path,
            'payload' => $this->maskPayload($payload),
            'authenticated' => $bearerToken !== null,
        ]);

        try {
            $response = $method === 'get'
                ? $request->get($path)
                : $request->post($path, $payload);

            $body = (array) $response->json();

            Log::channel('central_user_api')->info('Central user API response', [
                'correlation_id' => $correlationId,
                'path' => $path,
                'status' => $response->status(),
                'ok' => $response->successful(),
                'duration_ms' => (int) round((microtime(true) - $startedAt) * 1000),
                'response_status' => $body['status'] ?? null,
                'error_code' => $body['errorCode'] ?? null,
                'request_id' => $body['requestId'] ?? null,
            ]);

            return [
                'ok' => $response->successful(),
                'status' => $response->status(),
                'body' => $body,
            ];
        } catch (\Throwable $e) {
            Log::channel('central_user_api')->error('Central user API request failed', [
                'correlation_id' => $correlationId,
                'path' => $path,
                'duration_ms' => (int) round((microtime(true) - $startedAt) * 1000),
                'message' => $e->getMessage(),
            ]);

            return [
                'ok' => false,
                'status' => 503,
                'body' => [
                    'status' => 'failure',
                    'message' => 'Unable to reach the identity service. Please try again.',
                    'errorCode' => 'CENTRAL_API_UNAVAILABLE',
                ],
            ];
        }
    }

    /**
     * Redact secrets/credentials before writing a payload to the log.
     */
    protected function maskPayload(array $payload): array
    {
        $sensitiveKeys = ['secret', 'password', 'otp', 'pin', 'answer', 'phoneProofToken'];

        foreach ($sensitiveKeys as $key) {
            if (array_key_exists($key, $payload)) {
                $payload[$key] = '***redacted***';
            }
        }

        return $payload;
    }
}
