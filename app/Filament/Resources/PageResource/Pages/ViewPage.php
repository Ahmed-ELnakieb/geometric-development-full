<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use Filament\Actions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewPage extends ViewRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Page Information')
                    ->schema([
                        TextEntry::make('title'),
                        TextEntry::make('slug'),
                        TextEntry::make('template'),
                        TextEntry::make('is_published')
                            ->badge()
                            ->color(fn ($state) => $state ? 'success' : 'danger')
                            ->formatStateUsing(fn ($state) => $state ? 'Published' : 'Draft'),
                        TextEntry::make('published_at')
                            ->dateTime(),
                    ])
                    ->columns(2),
                    
                Section::make('Visit Statistics')
                    ->schema([
                        TextEntry::make('visit_count')
                            ->label('Total Visits')
                            ->formatStateUsing(fn ($record) => number_format($record->visits()->count())),
                            
                        TextEntry::make('unique_visitors')
                            ->label('Unique Visitors')
                            ->formatStateUsing(fn ($record) => number_format($record->visits()->whereNotNull('visitor_id')->distinct('visitor_id')->count())),
                            
                        TextEntry::make('today_visits')
                            ->label('Today\'s Visits')
                            ->formatStateUsing(fn ($record) => number_format($record->visits()->whereDate('created_at', today())->count())),
                            
                        TextEntry::make('this_week_visits')
                            ->label('This Week\'s Visits')
                            ->formatStateUsing(fn ($record) => number_format($record->visits()->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count())),
                            
                        TextEntry::make('frontend_visits')
                            ->label('Frontend Visits')
                            ->formatStateUsing(fn ($record) => number_format($record->visits()->where('is_admin', false)->count())),
                            
                        TextEntry::make('admin_visits')
                            ->label('Admin Visits')
                            ->formatStateUsing(fn ($record) => number_format($record->visits()->where('is_admin', true)->count())),
                    ])
                    ->columns(3),
                    
                Section::make('SEO Information')
                    ->schema([
                        TextEntry::make('meta_title'),
                        TextEntry::make('meta_description')
                            ->columnSpanFull(),
                        TextEntry::make('meta_keywords')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
