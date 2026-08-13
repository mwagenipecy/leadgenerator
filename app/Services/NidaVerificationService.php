<?php

namespace App\Services;

use App\Exceptions\NidaException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class NidaVerificationService
{
    private const QUESTION_LOADED_STATUS = 120;
    private const FAILED_STATUS = 400;

    private ?string $cachedToken = null;
    private int $cachedTokenExpiresAt = 0;

    /**
     * Get a NIDA access token.
     *
     * The token is cached in memory for the configured TTL,
     * matching the behaviour of the original implementation.
     */
    public function getAccessToken(): string
    {
        if (
            $this->cachedToken !== null &&
            now()->timestamp < $this->cachedTokenExpiresAt
        ) {
            return $this->cachedToken;
        }

        try {
            $response = $this->http()
                ->post(config('services.nida.token_url'), [
                    'username' => config('services.nida.username'),
                    'password' => config('services.nida.password'),
                ]);

            $data = $response->json();

            if (!$response->successful()) {
                Log::channel('nida')->error('NIDA token request failed', [
                    'http_status' => $response->status(),
                    'response' => $this->safeResponseForLog($data),
                ]);

                throw new NidaException(
                    'Failed to generate NIDA access token',
                    'NIDA_TOKEN_ERROR',
                    502,
                );
            }

            if (empty($data['accessToken'])) {
                Log::channel('nida')->error('NIDA did not return an access token');

                throw new NidaException(
                    'NIDA did not return an access token',
                    'NIDA_TOKEN_ERROR',
                    502,
                );
            }

            $this->cachedToken = $data['accessToken'];

            $ttl = (int) config('services.nida.token_ttl', 30);

            $this->cachedTokenExpiresAt = now()
                ->addSeconds($ttl)
                ->timestamp;

            return $this->cachedToken;
        } catch (NidaException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::channel('nida')->error('NIDA token error', [
                'message' => $e->getMessage(),
            ]);

            throw new NidaException(
                'Failed to generate NIDA access token',
                'NIDA_TOKEN_ERROR',
                502,
                null,
                $e
            );
        }
    }

    /**
     * Start NIDA Knowledge-Based Verification.
     *
     * Returns the first question.
     *
     * @return array{
     *     type: string,
     *     transactionId: mixed,
     *     nin: string,
     *     rqCode: string,
     *     questionEn: string|null,
     *     questionSw: string|null
     * }
     */
    public function getVerificationQuestion(string $nin): array
    {
        try {
            $token = $this->getAccessToken();

            $response = $this->http()
                ->withToken($token)
                ->post(config('services.nida.verify_url'), [
                    'NIN' => $nin,
                ]);

            return $this->handleResponse(
                $response,
                [
                    'nin' => $nin,
                ]
            );
        } catch (NidaException $e) {
            throw $e;
        } catch (Throwable $e) {
            $this->handleTransportError(
                $e,
                [
                    'nin' => $nin,
                ]
            );
        }
    }

    /**
     * Submit an answer to a NIDA verification question.
     *
     * @return array
     */
    public function submitVerificationAnswer(
        string $nin,
        string $rqCode,
        string $answer
    ): array {
        try {
            $token = $this->getAccessToken();

            $response = $this->http()
                ->withToken($token)
                ->post(config('services.nida.verify_url'), [
                    'NIN' => $nin,
                    'RQCode' => $rqCode,
                    'QNANSW' => $answer,
                ]);

            return $this->handleResponse(
                $response,
                [
                    'nin' => $nin,
                    'rqCode' => $rqCode,
                ]
            );
        } catch (NidaException $e) {
            throw $e;
        } catch (Throwable $e) {
            $this->handleTransportError(
                $e,
                [
                    'nin' => $nin,
                    'rqCode' => $rqCode,
                ]
            );
        }
    }

    /**
     * Normalize a successful NIDA response.
     *
     * Possible results:
     *
     * NEXT_QUESTION
     * VERIFIED
     */
    private function handleResponse(
        Response $response,
        array $context
    ): array {
        $data = $response->json();

        /*
         * NIDA can sometimes return HTTP 200 while the body
         * contains statusCode = 400.
         */
        if (($data['statusCode'] ?? null) === self::FAILED_STATUS) {
            Log::channel('nida')->warning(
                'NIDA verification unsuccessful (200 wrapping failure body)',
                [
                    ...$context,
                    'statusCode' => $data['statusCode'],
                    'message' => $data['message'] ?? null,
                ]
            );

            throw new NidaException(
                $data['message'] ?? 'NIDA verification was not successful',
                'NIDA_VERIFICATION_FAILED',
                400,
                self::FAILED_STATUS
            );
        }

        /*
         * More questions remain.
         */
        if (isset($data['rqVerificationResult'])) {
            $verification = $data['rqVerificationResult'];

            $header = $verification['header'] ?? [];
            $nidaResponse = $verification['bodyResult']['nidaResponse'] ?? [];

            if (
                ($header['statusCode'] ?? null) !== self::QUESTION_LOADED_STATUS ||
                empty($nidaResponse['rqCode'])
            ) {
                Log::channel('nida')->warning(
                    'NIDA returned rqVerificationResult with unexpected header status',
                    [
                        ...$context,
                        'statusCode' => $header['statusCode'] ?? null,
                        'statusDescription' => $header['statusCodeDescription'] ?? null,
                    ]
                );

                throw new NidaException(
                    $header['statusCodeDescription']
                        ?? 'Unexpected NIDA response',
                    'NIDA_BAD_RESPONSE',
                    502,
                    $header['statusCode'] ?? null
                );
            }

            Log::channel('nida')->info('NIDA verification question received', [
                ...$context,
                'rqCode' => $nidaResponse['rqCode'],
            ]);

            return [
                'type' => 'NEXT_QUESTION',
                'transactionId' => $header['nidaTransactionID'] ?? null,
                'nin' => $nidaResponse['nin'] ?? $context['nin'] ?? null,
                'rqCode' => $nidaResponse['rqCode'],
                'questionEn' => $nidaResponse['en'] ?? null,
                'questionSw' => $nidaResponse['sw'] ?? null,
            ];
        }

        /*
         * Verification completed.
         *
         * NIDA returns a flat profile and the presence of
         * nationalIdNumber is used as the success signal.
         */
        if (!empty($data['nationalIdNumber'])) {
            Log::channel('nida')->info('NIDA verification succeeded', [
                ...$context,
            ]);

            return [
                'type' => 'VERIFIED',

                'profile' => [
                    'nin' => $data['nationalIdNumber'],
                    'firstName' => $data['firstName'] ?? null,
                    'middleName' => $data['middleName'] ?? null,
                    'lastName' => $data['lastName'] ?? null,
                    'otherName' => $data['otherName'] ?? null,
                    'dateOfBirth' => $data['dateOfBirth'] ?? null,
                    'sex' => $data['sex'] ?? null,
                    'nationality' => $data['nationality'] ?? null,
                    'placeOfBirth' => $data['placeOfBirth'] ?? null,
                    'residentRegion' => $data['residentRegion'] ?? null,
                    'residentDistrict' => $data['residentDistrict'] ?? null,
                    'residentWard' => $data['residentWard'] ?? null,
                    'residentVillage' => $data['residentVillage'] ?? null,
                    'residentStreet' => $data['residentStreet'] ?? null,
                    'residentPostalAddress' => $data['residentPostalAddress'] ?? null,
                    'residentPostCode' => $data['residentPostCode'] ?? null,
                    'birthCountry' => $data['birthCountry'] ?? null,
                    'birthRegion' => $data['birthRegion'] ?? null,
                    'birthDistrict' => $data['birthDistrict'] ?? null,
                    'birthWard' => $data['birthWard'] ?? null,
                    'photo' => $data['photo'] ?? null,
                    'signature' => $data['signature'] ?? null,
                ],
            ];
        }

        /*
         * Unknown response shape.
         *
         * Do NOT log the full NIDA response because it may contain
         * PII, photo and signature.
         */
        Log::channel('nida')->warning(
            'Unrecognized NIDA response shape',
            [
                ...$context,
                'statusCode' => $data['statusCode'] ?? null,
                'message' => $data['message'] ?? null,
                'fieldsPresent' => array_keys($data),
            ]
        );

        throw new NidaException(
            'Unexpected response from NIDA',
            'NIDA_BAD_RESPONSE',
            502
        );
    }

    /**
     * Handle HTTP/network errors.
     */
    private function handleTransportError(
        Throwable $exception,
        array $context
    ): never {
        if ($exception instanceof NidaException) {
            throw $exception;
        }

        $response = $exception instanceof \Illuminate\Http\Client\RequestException
            ? $exception->response
            : null;

        $body = $response?->json();

        /*
         * NIDA verification failure.
         *
         * This may be HTTP 400 or a response body containing
         * statusCode = 400.
         */
        if (
            $response?->status() === self::FAILED_STATUS ||
            ($body['statusCode'] ?? null) === self::FAILED_STATUS
        ) {
            Log::channel('nida')->warning(
                'NIDA verification unsuccessful',
                [
                    ...$context,
                    'statusCode' => $body['statusCode'] ?? $response?->status(),
                    'message' => $body['message'] ?? null,
                ]
            );

            throw new NidaException(
                $body['message'] ?? 'NIDA verification was not successful',
                'NIDA_VERIFICATION_FAILED',
                400,
                $body['statusCode'] ?? $response?->status(),
                $exception
            );
        }

        Log::channel('nida')->error(
            'NIDA request failed',
            [
                ...$context,
                'message' => $exception->getMessage(),
                'statusCode' => $response?->status(),
            ]
        );

        throw new NidaException(
            'Verification service unavailable, please try again',
            'NIDA_UNAVAILABLE',
            502,
            null,
            $exception
        );
    }

    /**
     * Build the Laravel HTTP client.
     */
    private function http()
    {
        return Http::timeout(
            (int) config('services.nida.timeout', 15)
        )
            ->withOptions([
                'verify' => (bool) config('services.nida.verify_ssl', false),
            ]);
    }

    /**
     * Return safe information for logging.
     */
    private function safeResponseForLog(mixed $data): mixed
    {
        if (!is_array($data)) {
            return null;
        }

        return [
            'fieldsPresent' => array_keys($data),
            'statusCode' => $data['statusCode'] ?? null,
            'message' => $data['message'] ?? null,
        ];
    }
}
