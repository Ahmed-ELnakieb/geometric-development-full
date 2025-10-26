<?php

namespace App\Traits;

use App\Models\Visit;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Visitable
{
    /**
     * Get all visits for this model
     */
    public function visits(): MorphMany
    {
        return $this->morphMany(Visit::class, 'visitable');
    }

    /**
     * Get total visit count
     */
    public function getVisitCountAttribute(): int
    {
        return $this->visits()->count();
    }

    /**
     * Get visit count for a specific date range
     */
    public function getVisitCountForDateRange($startDate, $endDate): int
    {
        return $this->visits()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }

    /**
     * Get unique visitor count
     */
    public function getUniqueVisitorCountAttribute(): int
    {
        return $this->visits()
            ->whereNotNull('visitor_id')
            ->distinct('visitor_id')
            ->count();
    }

    /**
     * Get recent visits
     */
    public function getRecentVisits($limit = 10)
    {
        return $this->visits()
            ->with('visitor')
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get visits by date
     */
    public function getVisitsByDate($date = null)
    {
        $date = $date ?? now()->toDateString();
        
        return $this->visits()
            ->whereDate('created_at', $date)
            ->get();
    }

    /**
     * Get visit statistics
     */
    public function getVisitStats(): array
    {
        $visits = $this->visits();
        
        return [
            'total_visits' => $visits->count(),
            'unique_visitors' => $visits->whereNotNull('visitor_id')->distinct('visitor_id')->count(),
            'frontend_visits' => $visits->where('is_admin', false)->count(),
            'admin_visits' => $visits->where('is_admin', true)->count(),
            'today_visits' => $visits->whereDate('created_at', now())->count(),
            'this_week_visits' => $visits->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
            'this_month_visits' => $visits->whereBetween('created_at', [
                now()->startOfMonth(),
                now()->endOfMonth()
            ])->count(),
        ];
    }

    /**
     * Get top referrers for this model
     */
    public function getTopReferrers($limit = 10): array
    {
        return $this->visits()
            ->whereNotNull('referer')
            ->selectRaw('referer, COUNT(*) as count')
            ->groupBy('referer')
            ->orderByDesc('count')
            ->limit($limit)
            ->pluck('count', 'referer')
            ->toArray();
    }

    /**
     * Record a visit for this model
     */
    public function recordVisit(array $data = []): Visit
    {
        return $this->visits()->create(array_merge([
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'referer' => request()->header('referer'),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'is_admin' => str_starts_with(request()->path(), 'admin') || str_starts_with(request()->path(), 'filament'),
            'visitor_type' => auth()->check() ? get_class(auth()->user()) : null,
            'visitor_id' => auth()->id(),
        ], $data));
    }
}