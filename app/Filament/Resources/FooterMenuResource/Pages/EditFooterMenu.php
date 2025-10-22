<?php

namespace App\Filament\Resources\FooterMenuResource\Pages;

use App\Filament\Resources\FooterMenuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFooterMenu extends EditRecord
{
    protected static string $resource = FooterMenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('preview')
                ->label('Preview Link')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->url(fn () => getMenuUrl($this->record))
                ->openUrlInNewTab(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Clear cache after saving to reflect order changes
        cache()->flush();
        \Artisan::call('view:clear');
    }
}
