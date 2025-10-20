<?php

namespace App\Filament\Resources\CareerApplicationResource\Pages;

use App\Filament\Resources\CareerApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCareerApplication extends ViewRecord
{
    protected static string $resource = CareerApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make()->requiresConfirmation(),
            Actions\Action::make('review')
                ->visible(fn ($record) => $record->status === 'new')
                ->action(fn ($record) => $record->review())
                ->icon('heroicon-o-eye')
                ->color('info'),
            Actions\Action::make('shortlist')
                ->visible(fn ($record) => in_array($record->status, ['new', 'reviewing']))
                ->action(fn ($record) => $record->shortlist())
                ->icon('heroicon-o-star')
                ->color('warning'),
            Actions\Action::make('interview')
                ->visible(fn ($record) => $record->status === 'shortlisted')
                ->action(fn ($record) => $record->interview())
                ->icon('heroicon-o-calendar')
                ->color('primary'),
            Actions\Action::make('hire')
                ->visible(fn ($record) => in_array($record->status, ['interviewed', 'shortlisted']))
                ->action(fn ($record) => $record->hire())
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation(),
            Actions\Action::make('reject')
                ->visible(fn ($record) => !in_array($record->status, ['hired', 'rejected']))
                ->action(fn ($record) => $record->reject())
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation(),
        ];
    }
}