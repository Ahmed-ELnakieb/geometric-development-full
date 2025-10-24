<?php

namespace App\Filament\Pages;

use App\Services\PushNotificationService;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManagePushNotifications extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    
    protected static ?string $navigationLabel = 'Send Notifications';
    
    protected static ?string $navigationGroup = 'PWA Management';
    
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.manage-push-notifications';
    
    public ?array $data = [];
    
    public function mount(): void
    {
        $this->form->fill();
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->label('Notification Title'),
                Textarea::make('body')
                    ->required()
                    ->maxLength(500)
                    ->rows(4)
                    ->label('Notification Message'),
                TextInput::make('url')
                    ->url()
                    ->label('Target URL')
                    ->default('/')
                    ->helperText('URL to open when notification is clicked'),
                TextInput::make('icon')
                    ->url()
                    ->label('Custom Icon URL')
                    ->helperText('Leave empty to use default app icon'),
                Toggle::make('require_interaction')
                    ->label('Require User Interaction')
                    ->helperText('Notification stays visible until user interacts'),
                Toggle::make('silent')
                    ->label('Silent Notification')
                    ->helperText('No sound or vibration'),
            ])
            ->statePath('data');
    }
    
    public function sendNotification(): void
    {
        $data = $this->form->getState();
        
        $service = app(PushNotificationService::class);
        
        $notification = $service->createNotification(
            $data['title'],
            $data['body'],
            [
                'data' => [
                    'url' => $data['url'] ?? '/',
                    'timestamp' => now()->toISOString()
                ],
                'icon' => $data['icon'] ?? '/assets/img/logo/favicon.png',
                'requireInteraction' => $data['require_interaction'] ?? false,
                'silent' => $data['silent'] ?? false,
            ]
        );
        
        $result = $service->sendToAll($notification);
        
        if ($result['success']) {
            Notification::make()
                ->title('Notification Sent Successfully')
                ->body("Delivered to {$result['sent']} subscribers. {$result['failed']} failed.")
                ->success()
                ->send();
                
            $this->form->fill();
        } else {
            Notification::make()
                ->title('Failed to Send Notification')
                ->body($result['message'])
                ->danger()
                ->send();
        }
    }
    
    public function getStats(): array
    {
        $service = app(PushNotificationService::class);
        return $service->getStatistics();
    }
}
