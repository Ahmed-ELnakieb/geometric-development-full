<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CircuitBreaker
{
    private string $serviceName;
    private int $failureThreshold;
    private int $recoveryTimeout;
    private int $expectedExceptionThreshold;

    public function __construct(
        string $serviceName,
        int $failureThreshold = 5,
        int $recoveryTimeout = 60,
        int $expectedExceptionThreshold = 10
    ) {
        $this->serviceName = $serviceName;
        $this->failureThreshold = $failureThreshold;
        $this->recoveryTimeout = $recoveryTimeout;
        $this->expectedExceptionThreshold = $expectedExceptionThreshold;
    }

    /**
     * Execute operation with circuit breaker protection
     */
    public function call(callable $operation): mixed
    {
        if ($this->isOpen()) {
            throw new \Exception("Circuit breaker is OPEN for service: {$this->serviceName}");
        }

        try {
            $result = $operation();
            $this->recordSuccess();
            return $result;
        } catch (\Exception $e) {
            $this->recordFailure();
            throw $e;
        }
    }

    /**
     * Check if circuit breaker is open
     */
    public function isOpen(): bool
    {
        $state = $this->getState();
        
        if ($state === 'open') {
            // Check if recovery timeout has passed
            $lastFailureTime = Cache::get($this->getLastFailureKey(), 0);
            if (time() - $lastFailureTime >= $this->recoveryTimeout) {
                $this->setState('half-open');
                return false;
            }
            return true;
        }

        return false;
    }

    /**
     * Check if circuit breaker is closed
     */
    public function isClosed(): bool
    {
        return $this->getState() === 'closed';
    }

    /**
     * Check if circuit breaker is half-open
     */
    public function isHalfOpen(): bool
    {
        return $this->getState() === 'half-open';
    }

    /**
     * Reset circuit breaker to closed state
     */
    public function reset(): void
    {
        $this->setState('closed');
        $this->resetCounters();
        
        Log::info("Circuit breaker reset for service: {$this->serviceName}");
    }

    /**
     * Force circuit breaker to open state
     */
    public function forceOpen(): void
    {
        $this->setState('open');
        Cache::put($this->getLastFailureKey(), time(), $this->recoveryTimeout);
        
        Log::warning("Circuit breaker forced OPEN for service: {$this->serviceName}");
    }

    /**
     * Get current circuit breaker statistics
     */
    public function getStats(): array
    {
        return [
            'service' => $this->serviceName,
            'state' => $this->getState(),
            'failure_count' => $this->getFailureCount(),
            'success_count' => $this->getSuccessCount(),
            'last_failure_time' => Cache::get($this->getLastFailureKey(), 0),
            'failure_threshold' => $this->failureThreshold,
            'recovery_timeout' => $this->recoveryTimeout,
        ];
    }

    /**
     * Record successful operation
     */
    private function recordSuccess(): void
    {
        $state = $this->getState();
        
        if ($state === 'half-open') {
            // If we're in half-open state and got a success, close the circuit
            $this->setState('closed');
            $this->resetCounters();
            Log::info("Circuit breaker closed after successful recovery for service: {$this->serviceName}");
        } else {
            // Reset failure count on success
            Cache::forget($this->getFailureCountKey());
            $this->incrementSuccessCount();
        }
    }

    /**
     * Record failed operation
     */
    private function recordFailure(): void
    {
        $failureCount = $this->incrementFailureCount();
        Cache::put($this->getLastFailureKey(), time(), $this->recoveryTimeout * 2);

        if ($failureCount >= $this->failureThreshold) {
            $this->setState('open');
            Log::error("Circuit breaker opened due to failures for service: {$this->serviceName}", [
                'failure_count' => $failureCount,
                'threshold' => $this->failureThreshold
            ]);
        }
    }

    /**
     * Get current state
     */
    private function getState(): string
    {
        return Cache::get($this->getStateKey(), 'closed');
    }

    /**
     * Set circuit breaker state
     */
    private function setState(string $state): void
    {
        Cache::put($this->getStateKey(), $state, $this->recoveryTimeout * 2);
    }

    /**
     * Get failure count
     */
    private function getFailureCount(): int
    {
        return Cache::get($this->getFailureCountKey(), 0);
    }

    /**
     * Increment failure count
     */
    private function incrementFailureCount(): int
    {
        $key = $this->getFailureCountKey();
        $count = Cache::get($key, 0) + 1;
        Cache::put($key, $count, $this->recoveryTimeout * 2);
        return $count;
    }

    /**
     * Get success count
     */
    private function getSuccessCount(): int
    {
        return Cache::get($this->getSuccessCountKey(), 0);
    }

    /**
     * Increment success count
     */
    private function incrementSuccessCount(): int
    {
        $key = $this->getSuccessCountKey();
        $count = Cache::get($key, 0) + 1;
        Cache::put($key, $count, 3600); // Keep for 1 hour
        return $count;
    }

    /**
     * Reset all counters
     */
    private function resetCounters(): void
    {
        Cache::forget($this->getFailureCountKey());
        Cache::forget($this->getSuccessCountKey());
        Cache::forget($this->getLastFailureKey());
    }

    /**
     * Get cache key for state
     */
    private function getStateKey(): string
    {
        return "circuit_breaker:{$this->serviceName}:state";
    }

    /**
     * Get cache key for failure count
     */
    private function getFailureCountKey(): string
    {
        return "circuit_breaker:{$this->serviceName}:failures";
    }

    /**
     * Get cache key for success count
     */
    private function getSuccessCountKey(): string
    {
        return "circuit_breaker:{$this->serviceName}:successes";
    }

    /**
     * Get cache key for last failure time
     */
    private function getLastFailureKey(): string
    {
        return "circuit_breaker:{$this->serviceName}:last_failure";
    }
}