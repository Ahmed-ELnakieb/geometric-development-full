<?php

namespace App\Services;

use App\Exceptions\WhatsAppApiException;
use Exception;
use Illuminate\Support\Facades\Log;

class ApiErrorHandler
{
    /**
     * Handle WhatsApp API errors with appropriate logging and response
     */
    public function handleApiError(WhatsAppApiException $e): void
    {
        $context = [
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'context' => $e->getContext(),
            'should_retry' => $e->shouldRetry(),
            'is_rate_limited' => $e->isRateLimited(),
            'is_auth_error' => $e->isAuthenticationError(),
        ];

        if ($e->isAuthenticationError()) {
            Log::critical('WhatsApp API authentication error', $context);
            // Could trigger admin notification here
        } elseif ($e->isRateLimited()) {
            Log::warning('WhatsApp API rate limited', $context);
        } elseif ($e->isServerError()) {
            Log::error('WhatsApp API server error', $context);
        } else {
            Log::error('WhatsApp API error', $context);
        }
    }

    /**
     * Retry operation with exponential backoff
     */
    public function retryWithBackoff(callable $operation, int $maxRetries = 3): mixed
    {
        $attempt = 0;
        $lastException = null;

        while ($attempt < $maxRetries) {
            try {
                return $operation();
            } catch (WhatsAppApiException $e) {
                $lastException = $e;
                
                if (!$e->shouldRetry() || $attempt === $maxRetries - 1) {
                    $this->handleApiError($e);
                    throw $e;
                }

                $delay = $this->calculateBackoffDelay($attempt);
                
                Log::info('Retrying WhatsApp API operation', [
                    'attempt' => $attempt + 1,
                    'max_retries' => $maxRetries,
                    'delay_seconds' => $delay,
                    'error' => $e->getMessage()
                ]);

                sleep($delay);
                $attempt++;
            }
        }

        if ($lastException) {
            throw $lastException;
        }

        throw new WhatsAppApiException('Max retries exceeded without success', 500);
    }

    /**
     * Calculate exponential backoff delay
     */
    private function calculateBackoffDelay(int $attempt): int
    {
        // Base delay of 1 second, exponentially increased
        $baseDelay = 1;
        $maxDelay = 60; // Maximum 60 seconds
        
        $delay = $baseDelay * pow(2, $attempt);
        
        // Add jitter to prevent thundering herd
        $jitter = rand(0, 1000) / 1000; // 0-1 second
        
        return min($maxDelay, $delay + $jitter);
    }

    /**
     * Log API error with context
     */
    public function logApiError(Exception $e, array $context = []): void
    {
        $logContext = array_merge([
            'exception' => get_class($e),
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ], $context);

        if ($e instanceof WhatsAppApiException) {
            $logContext['whatsapp_context'] = $e->getContext();
            $logContext['should_retry'] = $e->shouldRetry();
        }

        Log::error('API Error occurred', $logContext);
    }

    /**
     * Check if error is recoverable
     */
    public function isRecoverableError(Exception $e): bool
    {
        if ($e instanceof WhatsAppApiException) {
            return $e->shouldRetry();
        }

        // Network timeouts and connection errors are generally recoverable
        return str_contains($e->getMessage(), 'timeout') ||
               str_contains($e->getMessage(), 'connection') ||
               str_contains($e->getMessage(), 'network');
    }

    /**
     * Get user-friendly error message
     */
    public function getUserFriendlyMessage(Exception $e): string
    {
        if ($e instanceof WhatsAppApiException) {
            if ($e->isAuthenticationError()) {
                return 'WhatsApp service is temporarily unavailable. Please try again later.';
            }
            
            if ($e->isRateLimited()) {
                return 'Too many messages sent. Please wait a moment before trying again.';
            }
            
            if ($e->isServerError()) {
                return 'WhatsApp service is experiencing issues. Please try again in a few minutes.';
            }
        }

        return 'Unable to send message at this time. Please try again later.';
    }
}