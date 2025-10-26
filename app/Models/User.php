<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Models\ActivityLog;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'is_developer',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
        'is_developer' => 'boolean',
        'password' => 'hashed',
    ];

    /**
     * Get the blog posts authored by the user.
     */
    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class, 'author_id');
    }

    /**
     * Get the activity logs caused by the user.
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'causer_id');
    }

    /**
     * Get the chat agent profile for this user.
     */
    public function chatAgent()
    {
        return $this->hasOne(ChatAgent::class);
    }

    /**
     * Get conversations assigned to this user as an agent.
     */
    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'agent_id');
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by role.
     */
    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Check if the user is a super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if the user can manage projects.
     */
    public function canManageProjects(): bool
    {
        return in_array($this->role, ['super_admin', 'content_manager', 'marketing_manager']);
    }

    /**
     * Check if the user can manage blog.
     */
    public function canManageBlog(): bool
    {
        return in_array($this->role, ['super_admin', 'content_manager']);
    }

    /**
     * Check if the user can manage careers.
     */
    public function canManageCareers(): bool
    {
        return in_array($this->role, ['super_admin', 'hr_manager']);
    }

    /**
     * Determine if user can access Filament admin panel
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->role, ['super_admin', 'content_manager']);
    }

    /**
     * Check if user is the developer account
     */
    public function isDeveloper(): bool
    {
        return $this->is_developer === true;
    }

    /**
     * Scope a query to exclude developer accounts
     */
    public function scopeNonDeveloper($query)
    {
        return $query->where('is_developer', false);
    }

    /**
     * Scope a query to get only developer account
     */
    public function scopeDeveloper($query)
    {
        return $query->where('is_developer', true);
    }
}
