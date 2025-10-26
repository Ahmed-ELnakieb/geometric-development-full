<?php

namespace App\Filament\Resources\ChatAgentResource\Pages;

use App\Filament\Resources\ChatAgentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChatAgent extends EditRecord
{
    protected static string $resource = ChatAgentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
