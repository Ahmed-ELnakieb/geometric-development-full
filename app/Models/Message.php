<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\Activitylog\LogOptions;
// use Spatie\Activitylog\// LogsActivity;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'name',
        'email',
        'phone',
        'user_type',
        'subject',
        'message',
        'status',
        'replied_at',
        'reply_message',
        'messageable_type',
        'messageable_id',
        'source_url',
        'source_page',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

    public function messageable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeRead($query)
    {
        return $query->where('status', 'read');
    }

    public function scopeReplied($query)
    {
        return $query->where('status', 'replied');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logOnly([])
    //         ->logOnlyDirty();
    // }

    public function isNew(): bool
    {
        return $this->status === 'new';
    }

    public function isRead(): bool
    {
        return $this->status === 'read';
    }

    public function isReplied(): bool
    {
        return $this->status === 'replied';
    }

    public function isArchived(): bool
    {
        return $this->status === 'archived';
    }

    public function markAsRead(): void
    {
        if ($this->isNew()) {
            $this->update(['status' => 'read']);
        }
    }

    public function reply(string $replyMessage): void
    {
        $this->update([
            'status' => 'replied',
            'reply_message' => $replyMessage,
            'replied_at' => now(),
        ]);
    }

    public function archive(): void
    {
        $this->update(['status' => 'archived']);
    }

    public function getFormattedTypeAttribute(): string
    {
        return match($this->type) {
            'contact' => 'Contact Form',
            'project_inquiry' => 'Project Inquiry',
            'broker_registration' => 'Broker Registration',
            'general' => 'General Inquiry',
            default => ucfirst($this->type),
        };
    }

    public function getSourcePageDisplayAttribute(): string
    {
        return match($this->source_page) {
            'home' => 'Homepage',
            'contact' => 'Contact Page',
            'project_details' => 'Project Details',
            'about' => 'About Page',
            'careers' => 'Careers Page',
            default => ucfirst($this->source_page ?? 'Unknown'),
        };
    }
}
