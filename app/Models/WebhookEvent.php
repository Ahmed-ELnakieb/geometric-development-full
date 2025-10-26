<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebhookEvent extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_type',
        'payload',
        'signature',
        'processed',
        'processed_at',
        'error_message',
        'retry_count'
    ];

    protected $casts = [
        'payload' => 'array',
        'processed' => 'boolean',
        'processed_at' => 'datetime',
    ];

    public function scopeUnprocessed($query)
    {
        return $query->where('processed', false);
    }

    public function scopeProcessed($query)
    {
        return $query->where('processed', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('event_type', $type);
    }

    public function scopeFailed($query)
    {
        return $query->where('processed', false)
                    ->whereNotNull('error_message');
    }

    public function markAsProcessed(): void
    {
        $this->update([
            'processed' => true,
            'processed_at' => now(),
            'error_message' => null
        ]);
    }

    public function markAsFailed(string $errorMessage): void
    {
        $this->update([
            'processed' => false,
            'error_message' => $errorMessage,
            'retry_count' => $this->retry_count + 1
        ]);
    }

    public function canRetry(): bool
    {
        return $this->retry_count < 5;
    }

    public function isMessage(): bool
    {
        return $this->event_type === 'message';
    }

    public function isStatus(): bool
    {
        return $this->event_type === 'status';
    }
}
