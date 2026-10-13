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

    protected bool $fake;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.central_user_api.base_url'), '/');
        $this->timeout = (int) config('services.central_user_api.timeout', 15);
        $this->fake = (bool) config('services.central_user_api.fake', false);
    }

    public function verifyPhone(string $phoneNumber, string $channel = 'web'): array
    {
        if ($this->fake) {
            return $this->fakeResponse(['status' => 'success', 'message' => '[FAKE] OTP sent to user via sms']);
        }

        return $this->post('/api/v1/users/verify-phone', [
            'phoneNumber' => $phoneNumber,
            'channel' => $channel,
        ]);
    }

    /**
     * The purpose to send on the subsequent verifyOtp() call. verify-phone's
     * success body distinguishes a brand-new phone ("OTP sent to user via
     * sms") from one that already has a CIT_APP account ("Phone number
     * already registered, OTP sent to user via sms") purely via `message` —
     * same status/shape either way. nextStep from verifyOtp (NEW_USER/LOGIN/
     * ONBOARD_CHANNEL/BLOCKED) remains the source of truth for how
     * CentralPortal branches afterwards; this only decides which `purpose`
     * value to send on that call.
     */
    public function resolveOtpPurpose(array $verifyPhoneResult): string
    {
        $message = $verifyPhoneResult['body']['message'] ?? '';

        return str_contains(strtolower($message), 'already registered')
            ? 'login'
            : 'registration';
    }

    public function verifyOtp(string $identifier, string $otp, string $purpose = 'registration', string $channel = 'web', string $identifierType = 'phone'): array
    {
        if ($this->fake) {
            return $this->fakeResponse([
                'status' => 'success',
                'message' => '[FAKE] OTP verified successfully',
                'nextStep' => 'NEW_USER',
                'userId' => '[FAKE]user.'.$identifier,
                'phoneProofToken' => '[FAKE]phone-proof.'.$identifier,
            ]);
        }

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
        if ($this->fake) {
            return $this->fakeResponse([
                'status' => 'success',
                'rqCode' => '[FAKE]rq.'.$nin,
                'question' => ['en' => "[FAKE] What is your mother's maiden name?", 'sw' => '[FAKE] Jina la ukoo la mama yako ni nani?'],
            ]);
        }

        return $this->post('/api/v1/users/verify-nin-qa', [
            'nin' => $nin,
            'channel' => $channel,
        ]);
    }

    public function submitNinQaAnswer(string $nin, string $rqCode, string $answer): array
    {
        if ($this->fake) {
            return $this->fakeResponse([
                'status' => 'success',
                'complete' => true,
                // Mirrors the real API, which returns this value already
                // "Bearer "-prefixed — see CentralUserApiClient::send(),
                // which strips any such prefix before re-adding its own.
                'preRegistrationToken' => 'Bearer [FAKE]pre-reg.'.$nin,
            ]);
        }

        return $this->post('/api/v1/users/verify-nin-qa/submit', [
            'nin' => $nin,
            'rqCode' => $rqCode,
            'answer' => $answer,
        ]);
    }

    public function register(string $phoneNumber, string $secret, string $phoneProofToken, string $preRegToken): array
    {
        if ($this->fake) {
            return $this->fakeAccountResponse($phoneNumber, '[FAKE] User registered and logged in successfully');
        }

        return $this->post('/api/v1/users/register', [
            'phoneNumber' => $phoneNumber,
            'secret' => $secret,
            'phoneProofToken' => $phoneProofToken,
        ], $preRegToken);
    }

    public function loginWeb(string $phoneNumber, string $password): array
    {
        if ($this->fake) {
            return $this->fakeAccountResponse($phoneNumber, '[FAKE] User successfully logged in');
        }

        return $this->post('/api/v1/users/login/web', [
            'phoneNumber' => $phoneNumber,
            'password' => $password,
        ]);
    }

    public function requestOnboardingOtp(string $phoneNumber): array
    {
        if ($this->fake) {
            return $this->fakeResponse(['status' => 'success', 'message' => '[FAKE] OTP sent to phone number.']);
        }

        return $this->post('/api/v1/credentials/request-onboarding-otp', [
            'phoneNumber' => $phoneNumber,
        ]);
    }

    public function onboardWebChannel(string $identifierValue, string $secret, string $bearerToken): array
    {
        if ($this->fake) {
            return $this->fakeResponse(['status' => 'success', 'message' => '[FAKE] Channel onboarded successfully.'], 201);
        }

        return $this->post('/api/v1/credentials/onboard-channel', [
            'channel' => 'web',
            'identifierValue' => $identifierValue,
            'secret' => $secret,
            'secretType' => 'password',
        ], $bearerToken);
    }

    /**
     * Adds an email to a central user account that doesn't have one
     * verified yet — the email isn't usable until verifyEmail() + the
     * generic verify-otp confirm step (identifierType "email") complete.
     * Returns ok=true with errorCode EMAIL_EXISTS if this exact email is
     * already registered to this same account (safe to retry/resume into
     * verifyEmail()); any other failure (e.g. taken by another account)
     * stays ok=false.
     */
    public function registerEmail(string $email, string $userId, string $bearerToken): array
    {
        if ($this->fake) {
            return $this->fakeResponse(['status' => 'success', 'message' => '[FAKE] Email successfully added to the user']);
        }

        return $this->post('/api/v1/users/register-email', [
            'email' => $email,
            'userId' => $userId,
        ], $bearerToken);
    }

    /**
     * Triggers CIT_APP to send an OTP to the given email. Confirming that
     * OTP is done via the generic verifyOtp() with identifierType "email",
     * not a dedicated endpoint — there is no separate "submit email otp"
     * request in the Postman collection.
     */
    public function verifyEmail(string $email, string $userId, string $bearerToken): array
    {
        if ($this->fake) {
            return $this->fakeResponse(['status' => 'success', 'message' => '[FAKE] Email verification code sent to user email']);
        }

        return $this->post('/api/v1/users/verify-email', [
            'email' => $email,
            'userId' => $userId,
        ], $bearerToken);
    }

    public function getProfile(string $bearerToken): array
    {
        if ($this->fake) {
            return $this->fakeResponse(['status' => 'success', 'nin' => null, 'phoneNumber' => null, 'firstName' => '[FAKE] Borrower']);
        }

        return $this->get('/api/v1/users/profile', $bearerToken);
    }

    /**
     * TEMPORARY — FAKE MODE: while the central user API isn't reachable, set
     * CENTRAL_USER_API_FAKE=true in .env so every method above returns a
     * canned successful ['ok'=>true,...] response instead of making a real
     * HTTP call, so the full registration/login journey can be clicked
     * through end-to-end in a real browser. Intentionally crude (any OTP/NIN
     * answer is accepted) — mirrors CitAppClient's fake mode in the CLA
     * project. Remove once a real base URL is in place; CENTRAL_USER_API_FAKE
     * should stay unset/false otherwise.
     */
    protected function fakeResponse(array $body, int $status = 200): array
    {
        return ['ok' => true, 'status' => $status, 'body' => $body];
    }

    protected function fakeAccountResponse(string $phoneNumber, string $message): array
    {
        $fakeId = 'fake-'.substr(md5($phoneNumber), 0, 16);

        return $this->fakeResponse([
            'status' => 'success',
            'message' => $message,
            'user' => [
                'id' => $fakeId,
                'phone_number' => $phoneNumber,
                'status' => 'active',
            ],
            'token' => 'Bearer [FAKE]token.'.$fakeId,
        ]);
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
