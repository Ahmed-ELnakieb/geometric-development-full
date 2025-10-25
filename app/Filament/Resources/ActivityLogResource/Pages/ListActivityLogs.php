<?php

namespace App\Filament\Resources\ActivityLogResource\Pages;

use App\Filament\Resources\ActivityLogResource;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Hash;
use Spatie\Activitylog\Models\Activity;

class ListActivityLogs extends ListRecords
{
    protected static string $resource = ActivityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('clearAllLogs')
                ->label('Clear All Logs')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Clear All Activity Logs')
                ->modalDescription('This action will permanently delete all activity logs. Please enter developer credentials to proceed.')

                ->form([
                    Forms\Components\TextInput::make('developer_email')
                        ->label('Developer Email')
                        ->email()
                        ->required()
                        ->placeholder('********95@gmail.com')
                        ->autocomplete(false),
                    Forms\Components\TextInput::make('developer_password')
                        ->label('Developer Password')
                        ->password()
                        ->required()
                        ->placeholder('Enter developer password')
                        ->autocomplete(false),
                ])
                ->action(function (array $data) {
                    // Validate developer credentials
                    $developer = User::where('email', $data['developer_email'])
                        ->where('is_developer', true)
                        ->first();
                    
                    if (!$developer || !Hash::check($data['developer_password'], $developer->password)) {
                        Notification::make()
                            ->danger()
                            ->title('Authentication Failed')
                            ->body('Invalid developer credentials. Please check your email and password.')
                            ->send();
                        
                        return;
                    }
                    
                    // Clear all logs
                    Activity::truncate();
                    
                    // Log this action
                    activity()
                        ->causedBy(auth()->user())
                        ->log('Cleared all activity logs (authenticated by developer: ' . $developer->email . ')');
                    
                    Notification::make()
                        ->success()
                        ->title('Success')
                        ->body('All activity logs have been cleared successfully.')
                        ->send();
                }),
        ];
    }
}
