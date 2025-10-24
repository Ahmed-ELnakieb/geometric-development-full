<?php

namespace App\Filament\Resources\PushSubscriptionResource\Pages;

use App\Filament\Resources\PushSubscriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPushSubscriptions extends ListRecords
{
    protected static string $resource = PushSubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
