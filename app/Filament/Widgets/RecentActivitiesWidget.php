<?php

namespace App\Filament\Widgets;

use App\Models\CareerApplication;
use App\Models\Message;
use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentActivitiesWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    /**
     * Get table displaying recent activities
     */
    public function table(Table $table): Table
    {
        return $table
            ->heading('Recent Activities')
            ->query(
                // Combine recent career applications and messages
                CareerApplication::query()->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->label('Name')
                    ->formatStateUsing(fn ($record) => $record->first_name . ' ' . $record->last_name)
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('email')
                    ->icon('heroicon-o-envelope')
                    ->copyable()
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('phone')
                    ->icon('heroicon-o-phone')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('career.title')
                    ->label('Position')
                    ->badge()
                    ->color('success')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Applied At')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
