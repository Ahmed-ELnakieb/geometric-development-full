<?php

namespace App\Filament\Pages;

use App\Models\Visit;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class VisitAnalytics extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    
    protected static ?string $navigationLabel = 'Visit Analytics';
    
    protected static ?string $title = 'Visit Analytics';
    
    protected static ?string $navigationGroup = 'Analytics';
    
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.visit-analytics';

    public ?string $dateRange = '7';
    public ?string $visitType = 'all';
    public ?string $modelType = 'all';

    public function updated($property)
    {
        // Clear cache when filters change
        if (in_array($property, ['dateRange', 'visitType', 'modelType'])) {
            $this->clearAnalyticsCache();
        }
    }

    protected function clearAnalyticsCache(): void
    {
        $patterns = [
            "visit_stats_*",
            "top_pages_*",
            "top_referrers_*",
            "visit_trends_*",
            "model_visits_*",
        ];

        foreach ($patterns as $pattern) {
            Cache::forget($pattern);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('clearAllVisits')
                ->label('Clear All Visits')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Clear All Visits')
                ->modalDescription('Are you sure you want to delete all visit records? This action cannot be undone.')
                ->modalSubmitActionLabel('Yes, clear all visits')
                ->action('clearAllVisits'),
                
            Action::make('exportCsv')
                ->label('Export CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->action('exportToCsv'),
                
            Action::make('exportJson')
                ->label('Export JSON')
                ->icon('heroicon-o-document-arrow-down')
                ->action('exportToJson'),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('dateRange')
                    ->label('Date Range')
                    ->options([
                        '1' => 'Today',
                        '7' => 'Last 7 days',
                        '30' => 'Last 30 days',
                        '90' => 'Last 90 days',
                        '365' => 'Last year',
                    ])
                    ->default('7')
                    ->reactive(),
                    
                Select::make('visitType')
                    ->label('Visit Type')
                    ->options([
                        'all' => 'All Visits',
                        'frontend' => 'Frontend Only',
                        'admin' => 'Admin Only',
                    ])
                    ->default('all')
                    ->reactive(),
                    
                Select::make('modelType')
                    ->label('Content Type')
                    ->options([
                        'all' => 'All Content',
                        'App\\Models\\Page' => 'Pages',
                        'App\\Models\\BlogPost' => 'Blog Posts',
                        'App\\Models\\Project' => 'Projects',
                    ])
                    ->default('all')
                    ->reactive(),
            ])
            ->columns(3);
    }

    public function getVisitStats(): array
    {
        $cacheKey = "visit_stats_{$this->dateRange}_{$this->visitType}_{$this->modelType}";
        
        return Cache::remember($cacheKey, now()->addMinutes(5), function () {
            $query = Visit::query();
            
            // Apply date range filter
            if ($this->dateRange) {
                $startDate = Carbon::now()->subDays((int) $this->dateRange);
                $query->where('created_at', '>=', $startDate);
            }
            
            // Apply visit type filter
            if ($this->visitType !== 'all') {
                $query->where('is_admin', $this->visitType === 'admin');
            }
            
            // Apply model type filter
            if ($this->modelType !== 'all') {
                $query->where('visitable_type', $this->modelType);
            }

            $totalVisits = $query->count();
            $uniqueVisitors = $query->whereNotNull('visitor_id')->distinct('visitor_id')->count();
            $todayVisits = (clone $query)->whereDate('created_at', today())->count();
            
            return [
                'total_visits' => $totalVisits,
                'unique_visitors' => $uniqueVisitors,
                'today_visits' => $todayVisits,
                'avg_daily_visits' => $this->dateRange ? round($totalVisits / (int) $this->dateRange, 1) : 0,
            ];
        });
    }

    public function getTopPages(): array
    {
        $cacheKey = "top_pages_{$this->dateRange}_{$this->visitType}";
        
        return Cache::remember($cacheKey, now()->addMinutes(10), function () {
            $query = Visit::query()
                ->select('url', DB::raw('COUNT(*) as visits'))
                ->groupBy('url')
                ->orderByDesc('visits')
                ->limit(10);
                
            // Apply filters
            if ($this->dateRange) {
                $startDate = Carbon::now()->subDays((int) $this->dateRange);
                $query->where('created_at', '>=', $startDate);
            }
            
            if ($this->visitType !== 'all') {
                $query->where('is_admin', $this->visitType === 'admin');
            }

            return $query->get()->toArray();
        });
    }

    public function getTopReferrers(): array
    {
        $query = Visit::query()
            ->whereNotNull('referer')
            ->select('referer', DB::raw('COUNT(*) as visits'))
            ->groupBy('referer')
            ->orderByDesc('visits')
            ->limit(10);
            
        // Apply filters
        if ($this->dateRange) {
            $startDate = Carbon::now()->subDays((int) $this->dateRange);
            $query->where('created_at', '>=', $startDate);
        }
        
        if ($this->visitType !== 'all') {
            $query->where('is_admin', $this->visitType === 'admin');
        }

        return $query->get()->toArray();
    }

    public function getVisitTrends(): array
    {
        $query = Visit::query()
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as visits')
            )
            ->groupBy('date')
            ->orderBy('date');
            
        // Apply filters
        if ($this->dateRange) {
            $startDate = Carbon::now()->subDays((int) $this->dateRange);
            $query->where('created_at', '>=', $startDate);
        }
        
        if ($this->visitType !== 'all') {
            $query->where('is_admin', $this->visitType === 'admin');
        }

        return $query->get()->toArray();
    }

    public function getModelVisits(): array
    {
        if ($this->modelType === 'all') {
            $query = Visit::query()
                ->whereNotNull('visitable_type')
                ->select(
                    'visitable_type',
                    'visitable_id',
                    DB::raw('COUNT(*) as visits')
                )
                ->with('visitable')
                ->groupBy('visitable_type', 'visitable_id')
                ->orderByDesc('visits')
                ->limit(20);
        } else {
            $query = Visit::query()
                ->where('visitable_type', $this->modelType)
                ->select(
                    'visitable_type',
                    'visitable_id',
                    DB::raw('COUNT(*) as visits')
                )
                ->with('visitable')
                ->groupBy('visitable_type', 'visitable_id')
                ->orderByDesc('visits')
                ->limit(20);
        }
            
        // Apply date filter
        if ($this->dateRange) {
            $startDate = Carbon::now()->subDays((int) $this->dateRange);
            $query->where('created_at', '>=', $startDate);
        }
        
        if ($this->visitType !== 'all') {
            $query->where('is_admin', $this->visitType === 'admin');
        }

        return $query->get()->toArray();
    }

    public function exportToCsv()
    {
        $visits = $this->getFilteredVisits();
        
        $filename = 'visit-analytics-' . now()->format('Y-m-d-H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($visits) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID',
                'URL',
                'Content Type',
                'Content ID',
                'Content Title',
                'Visitor ID',
                'Visitor Name',
                'IP Address',
                'User Agent',
                'Referrer',
                'Method',
                'Is Admin',
                'Visit Date',
            ]);

            // CSV data
            foreach ($visits as $visit) {
                fputcsv($file, [
                    $visit->id,
                    $visit->url,
                    $visit->visitable_type ? class_basename($visit->visitable_type) : 'N/A',
                    $visit->visitable_id ?? 'N/A',
                    $visit->visitable->title ?? $visit->visitable->name ?? 'N/A',
                    $visit->visitor_id ?? 'N/A',
                    $visit->visitor->name ?? 'Anonymous',
                    $visit->ip,
                    $visit->user_agent,
                    $visit->referer ?? 'Direct',
                    $visit->method,
                    $visit->is_admin ? 'Yes' : 'No',
                    $visit->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function exportToJson()
    {
        $visits = $this->getFilteredVisits();
        
        $filename = 'visit-analytics-' . now()->format('Y-m-d-H-i-s') . '.json';
        
        $data = [
            'export_date' => now()->toISOString(),
            'filters' => [
                'date_range' => $this->dateRange,
                'visit_type' => $this->visitType,
                'model_type' => $this->modelType,
            ],
            'statistics' => $this->getVisitStats(),
            'visits' => $visits->map(function ($visit) {
                return [
                    'id' => $visit->id,
                    'url' => $visit->url,
                    'content_type' => $visit->visitable_type ? class_basename($visit->visitable_type) : null,
                    'content_id' => $visit->visitable_id,
                    'content_title' => $visit->visitable->title ?? $visit->visitable->name ?? null,
                    'visitor_id' => $visit->visitor_id,
                    'visitor_name' => $visit->visitor->name ?? null,
                    'ip_address' => $visit->ip,
                    'user_agent' => $visit->user_agent,
                    'referrer' => $visit->referer,
                    'method' => $visit->method,
                    'is_admin' => $visit->is_admin,
                    'visit_date' => $visit->created_at->toISOString(),
                ];
            })->toArray(),
        ];

        $headers = [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return Response::make(json_encode($data, JSON_PRETTY_PRINT), 200, $headers);
    }

    protected function getFilteredVisits()
    {
        $query = Visit::with(['visitable', 'visitor']);
        
        // Apply date range filter
        if ($this->dateRange) {
            $startDate = Carbon::now()->subDays((int) $this->dateRange);
            $query->where('created_at', '>=', $startDate);
        }
        
        // Apply visit type filter
        if ($this->visitType !== 'all') {
            $query->where('is_admin', $this->visitType === 'admin');
        }
        
        // Apply model type filter
        if ($this->modelType !== 'all') {
            $query->where('visitable_type', $this->modelType);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function clearAllVisits(): void
    {
        $deletedCount = Visit::count();
        Visit::truncate();
        
        // Clear analytics cache
        $this->clearAnalyticsCache();
        
        Notification::make()
            ->title('All visits cleared')
            ->body("Successfully deleted {$deletedCount} visit records.")
            ->success()
            ->send();
            
        // Refresh the page data
        $this->redirect(request()->header('Referer'));
    }
}
