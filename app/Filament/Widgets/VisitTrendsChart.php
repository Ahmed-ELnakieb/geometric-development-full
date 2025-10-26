<?php

namespace App\Filament\Widgets;

use App\Models\Visit;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class VisitTrendsChart extends ChartWidget
{
    protected static ?string $heading = 'Visit Trends (Last 30 Days)';
    
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // Get visit data for the last 30 days
        $visits = Visit::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw('COUNT(CASE WHEN is_admin = 0 THEN 1 END) as frontend'),
                DB::raw('COUNT(CASE WHEN is_admin = 1 THEN 1 END) as admin')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Fill in missing dates with zero values
        $dates = [];
        $totalData = [];
        $frontendData = [];
        $adminData = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $dates[] = Carbon::parse($date)->format('M j');
            
            $visitData = $visits->firstWhere('date', $date);
            $totalData[] = $visitData ? $visitData->total : 0;
            $frontendData[] = $visitData ? $visitData->frontend : 0;
            $adminData[] = $visitData ? $visitData->admin : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Visits',
                    'data' => $totalData,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 2,
                ],
                [
                    'label' => 'Frontend Visits',
                    'data' => $frontendData,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.5)',
                    'borderColor' => 'rgb(16, 185, 129)',
                    'borderWidth' => 2,
                ],
                [
                    'label' => 'Admin Visits',
                    'data' => $adminData,
                    'backgroundColor' => 'rgba(245, 158, 11, 0.5)',
                    'borderColor' => 'rgb(245, 158, 11)',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $dates,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}
