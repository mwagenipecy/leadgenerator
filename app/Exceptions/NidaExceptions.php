<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class NidaException extends Exception
{
    public const TOKEN_ERROR = 'NIDA_TOKEN_ERROR';
    public const VERIFICATION_FAILED = 'NIDA_VERIFICATION_FAILED';
    public const UNAVAILABLE = 'NIDA_UNAVAILABLE';
    public const TIMEOUT = 'NIDA_TIMEOUT';
    public const BAD_RESPONSE = 'NIDA_BAD_RESPONSE';

    /**
     * Application-level error code.
     */
    public string $errorCode;

    /**
     * HTTP status that your application should return.
     */
    public int $statusCode;

    /**
     * Raw status code returned by NIDA, if available.
     */
    public ?int $nidaStatusCode;

    /**
     * Whether the error is caused by NIDA/network availability.
     */
    public bool $isUnavailable;

    public function __construct(
        string $message,
        string $errorCode = self::UNAVAILABLE,
        int $statusCode = 502,
        ?int $nidaStatusCode = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);

        $this->errorCode = $errorCode;
        $this->statusCode = $statusCode;
        $this->nidaStatusCode = $nidaStatusCode;

        $this->isUnavailable = in_array(
            $errorCode,
            [
                self::UNAVAILABLE,
                self::TIMEOUT,
                self::TOKEN_ERROR,
            ],
            true
        );
    }

    /**
     * NIDA service is unavailable.
     */
    public static function unavailable(
        ?Throwable $previous = null
    ): self {
        return new self(
            'NIDA verification service is currently unavailable. Please try again later.',
            self::UNAVAILABLE,
            503,
            null,
            $previous
        );
    }

    /**
     * NIDA request timed out.
     */
    public static function timeout(
        ?Throwable $previous = null
    ): self {
        return new self(
            'NIDA verification service did not respond in time. Please try again.',
            self::TIMEOUT,
            504,
            null,
            $previous
        );
    }

    /**
     * Failed to obtain NIDA access token.
     */
    public static function tokenError(
        ?Throwable $previous = null
    ): self {
        return new self(
            'Unable to authenticate with the NIDA verification service.',
            self::TOKEN_ERROR,
            502,
            null,
            $previous
        );
    }

    /**
     * NIDA explicitly rejected verification.
     */
    public static function verificationFailed(
        string $message = 'NIDA verification was not successful.',
        ?int $nidaStatusCode = 400,
        ?Throwable $previous = null
    ): self {
        return new self(
            $message,
            self::VERIFICATION_FAILED,
            400,
            $nidaStatusCode,
            $previous
        );
    }

    /**
     * NIDA returned an unexpected response.
     */
    public static function badResponse(
        ?int $nidaStatusCode = null,
        ?Throwable $previous = null
    ): self {
        return new self(
            'Unexpected response received from NIDA.',
            self::BAD_RESPONSE,
            502,
            $nidaStatusCode,
            $previous
        );
    }
}