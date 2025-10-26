<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VisitorSession extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'ip_address',
        'user_agent',
        'referrer',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'first_visit_at',
        'last_activity_at',
        'page_views',
        'metadata'
    ];

    protected $casts = [
        'first_visit_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'metadata' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::uuid();
            }
        });
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class, 'visitor_session_id');
    }

    public function updateActivity(): void
    {
        $this->update([
            'last_activity_at' => now(),
            'page_views' => $this->page_views + 1
        ]);
    }

    public static function createFromRequest($request): self
    {
        return static::create([
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->header('referer'),
            'utm_source' => $request->get('utm_source'),
            'utm_medium' => $request->get('utm_medium'),
            'utm_campaign' => $request->get('utm_campaign'),
            'first_visit_at' => now(),
            'last_activity_at' => now(),
            'page_views' => 1,
        ]);
    }

    public function getSourceAttribute(): string
    {
        if ($this->utm_source) {
            return $this->utm_source;
        }
        
        if ($this->referrer) {
            $domain = parse_url($this->referrer, PHP_URL_HOST);
            return $domain ?: 'referrer';
        }
        
        return 'direct';
    }
}
