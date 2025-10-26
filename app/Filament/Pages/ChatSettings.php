<?php

namespace App\Filament\Pages;

use App\Models\ChatSetting;
use App\Services\WhatsAppCloudService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class ChatSettings extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    
    protected static ?string $navigationLabel = 'Chat Settings';
    
    protected static ?string $title = 'WhatsApp Chat Settings';
    
    protected static ?string $navigationGroup = 'Chat Management';
    
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.chat-settings';

    public ?array $data = [];



    public function mount(): void
    {
        $this->form->fill([
            'whatsapp_app_id' => ChatSetting::get('whatsapp_app_id', ''),
            'whatsapp_phone_number' => ChatSetting::get('whatsapp_phone_number', ''),
            'whatsapp_access_token' => ChatSetting::get('whatsapp_access_token', ''),
            'whatsapp_app_secret' => ChatSetting::get('whatsapp_app_secret', ''),
            'whatsapp_webhook_url' => ChatSetting::get('whatsapp_webhook_url', url('/api/whatsapp/webhook')),
            'whatsapp_webhook_verify_token' => ChatSetting::get('whatsapp_webhook_verify_token', ''),
            'chat_enabled' => ChatSetting::get('chat_enabled', 'true') === 'true',
            'auto_reply_enabled' => ChatSetting::get('auto_reply_enabled', 'true') === 'true',
            'business_hours_enabled' => ChatSetting::get('business_hours_enabled', 'false') === 'true',
            'business_hours_start' => ChatSetting::get('business_hours_start', '09:00'),
            'business_hours_end' => ChatSetting::get('business_hours_end', '17:00'),
            'away_message' => ChatSetting::get('away_message', 'Thank you for your message! We are currently away but will get back to you soon.'),
            'welcome_message' => ChatSetting::get('welcome_message', 'Hi! 👋 How can we help you today?'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Settings')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('WhatsApp API')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Forms\Components\Section::make('WhatsApp Cloud API Configuration')
                                    ->description('Configure your WhatsApp Business API credentials')
                                    ->schema([
                                        Forms\Components\TextInput::make('whatsapp_app_id')
                                            ->label('App ID')
                                            ->required()
                                            ->placeholder('Enter your WhatsApp App ID')
                                            ->helperText('Your WhatsApp Business App ID from Meta Developer Console'),
                                        
                                        Forms\Components\TextInput::make('whatsapp_phone_number')
                                            ->label('Phone Number ID')
                                            ->required()
                                            ->placeholder('Enter your Phone Number ID')
                                            ->helperText('The Phone Number ID from your WhatsApp Business Account'),
                                        
                                        Forms\Components\TextInput::make('whatsapp_access_token')
                                            ->label('Access Token')
                                            ->required()
                                            ->password()
                                            ->placeholder('Enter your Access Token')
                                            ->helperText('Your WhatsApp Business API Access Token'),
                                        
                                        Forms\Components\TextInput::make('whatsapp_app_secret')
                                            ->label('App Secret')
                                            ->required()
                                            ->password()
                                            ->placeholder('Enter your App Secret')
                                            ->helperText('Your WhatsApp App Secret for webhook verification'),
                                    ])->columns(2),
                                
                                Forms\Components\Section::make('Webhook Configuration')
                                    ->description('Configure webhook settings for receiving messages')
                                    ->schema([
                                        Forms\Components\TextInput::make('whatsapp_webhook_url')
                                            ->label('Webhook URL')
                                            ->required()
                                            ->url()
                                            ->placeholder('https://yourdomain.com/api/whatsapp/webhook')
                                            ->helperText('The URL where WhatsApp will send webhook events'),
                                        
                                        Forms\Components\TextInput::make('whatsapp_webhook_verify_token')
                                            ->label('Webhook Verify Token')
                                            ->required()
                                            ->placeholder('Enter a secure verify token')
                                            ->helperText('A secure token for webhook verification'),
                                    ])->columns(1),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Chat Settings')
                            ->icon('heroicon-o-chat-bubble-left-ellipsis')
                            ->schema([
                                Forms\Components\Section::make('General Settings')
                                    ->schema([
                                        Forms\Components\Toggle::make('chat_enabled')
                                            ->label('Enable Chat Widget')
                                            ->helperText('Enable or disable the chat widget on your website')
                                            ->default(true),
                                        
                                        Forms\Components\Toggle::make('auto_reply_enabled')
                                            ->label('Enable Auto-Reply')
                                            ->helperText('Automatically reply to incoming messages')
                                            ->default(true),
                                        
                                        Forms\Components\Textarea::make('welcome_message')
                                            ->label('Welcome Message')
                                            ->placeholder('Hi! 👋 How can we help you today?')
                                            ->helperText('The first message visitors see when they open the chat')
                                            ->rows(3),
                                    ])->columns(1),
                                
                                Forms\Components\Section::make('Business Hours')
                                    ->schema([
                                        Forms\Components\Toggle::make('business_hours_enabled')
                                            ->label('Enable Business Hours')
                                            ->helperText('Only show online status during business hours')
                                            ->reactive(),
                                        
                                        Forms\Components\TimePicker::make('business_hours_start')
                                            ->label('Business Hours Start')
                                            ->default('09:00')
                                            ->visible(fn (Forms\Get $get) => $get('business_hours_enabled')),
                                        
                                        Forms\Components\TimePicker::make('business_hours_end')
                                            ->label('Business Hours End')
                                            ->default('17:00')
                                            ->visible(fn (Forms\Get $get) => $get('business_hours_enabled')),
                                        
                                        Forms\Components\Textarea::make('away_message')
                                            ->label('Away Message')
                                            ->placeholder('Thank you for your message! We are currently away but will get back to you soon.')
                                            ->helperText('Message sent when outside business hours or when auto-reply is enabled')
                                            ->rows(3),
                                    ])->columns(2),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Testing')
                            ->icon('heroicon-o-beaker')
                            ->schema([
                                Forms\Components\Section::make('API Testing')
                                    ->description('Test your WhatsApp API configuration')
                                    ->schema([
                                        Forms\Components\Placeholder::make('test_connection')
                                            ->label('Connection Status')
                                            ->content(fn () => new \Illuminate\Support\HtmlString($this->getConnectionStatus())),
                                        
                                        Forms\Components\TextInput::make('test_phone_number')
                                            ->label('Test Phone Number')
                                            ->placeholder('+1234567890')
                                            ->helperText('Phone number to send test message to'),
                                        
                                        Forms\Components\Textarea::make('test_message')
                                            ->label('Test Message')
                                            ->placeholder('This is a test message from your WhatsApp chat system.')
                                            ->rows(3),
                                    ])->columns(1),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }



    public function save(): void
    {
        $data = $this->form->getState();
        
        try {
            // Save API settings (encrypted)
            ChatSetting::set('whatsapp_app_id', $data['whatsapp_app_id'], false, 'WhatsApp App ID');
            ChatSetting::set('whatsapp_phone_number', $data['whatsapp_phone_number'], false, 'WhatsApp Phone Number ID');
            ChatSetting::set('whatsapp_access_token', $data['whatsapp_access_token'], true, 'WhatsApp Access Token');
            ChatSetting::set('whatsapp_app_secret', $data['whatsapp_app_secret'], true, 'WhatsApp App Secret');
            ChatSetting::set('whatsapp_webhook_url', $data['whatsapp_webhook_url'], false, 'WhatsApp Webhook URL');
            ChatSetting::set('whatsapp_webhook_verify_token', $data['whatsapp_webhook_verify_token'], true, 'WhatsApp Webhook Verify Token');
            
            // Save chat settings
            ChatSetting::set('chat_enabled', $data['chat_enabled'] ? 'true' : 'false', false, 'Chat Widget Enabled');
            ChatSetting::set('auto_reply_enabled', $data['auto_reply_enabled'] ? 'true' : 'false', false, 'Auto Reply Enabled');
            ChatSetting::set('business_hours_enabled', $data['business_hours_enabled'] ? 'true' : 'false', false, 'Business Hours Enabled');
            ChatSetting::set('business_hours_start', $data['business_hours_start'], false, 'Business Hours Start Time');
            ChatSetting::set('business_hours_end', $data['business_hours_end'], false, 'Business Hours End Time');
            ChatSetting::set('away_message', $data['away_message'], false, 'Away Message');
            ChatSetting::set('welcome_message', $data['welcome_message'], false, 'Welcome Message');
            
            Notification::make()
                ->title('Settings Saved')
                ->body('Chat settings have been saved successfully.')
                ->success()
                ->send();
                
            Log::info('Chat settings updated', [
                'user_id' => auth()->id(),
                'settings' => array_keys($data)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to save chat settings', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            Notification::make()
                ->title('Error')
                ->body('Failed to save settings: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function testConnection(): void
    {
        try {
            $whatsappService = app(WhatsAppCloudService::class);
            $result = $whatsappService->testConnection();
            
            if (isset($result['verified_name'])) {
                Notification::make()
                    ->title('Connection Successful')
                    ->body("Connected to: {$result['verified_name']}")
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('Connection Failed')
                    ->body($result['error'] ?? 'Unknown error')
                    ->danger()
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Connection Error')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function sendTestMessage(): void
    {
        $data = $this->form->getState();
        
        if (empty($data['test_phone_number']) || empty($data['test_message'])) {
            Notification::make()
                ->title('Missing Information')
                ->body('Please enter both phone number and test message.')
                ->warning()
                ->send();
            return;
        }
        
        try {
            $whatsappService = app(WhatsAppCloudService::class);
            $result = $whatsappService->sendMessage($data['test_phone_number'], $data['test_message']);
            
            if (isset($result['messages'][0]['id'])) {
                Notification::make()
                    ->title('Test Message Sent')
                    ->body("Message sent successfully! ID: {$result['messages'][0]['id']}")
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('Send Failed')
                    ->body('Failed to send test message.')
                    ->danger()
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Send Error')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    private function getConnectionStatus(): string
    {
        try {
            // Check if basic credentials are provided
            $appId = ChatSetting::get('whatsapp_app_id');
            $phoneNumber = ChatSetting::get('whatsapp_phone_number');
            $accessToken = ChatSetting::get('whatsapp_access_token');
            
            if (empty($appId) || empty($phoneNumber) || empty($accessToken)) {
                return '🔴 Not Configured - Please enter your API credentials';
            }
            
            $whatsappService = app(WhatsAppCloudService::class);
            
            if (!$whatsappService->isConfigured()) {
                return '🔴 Not Configured - Please enter your API credentials';
            }
            
            $result = $whatsappService->testConnection();
            
            if (is_array($result)) {
                if (isset($result['success']) && $result['success'] === false) {
                    return '🟡 Configuration Error - ' . ($result['error'] ?? 'Unknown error');
                } elseif (isset($result['verified_name'])) {
                    return "🟢 Connected - {$result['verified_name']}";
                } else {
                    return '🟡 Configuration Error - Invalid response format';
                }
            }
            
            return '🟡 Configuration Error - Unexpected response format';
        } catch (\Exception $e) {
            return '🔴 Connection Failed - ' . $e->getMessage();
        }
    }
}
