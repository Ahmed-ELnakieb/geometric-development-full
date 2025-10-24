<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Support\Facades\Artisan;

class PWASettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    
    protected static ?string $navigationLabel = 'PWA Settings';
    
    protected static ?string $navigationGroup = 'PWA Management';
    
    protected static ?int $navigationSort = 3;
    
    protected static ?string $title = 'PWA Configuration';

    protected static string $view = 'filament.pages.p-w-a-settings';
    
    public ?array $data = [];
    
    public function mount(): void
    {
        $this->form->fill([
            // Basic PWA Settings
            'pwa_name' => config('pwa.name', 'Geometric Development'),
            'pwa_short_name' => config('pwa.short_name', 'Geometric'),
            'pwa_description' => config('pwa.description', 'Leading Saudi real estate company'),
            'pwa_theme_color' => ltrim(config('pwa.theme_color', '#1a1a1a'), '#'),
            'pwa_background_color' => ltrim(config('pwa.background_color', '#1a1a1a'), '#'),
            
            // Background Sync Settings
            'sync_enabled' => (bool) config('pwa.background_sync.enabled', true),
            'sync_max_retries' => (int) config('pwa.background_sync.max_retries', 3),
            'sync_retry_delay' => (int) config('pwa.background_sync.retry_delay', 1000),
            'sync_queue_limit' => (int) config('pwa.background_sync.queue_limit', 100),
            
            // Push Notification Settings
            'push_enabled' => (bool) config('pwa.push_notifications.enabled', true),
            'vapid_public_key' => config('pwa.push_notifications.vapid.public_key', ''),
            'vapid_private_key' => config('pwa.push_notifications.vapid.private_key', ''),
            'vapid_subject' => config('pwa.push_notifications.vapid.subject', 'mailto:admin@geometric-development.com'),
        ]);
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Basic PWA Configuration')
                    ->description('Configure basic Progressive Web App settings')
                    ->schema([
                        TextInput::make('pwa_name')
                            ->label('App Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('pwa_short_name')
                            ->label('Short Name')
                            ->required()
                            ->maxLength(12)
                            ->helperText('Used on home screen (max 12 characters)'),
                        Textarea::make('pwa_description')
                            ->label('Description')
                            ->required()
                            ->rows(3),
                        TextInput::make('pwa_theme_color')
                            ->label('Theme Color')
                            ->required()
                            ->prefix('#')
                            ->helperText('Hex color code (e.g., 1a1a1a)'),
                        TextInput::make('pwa_background_color')
                            ->label('Background Color')
                            ->required()
                            ->prefix('#')
                            ->helperText('Hex color code (e.g., 1a1a1a)'),
                    ])
                    ->columns(2),
                    
                Section::make('Background Sync Configuration')
                    ->description('Configure offline data synchronization')
                    ->schema([
                        Toggle::make('sync_enabled')
                            ->label('Enable Background Sync')
                            ->helperText('Allow forms and data to sync when connection is restored')
                            ->reactive(),
                        TextInput::make('sync_max_retries')
                            ->label('Max Retries')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(10)
                            ->default(3)
                            ->helperText('Number of retry attempts for failed syncs')
                            ->disabled(fn ($get) => !$get('sync_enabled')),
                        TextInput::make('sync_retry_delay')
                            ->label('Retry Delay (ms)')
                            ->numeric()
                            ->minValue(100)
                            ->maxValue(10000)
                            ->default(1000)
                            ->helperText('Delay between retry attempts in milliseconds')
                            ->disabled(fn ($get) => !$get('sync_enabled')),
                        TextInput::make('sync_queue_limit')
                            ->label('Queue Limit')
                            ->numeric()
                            ->minValue(10)
                            ->maxValue(1000)
                            ->default(100)
                            ->helperText('Maximum number of items in sync queue')
                            ->disabled(fn ($get) => !$get('sync_enabled')),
                    ])
                    ->columns(2),
                    
                Section::make('Push Notifications Configuration')
                    ->description('Configure push notification settings and VAPID keys')
                    ->schema([
                        Toggle::make('push_enabled')
                            ->label('Enable Push Notifications')
                            ->helperText('Allow sending push notifications to users')
                            ->reactive(),
                        TextInput::make('vapid_subject')
                            ->label('VAPID Subject')
                            ->required()
                            ->helperText('Contact email for push notifications (e.g., mailto:admin@example.com)')
                            ->disabled(fn ($get) => !$get('push_enabled')),
                        Textarea::make('vapid_public_key')
                            ->label('VAPID Public Key')
                            ->required()
                            ->rows(3)
                            ->helperText('Public key for push notification authentication')
                            ->disabled(fn ($get) => !$get('push_enabled')),
                        Textarea::make('vapid_private_key')
                            ->label('VAPID Private Key')
                            ->required()
                            ->rows(3)
                            ->helperText('Private key for push notification authentication (keep secure!)')
                            ->disabled(fn ($get) => !$get('push_enabled')),
                    ])
                    ->columns(1)
                    ->headerActions([
                        Action::make('generate_keys')
                            ->label('Generate New VAPID Keys')
                            ->icon('heroicon-o-key')
                            ->color('warning')
                            ->requiresConfirmation()
                            ->modalHeading('Generate New VAPID Keys')
                            ->modalDescription('This will generate new VAPID keys. All existing push subscriptions will be invalidated and users will need to re-subscribe.')
                            ->modalSubmitActionLabel('Generate Keys')
                            ->action(function () {
                                $this->generateVapidKeysAction();
                            }),
                    ]),
            ])
            ->statePath('data');
    }
    
    public function save(): void
    {
        $data = $this->form->getState();
        
        // Update .env file
        $this->updateEnvFile([
            'PWA_NAME' => $data['pwa_name'],
            'PWA_SHORT_NAME' => $data['pwa_short_name'],
            'PWA_DESCRIPTION' => $data['pwa_description'],
            'PWA_THEME_COLOR' => '#' . ltrim($data['pwa_theme_color'], '#'),
            'PWA_BACKGROUND_COLOR' => '#' . ltrim($data['pwa_background_color'], '#'),
            'PWA_SYNC_ENABLED' => $data['sync_enabled'] ? 'true' : 'false',
            'PWA_SYNC_MAX_RETRIES' => $data['sync_max_retries'],
            'PWA_SYNC_RETRY_DELAY' => $data['sync_retry_delay'],
            'PWA_SYNC_QUEUE_LIMIT' => $data['sync_queue_limit'],
            'PWA_PUSH_ENABLED' => $data['push_enabled'] ? 'true' : 'false',
            'PWA_VAPID_PUBLIC_KEY' => $data['vapid_public_key'],
            'PWA_VAPID_PRIVATE_KEY' => $data['vapid_private_key'],
            'PWA_VAPID_SUBJECT' => $data['vapid_subject'],
        ]);
        
        // Clear config cache
        Artisan::call('config:clear');
        
        Notification::make()
            ->title('Settings Saved Successfully')
            ->body('PWA configuration has been updated. Changes will take effect immediately.')
            ->success()
            ->send();
    }
    
    protected function updateEnvFile(array $data): void
    {
        $envFile = base_path('.env');
        $envContent = file_get_contents($envFile);
        
        foreach ($data as $key => $value) {
            // Escape special characters in value
            $value = str_replace('"', '\"', $value);
            
            // Check if key exists
            if (preg_match("/^{$key}=.*/m", $envContent)) {
                // Update existing key
                $envContent = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}=\"{$value}\"",
                    $envContent
                );
            } else {
                // Add new key
                $envContent .= "\n{$key}=\"{$value}\"";
            }
        }
        
        file_put_contents($envFile, $envContent);
    }
    
    protected function generateVapidKeysAction(): void
    {
        try {
            // Run the artisan command to generate keys
            Artisan::call('pwa:generate-vapid-keys');
            $output = Artisan::output();
            
            // Extract keys from output
            preg_match('/PWA_VAPID_PUBLIC_KEY="([^"]+)"/', $output, $publicMatch);
            preg_match('/PWA_VAPID_PRIVATE_KEY="([^"]+)"/', $output, $privateMatch);
            
            if (isset($publicMatch[1]) && isset($privateMatch[1])) {
                // Update form data
                $this->data['vapid_public_key'] = $publicMatch[1];
                $this->data['vapid_private_key'] = $privateMatch[1];
                
                Notification::make()
                    ->title('VAPID Keys Generated')
                    ->body('New VAPID keys have been generated. Remember to save the settings to apply changes.')
                    ->success()
                    ->send();
            } else {
                throw new \Exception('Failed to extract keys from command output');
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Key Generation Failed')
                ->body('Using sample keys for development. Error: ' . $e->getMessage())
                ->warning()
                ->send();
                
            // Use sample keys
            $this->data['vapid_public_key'] = 'BEl62iUYgUivxIkv69yViEuiBIa40HI0DLLuxN4AbgPyIdkAGfWKOqJOTKh-JnLKBJqBVkb0VplJBQVgpcUvQoA';
            $this->data['vapid_private_key'] = 'nCScfhigsOpkAHFuINDqEGnyRkmT9E-VcN5ma-5_LyM';
        }
    }
    

}
