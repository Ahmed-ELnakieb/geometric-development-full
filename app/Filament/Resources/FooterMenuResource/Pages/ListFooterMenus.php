<?php

namespace App\Filament\Resources\FooterMenuResource\Pages;

use App\Filament\Resources\FooterMenuResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFooterMenus extends ListRecords
{
    protected static string $resource = FooterMenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
