<?php

namespace App\Filament\Resources\CareerApplicationResource\Pages;

use App\Filament\Resources\CareerApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCareerApplication extends EditRecord
{
    protected static string $resource = CareerApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()->requiresConfirmation(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }

    /**
     * Sync cv_file_id with Spatie Media Library after save
     */
    protected function afterSave(): void
    {
        $cvMedia = $this->record->getFirstMedia('cv_files');
        
        if ($cvMedia && $this->record->cv_file_id !== $cvMedia->id) {
            $this->record->update(['cv_file_id' => $cvMedia->id]);
        }
    }
}