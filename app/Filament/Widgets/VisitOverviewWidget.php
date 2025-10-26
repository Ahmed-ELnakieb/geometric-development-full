<?php

namespace App\Filament\Widgets;

use App\Models\Visit;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class VisitOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Get visit statistics
        $totalVisits = Visit::count();
        $todayVisits = Visit::whereDate('created_at', today())->count();
        $yesterdayVisits = Visit::whereDate('created_at', Carbon::yesterday())->count();
        $weekVisits = Visit::where('created_at', '>=', Carbon::now()->startOfWeek())->count();
        $monthVisits = Visit::where('created_at', '>=', Carbon::now()->startOfMonth())->count();
        
        $uniqueVisitors = Visit::whereNotNull('visitor_id')
            ->distinct('visitor_id')
            ->count();
            
        $frontendVisits = Visit::where('is_admin', false)->count();
        $adminVisits = Visit::where('is_admin', true)->count();

        // Calculate trends
        $todayTrend = $yesterdayVisits > 0 
            ? (($todayVisits - $yesterdayVisits) / $yesterdayVisits) * 100 
            : 0;

        $lastWeekVisits = Visit::whereBetween('created_at', [
            Carbon::now()->subWeek()->startOfWeek(),
            Carbon::now()->subWeek()->endOfWeek()
        ])->count();
        
        $weekTrend = $lastWeekVisits > 0 
            ? (($weekVisits - $lastWeekVisits) / $lastWeekVisits) * 100 
            : 0;

        return [
            Stat::make('Total Visits', number_format($totalVisits))
                ->description('All time visits')
                ->descriptionIcon('heroicon-m-eye')
                ->color('primary'),
                
            Stat::make('Today\'s Visits', number_format($todayVisits))
                ->description(
                    ($todayTrend >= 0 ? '+' : '') . number_format($todayTrend, 1) . '% from yesterday'
                )
                ->descriptionIcon($todayTrend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($todayTrend >= 0 ? 'success' : 'danger'),
                
            Stat::make('This Week', number_format($weekVisits))
                ->description(
                    ($weekTrend >= 0 ? '+' : '') . number_format($weekTrend, 1) . '% from last week'
                )
                ->descriptionIcon($weekTrend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($weekTrend >= 0 ? 'success' : 'danger'),
                
            Stat::make('Unique Visitors', number_format($uniqueVisitors))
                ->description('Registered users who visited')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
                
            Stat::make('Frontend Visits', number_format($frontendVisits))
                ->description(number_format(($frontendVisits / max($totalVisits, 1)) * 100, 1) . '% of total')
                ->descriptionIcon('heroicon-m-globe-alt')
                ->color('warning'),
                
            Stat::make('Admin Visits', number_format($adminVisits))
                ->description(number_format(($adminVisits / max($totalVisits, 1)) * 100, 1) . '% of total')
                ->descriptionIcon('heroicon-m-cog-6-tooth')
                ->color('gray'),
        ];
    }
}
