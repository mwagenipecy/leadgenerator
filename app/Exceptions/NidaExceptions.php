<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class NidaException extends Exception
{
    public function __construct(
        string $message,
        public readonly string $errorCode = 'NIDA_ERROR',
        public readonly int $httpStatus = 502,
        public readonly ?int $nidaStatusCode = null,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }
}