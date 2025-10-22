<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use App\Models\BlogPost;
use App\Models\CareerApplication;
use App\Models\Message;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class MonthlyTrendsWidget extends ChartWidget
{
    protected static ?string $heading = 'Monthly Activity Trends';
    protected static ?int $sort = 1;
    protected static string $color = 'info';

    /**
     * Get chart data showing monthly trends
     */
    protected function getData(): array
    {
        $months = collect(range(1, 6))->map(function ($month) {
            return Carbon::now()->subMonths(6 - $month)->format('M Y');
        })->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Projects',
                    'data' => $this->getMonthlyData(Project::class),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                ],
                [
                    'label' => 'Blog Posts',
                    'data' => $this->getMonthlyData(BlogPost::class),
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                ],
                [
                    'label' => 'Applications',
                    'data' => $this->getMonthlyData(CareerApplication::class),
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                ],
                [
                    'label' => 'Messages',
                    'data' => $this->getMonthlyData(Message::class),
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                ],
            ],
            'labels' => $months,
        ];
    }

    /**
     * Get monthly count data for a model
     */
    protected function getMonthlyData(string $model): array
    {
        return collect(range(1, 6))->map(function ($month) use ($model) {
            $startDate = Carbon::now()->subMonths(6 - $month)->startOfMonth();
            $endDate = Carbon::now()->subMonths(6 - $month)->endOfMonth();

            return $model::whereBetween('created_at', [$startDate, $endDate])->count();
        })->toArray();
    }

    protected function getType(): string
    {
        return 'line';
    }
}
