<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Project;
use App\Models\BlogPost;
use App\Models\Career;
use App\Models\CareerApplication;
use App\Models\Page;
use App\Models\Message;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('Registered admin users')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            
            Stat::make('Total Projects', Project::count())
                ->description('Real estate projects')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('primary')
                ->chart([2, 5, 10, 12, 18, 20, 25]),
            
            Stat::make('Blog Posts', BlogPost::count())
                ->description('Published articles')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('warning')
                ->chart([5, 10, 15, 12, 18, 20, 22]),
            
            Stat::make('Job Applications', CareerApplication::count())
                ->description('Total received')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('info')
                ->chart([2, 4, 6, 8, 10, 12, 15]),
            
            Stat::make('Active Jobs', Career::count())
                ->description('Open positions')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('indigo'),
            
            Stat::make('Messages', Message::count())
                ->description('Contact submissions')
                ->descriptionIcon('heroicon-m-envelope')
                ->color('danger')
                ->chart([5, 8, 12, 15, 20, 25, 30]),
        ];
    }
}
