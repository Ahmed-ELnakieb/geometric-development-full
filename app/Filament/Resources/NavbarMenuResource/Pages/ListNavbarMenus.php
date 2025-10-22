<?php

namespace App\Filament\Resources\NavbarMenuResource\Pages;

use App\Filament\Resources\NavbarMenuResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNavbarMenus extends ListRecords
{
    protected static string $resource = NavbarMenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
