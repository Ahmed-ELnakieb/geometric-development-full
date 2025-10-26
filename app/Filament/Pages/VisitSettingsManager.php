<?php

namespace App\Filament\Pages;

use App\Models\Visit;
use App\Models\VisitTrackingSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\Attributes\Reactive;

class VisitSettingsManager extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    
    protected static ?string $navigationLabel = 'Visit Control Panel';
    
    protected static ?string $title = 'Visit Tracking Control Panel';
    
    protected static ?string $navigationGroup = 'Analytics';
    
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.visit-settings-manager';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->getCurrentSettings());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('General Settings')
                    ->schema([
                        Toggle::make('enabled')
                            ->label('Enable Visit Tracking')
                            ->helperText('Master switch for all visit tracking functionality')
                            ->reactive(),
                        
                        Toggle::make('track_frontend')
                            ->label('Track Frontend Visits')
                            ->helperText('Track visits to public pages')
                            ->visible(fn ($get) => $get('enabled')),
                        
                        Toggle::make('track_admin')
                            ->label('Track Admin Visits')
                            ->helperText('Track visits to admin/filament pages (Note: /admin routes are always excluded)')
                            ->visible(fn ($get) => $get('enabled')),
                    ])
                    ->columns(1),

                Section::make('Performance Settings')
                    ->schema([
                        Toggle::make('use_queue')
                            ->label('Use Queue for Processing')
                            ->helperText('Process visits in background queue for better performance')
                            ->reactive(),
                        
                        TextInput::make('queue_name')
                            ->label('Queue Name')
                            ->default('default')
                            ->helperText('Name of the queue to use for processing visits')
                            ->visible(fn ($get) => $get('use_queue')),
                        
                        TextInput::make('retention_days')
                            ->label('Data Retention (Days)')
                            ->numeric()
                            ->default(365)
                            ->helperText('Number of days to keep visit data (0 = keep forever)'),
                    ])
                    ->columns(2),

                Section::make('Exclusions')
                    ->schema([
                        TagsInput::make('excluded_routes')
                            ->label('Excluded Routes')
                            ->helperText('Route patterns to exclude from tracking (supports wildcards like api/*, admin/* is always excluded)')
                            ->placeholder('Add route pattern...'),
                        
                        TagsInput::make('excluded_ips')
                            ->label('Excluded IP Addresses')
                            ->helperText('IP addresses to exclude from tracking')
                            ->placeholder('Add IP address...'),
                        
                        TagsInput::make('excluded_user_agents')
                            ->label('Excluded User Agents')
                            ->helperText('User agent patterns to exclude (e.g., bot, crawler)')
                            ->placeholder('Add user agent pattern...'),
                    ])
                    ->columns(1),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->icon('heroicon-o-check')
                ->color('success')
                ->action('save'),
                
            Action::make('clearAllVisits')
                ->label('Clear All Visits')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Clear All Visits')
                ->modalDescription('Are you sure you want to delete all visit records? This action cannot be undone.')
                ->modalSubmitActionLabel('Yes, clear all visits')
                ->action('clearAllVisits'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();
        
        foreach ($data as $key => $value) {
            VisitTrackingSetting::set($key, $value);
        }
        
        Notification::make()
            ->title('Settings saved successfully')
            ->body('All visit tracking settings have been updated.')
            ->success()
            ->send();
    }

    public function isTrackingEnabled(): bool
    {
        return VisitTrackingSetting::get('enabled', true);
    }

    public function getVisitStats(): array
    {
        $totalVisits = Visit::count();
        $todayVisits = Visit::whereDate('created_at', today())->count();
        $uniqueVisitors = Visit::whereNotNull('visitor_id')->distinct('visitor_id')->count();
        
        // Calculate average daily visits (last 30 days)
        $thirtyDaysAgo = now()->subDays(30);
        $visitsLast30Days = Visit::where('created_at', '>=', $thirtyDaysAgo)->count();
        $avgDailyVisits = $visitsLast30Days > 0 ? round($visitsLast30Days / 30, 1) : 0;
        
        return [
            'total_visits' => $totalVisits,
            'unique_visitors' => $uniqueVisitors,
            'today_visits' => $todayVisits,
            'avg_daily_visits' => $avgDailyVisits,
        ];
    }

    public function getCurrentSettings(): array
    {
        return [
            'enabled' => VisitTrackingSetting::get('enabled', true),
            'track_frontend' => VisitTrackingSetting::get('track_frontend', true),
            'track_admin' => VisitTrackingSetting::get('track_admin', false), // Default to false since /admin is excluded
            'excluded_routes' => VisitTrackingSetting::get('excluded_routes', ['api/*', 'livewire/*']),
            'excluded_ips' => VisitTrackingSetting::get('excluded_ips', []),
            'excluded_user_agents' => VisitTrackingSetting::get('excluded_user_agents', ['bot', 'crawler', 'spider']),
            'retention_days' => VisitTrackingSetting::get('retention_days', 365),
            'use_queue' => VisitTrackingSetting::get('use_queue', false),
            'queue_name' => VisitTrackingSetting::get('queue_name', 'default'),
        ];
    }

    public function clearAllVisits(): void
    {
        $deletedCount = Visit::count();
        Visit::truncate();
        
        Notification::make()
            ->title('All visits cleared')
            ->body("Successfully deleted {$deletedCount} visit records.")
            ->success()
            ->send();
    }


}
