<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\Activitylog\LogOptions;
// use Spatie\Activitylog\// LogsActivity;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CareerApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'career_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'cover_letter',
        'cv_file_id',
        'portfolio_url',
        'status',
        'notes',
        'source_url',
        'ip_address',
        'user_agent',
    ];

    public function career(): BelongsTo
    {
        return $this->belongsTo(Career::class);
    }

    public function cvFile(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'cv_file_id');
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
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

    public function isHired(): bool
    {
        return $this->status === 'hired';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
