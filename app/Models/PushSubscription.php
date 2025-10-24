<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PushSubscription extends Model
{
    protected $fillable = [
        'endpoint',
        'keys',
        'user_id',
        'user_agent',
        'last_used_at'
    ];

    protected $casts = [
        'keys' => 'array',
        'last_used_at' => 'datetime'
    ];

    /**
     * Get the user that owns the subscription
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Update the last used timestamp
     */
    public function updateLastUsed(): void
    {
        $this->update(['last_used_at' => now()]);
    }

    /**
     * Check if subscription is expired (not used in 30 days)
     */
    public function isExpired(): bool
    {
        if (!$this->last_used_at) {
            return false;
        }

        return $this->last_used_at->diffInDays(now()) > 30;
    }

    /**
     * Scope to get active subscriptions
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('last_used_at')
              ->orWhere('last_used_at', '>', now()->subDays(30));
        });
    }

    /**
     * Scope to get subscriptions for a specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get subscription data formatted for web-push library
     */
    public function getWebPushSubscription(): array
    {
        return [
            'endpoint' => $this->endpoint,
            'keys' => $this->keys
        ];
    }
}
