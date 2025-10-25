<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public function mount(int | string $record): void
    {
        parent::mount($record);
        
        if ($this->record->isDeveloper()) {
            Notification::make()
                ->danger()
                ->title('Access Denied')
                ->body('The developer account cannot be modified.')
                ->send();
                
            $this->redirect(UserResource::getUrl('index'));
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
