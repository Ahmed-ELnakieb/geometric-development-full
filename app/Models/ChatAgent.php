<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatAgent extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'status',
        'max_concurrent_chats',
        'auto_assign',
        'last_activity_at'
    ];

    protected $casts = [
        'last_activity_at' => 'datetime',
        'auto_assign' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class, 'agent_id', 'user_id');
    }

    public function activeConversations(): HasMany
    {
        return $this->conversations()->where('status', 'active');
    }

    public function scopeOnline($query)
    {
        return $query->where('status', 'online');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'online')
                    ->where('auto_assign', true);
    }

    public function isOnline(): bool
    {
        return $this->status === 'online';
    }

    public function isAway(): bool
    {
        return $this->status === 'away';
    }

    public function isOffline(): bool
    {
        return $this->status === 'offline';
    }

    public function canTakeNewChat(): bool
    {
        return $this->isOnline() && 
               $this->auto_assign && 
               $this->activeConversations()->count() < $this->max_concurrent_chats;
    }

    public function updateActivity(): void
    {
        $this->update(['last_activity_at' => now()]);
    }
}
