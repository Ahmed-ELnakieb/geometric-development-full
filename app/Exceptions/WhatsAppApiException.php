<?php

namespace App\Exceptions;

use Exception;

class WhatsAppApiException extends Exception
{
    private array $context;

    public function __construct(string $message, int $code = 0, array $context = [], Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function shouldRetry(): bool
    {
        // Retry on rate limiting, temporary failures, and network issues
        return in_array($this->code, [429, 500, 502, 503, 504]);
    }

    public function isRateLimited(): bool
    {
        return $this->code === 429;
    }

    public function isTemporaryFailure(): bool
    {
        return in_array($this->code, [500, 502, 503, 504]);
    }

    public function isAuthenticationError(): bool
    {
        return in_array($this->code, [401, 403]);
    }

    public function isClientError(): bool
    {
        return $this->code >= 400 && $this->code < 500;
    }

    public function isServerError(): bool
    {
        return $this->code >= 500;
    }
}