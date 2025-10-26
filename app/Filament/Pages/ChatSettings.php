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

    protected function getHeaderActions(): array
    {
        return [
            Action::make('testConnection')
                ->label('Test Connection')
                ->icon('heroicon-o-signal')
                ->color('info')
                ->action('testConnection'),
            
            Action::make('sendTestMessage')
                ->label('Send Test Message')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->action('sendTestMessage')
                ->requiresConfirmation()
                ->modalHeading('Send Test Message')
                ->modalDescription('This will send a test message to the phone number specified in the Testing tab.')
                ->modalSubmitActionLabel('Send Message'),
        ];
    }



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
            'test_phone_number' => '', // Initialize test fields
            'test_message' => 'This is a test message from your WhatsApp chat system.',
            'test_message_type' => 'template',
            'test_template' => 'hello_world',
            'use_dynamic_parameters' => true,
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
                                            ->dehydrated() // Always include in form data
                                            ->visible(fn (Forms\Get $get) => $get('business_hours_enabled')),
                                        
                                        Forms\Components\TimePicker::make('business_hours_end')
                                            ->label('Business Hours End')
                                            ->default('17:00')
                                            ->dehydrated() // Always include in form data
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
                                    ->description('Test your WhatsApp API configuration with static or dynamic content')
                                    ->schema([
                                        Forms\Components\Placeholder::make('test_connection')
                                            ->label('Connection Status')
                                            ->content(fn () => new \Illuminate\Support\HtmlString($this->getConnectionStatus())),
                                        
                                        Forms\Components\Placeholder::make('ssl_info')
                                            ->label('Development Environment Notice')
                                            ->content(fn () => new \Illuminate\Support\HtmlString(
                                                app()->environment(['local', 'development', 'testing']) 
                                                    ? '<div class="text-sm text-amber-600 bg-amber-50 p-3 rounded-lg border border-amber-200">
                                                        <strong>🔧 Development Mode:</strong> SSL certificate verification is automatically disabled for local testing. 
                                                        This resolves common "SSL certificate problem" errors in development environments.
                                                       </div>'
                                                    : '<div class="text-sm text-green-600 bg-green-50 p-3 rounded-lg border border-green-200">
                                                        <strong>🔒 Production Mode:</strong> SSL certificate verification is enabled for secure connections.
                                                       </div>'
                                            ))
                                            ->visible(fn () => true),
                                        
                                        Forms\Components\TextInput::make('test_phone_number')
                                            ->label('Test Phone Number')
                                            ->placeholder('+1234567890')
                                            ->helperText('Phone number to send test message to (include country code)')
                                            ->dehydrated(),
                                        
                                        Forms\Components\Select::make('test_message_type')
                                            ->label('Message Type')
                                            ->options([
                                                'text' => '📝 Free Text Message (Test Mode Only - Won\'t Deliver)',
                                                'template' => '✅ Template Message (Works Now - Real Delivery!)'
                                            ])
                                            ->default('template')
                                            ->reactive()
                                            ->helperText('Template messages will be delivered to WhatsApp even in test mode!')
                                            ->dehydrated(),
                                        
                                        Forms\Components\Textarea::make('test_message')
                                            ->label('Test Message')
                                            ->placeholder('This is a test message from your WhatsApp chat system.')
                                            ->rows(3)
                                            ->visible(fn (Forms\Get $get) => $get('test_message_type') === 'text')
                                            ->dehydrated(),
                                        
                                        Forms\Components\Select::make('test_template')
                                            ->label('Template Message')
                                            ->options(fn () => $this->getAvailableTemplates())
                                            ->default('hello_world')
                                            ->helperText('Template messages work in both test and production mode and will be delivered to WhatsApp')
                                            ->visible(fn (Forms\Get $get) => $get('test_message_type') === 'template')
                                            ->reactive()
                                            ->dehydrated(),
                                        
                                        Forms\Components\Toggle::make('use_dynamic_parameters')
                                            ->label('Use Dynamic Parameters')
                                            ->helperText('Include business information from chat settings in template messages')
                                            ->default(true)
                                            ->visible(fn (Forms\Get $get) => $get('test_message_type') === 'template')
                                            ->reactive()
                                            ->dehydrated(),
                                        
                                        Forms\Components\Placeholder::make('template_preview')
                                            ->label('Template Preview')
                                            ->content(fn (Forms\Get $get) => new \Illuminate\Support\HtmlString($this->getTemplatePreview($get)))
                                            ->visible(fn (Forms\Get $get) => $get('test_message_type') === 'template')
                                            ->columnSpanFull(),
                                    ])->columns(1),
                                
                                Forms\Components\Section::make('Dynamic Template Parameters')
                                    ->description('How dynamic parameters work with your chat settings')
                                    ->schema([
                                        Forms\Components\Placeholder::make('dynamic_explanation')
                                            ->label('Dynamic Content Features')
                                            ->content(fn () => new \Illuminate\Support\HtmlString('
                                                <div class="space-y-4 text-sm">
                                                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                                        <h4 class="font-semibold text-blue-800 mb-2">🎯 Dynamic Parameters</h4>
                                                        <ul class="list-disc list-inside text-blue-700 space-y-1">
                                                            <li><strong>Welcome Message:</strong> Uses your configured welcome message</li>
                                                            <li><strong>Business Hours:</strong> Automatically includes your business hours</li>
                                                            <li><strong>Auto-Update:</strong> Changes when you update chat settings</li>
                                                            <li><strong>Personalized:</strong> Each message reflects your current configuration</li>
                                                        </ul>
                                                    </div>
                                                    
                                                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                                                        <h4 class="font-semibold text-green-800 mb-2">✅ Current Dynamic Values</h4>
                                                        <ul class="list-disc list-inside text-green-700 space-y-1">
                                                            <li><strong>Welcome:</strong> "' . htmlspecialchars(ChatSetting::get('welcome_message', 'Hi! 👋 How can we help you today?')) . '"</li>
                                                            <li><strong>Business Hours:</strong> ' . (ChatSetting::get('business_hours_enabled', 'false') === 'true' 
                                                                ? ChatSetting::get('business_hours_start', '09:00') . ' - ' . ChatSetting::get('business_hours_end', '17:00')
                                                                : 'Always available') . '</li>
                                                            <li><strong>Auto-Reply:</strong> ' . (ChatSetting::get('auto_reply_enabled', 'true') === 'true' ? 'Enabled' : 'Disabled') . '</li>
                                                        </ul>
                                                    </div>
                                                    
                                                    <div class="bg-amber-50 p-4 rounded-lg border border-amber-200">
                                                        <h4 class="font-semibold text-amber-800 mb-2">⚙️ How It Works</h4>
                                                        <ul class="list-disc list-inside text-amber-700 space-y-1">
                                                            <li>Template messages can include variable parameters</li>
                                                            <li>Parameters are filled with data from your chat settings</li>
                                                            <li>If custom template fails, falls back to "hello_world"</li>
                                                            <li>Perfect for personalized automated responses</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            ')),
                                    ])->columns(1),
                                
                                Forms\Components\Section::make('Test Mode vs Production Mode')
                                    ->description('Understanding WhatsApp Business API limitations')
                                    ->schema([
                                        Forms\Components\Placeholder::make('mode_explanation')
                                            ->label('Why am I not receiving messages?')
                                            ->content(fn () => new \Illuminate\Support\HtmlString('
                                                <div class="space-y-4 text-sm">
                                                    <div class="bg-amber-50 p-4 rounded-lg border border-amber-200">
                                                        <h4 class="font-semibold text-amber-800 mb-2">🧪 Test Mode (Current Status)</h4>
                                                        <ul class="list-disc list-inside text-amber-700 space-y-1">
                                                            <li><strong>Messages don\'t reach actual WhatsApp</strong> - they exist in Meta\'s system only</li>
                                                            <li>Can only send to test numbers added in Meta Business Manager</li>
                                                            <li>Other numbers will get "not allowed" error</li>
                                                            <li>Perfect for development and testing your integration</li>
                                                            <li>Message IDs are generated but no real delivery occurs</li>
                                                        </ul>
                                                    </div>
                                                    
                                                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                                                        <h4 class="font-semibold text-green-800 mb-2">✅ Production Mode (Go Live)</h4>
                                                        <ul class="list-disc list-inside text-green-700 space-y-1">
                                                            <li><strong>Messages reach actual WhatsApp users</strong></li>
                                                            <li>Can send to any WhatsApp number (with conversation rules)</li>
                                                            <li>Requires business verification and approval</li>
                                                            <li>Must comply with WhatsApp\'s messaging policies</li>
                                                            <li>Real message delivery and status updates</li>
                                                        </ul>
                                                    </div>
                                                    
                                                    <div class="bg-cyan-50 p-4 rounded-lg border border-cyan-200">
                                                        <h4 class="font-semibold text-cyan-800 mb-2">🎯 Template Messages (Works Now!)</h4>
                                                        <ul class="list-disc list-inside text-cyan-700 space-y-1">
                                                            <li><strong>Template messages work in both test and production mode</strong></li>
                                                            <li>The "hello_world" template is pre-approved by Meta</li>
                                                            <li>You should receive template messages on your WhatsApp</li>
                                                            <li>Use the "Template Message" option above to test real delivery</li>
                                                            <li>Perfect for testing while waiting for production approval</li>
                                                        </ul>
                                                    </div>
                                                    
                                                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                                        <h4 class="font-semibold text-blue-800 mb-2">🚀 How to Go Live (Production)</h4>
                                                        <ol class="list-decimal list-inside text-blue-700 space-y-1">
                                                            <li>Complete <strong>Business Verification</strong> in Meta Business Manager</li>
                                                            <li>Verify your <strong>WhatsApp Business Phone Number</strong></li>
                                                            <li>Create and get approval for <strong>Message Templates</strong></li>
                                                            <li>Submit your app for <strong>App Review</strong> (if required)</li>
                                                            <li>Request <strong>Production Access</strong> in Meta Developer Console</li>
                                                            <li>Wait for Meta\'s approval (can take 1-7 days)</li>
                                                        </ol>
                                                    </div>
                                                    
                                                    <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                                                        <h4 class="font-semibold text-purple-800 mb-2">📋 Production Requirements</h4>
                                                        <ul class="list-disc list-inside text-purple-700 space-y-1">
                                                            <li>Valid business registration documents</li>
                                                            <li>Business website with privacy policy</li>
                                                            <li>Clear use case description</li>
                                                            <li>Compliance with WhatsApp Business Policy</li>
                                                            <li>Message templates for proactive messaging</li>
                                                        </ul>
                                                    </div>
                                                    
                                                    <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                                                        <h4 class="font-semibold text-red-800 mb-2">⚠️ Important Notes</h4>
                                                        <ul class="list-disc list-inside text-red-700 space-y-1">
                                                            <li>Even in production, you can only send free messages to users who messaged you first</li>
                                                            <li>For proactive messaging, you must use approved templates</li>
                                                            <li>Conversation window is 24 hours from last user message</li>
                                                            <li>Violating policies can result in account suspension</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            ')),
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
            ChatSetting::set('business_hours_start', $data['business_hours_start'] ?? '09:00', false, 'Business Hours Start Time');
            ChatSetting::set('business_hours_end', $data['business_hours_end'] ?? '17:00', false, 'Business Hours End Time');
            ChatSetting::set('away_message', $data['away_message'] ?? '', false, 'Away Message');
            ChatSetting::set('welcome_message', $data['welcome_message'] ?? '', false, 'Welcome Message');
            
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
                $errorMessage = $result['error'] ?? 'Unknown error';
                
                // Provide helpful error messages for common issues
                if (str_contains($errorMessage, 'SSL certificate')) {
                    $errorMessage .= ' (SSL certificate issue - this is common in development environments)';
                } elseif (str_contains($errorMessage, 'Invalid access token')) {
                    $errorMessage = 'Invalid access token. Please check your WhatsApp API credentials.';
                } elseif (str_contains($errorMessage, 'Phone number not found')) {
                    $errorMessage = 'Phone Number ID not found. Please verify your Phone Number ID.';
                }
                
                Notification::make()
                    ->title('Connection Failed')
                    ->body($errorMessage)
                    ->danger()
                    ->send();
            }
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            
            // Provide helpful error messages for common cURL issues
            if (str_contains($errorMessage, 'SSL certificate problem')) {
                $errorMessage = 'SSL Certificate Error: This is common in development environments. The system will automatically handle this for local testing.';
            } elseif (str_contains($errorMessage, 'Could not resolve host')) {
                $errorMessage = 'Network Error: Unable to connect to WhatsApp servers. Please check your internet connection.';
            }
            
            Notification::make()
                ->title('Connection Error')
                ->body($errorMessage)
                ->danger()
                ->send();
        }
    }

    public function sendTestMessage(): void
    {
        $data = $this->form->getState();
        
        $testPhoneNumber = $data['test_phone_number'] ?? '';
        $messageType = $data['test_message_type'] ?? 'template';
        
        if (empty($testPhoneNumber)) {
            Notification::make()
                ->title('Missing Information')
                ->body('Please enter a phone number.')
                ->warning()
                ->send();
            return;
        }
        
        // Validate based on message type
        if ($messageType === 'text') {
            $testMessage = $data['test_message'] ?? '';
            if (empty($testMessage)) {
                Notification::make()
                    ->title('Missing Information')
                    ->body('Please enter a test message.')
                    ->warning()
                    ->send();
                return;
            }
        } else {
            $templateName = $data['test_template'] ?? 'hello_world';
            $useDynamicParams = $data['use_dynamic_parameters'] ?? false;
        }
        
        try {
            $whatsappService = app(WhatsAppCloudService::class);
            
            // Send different message types
            if ($messageType === 'template') {
                // Prepare dynamic parameters if enabled
                $templateParams = [];
                if ($useDynamicParams && $templateName === 'custom_welcome') {
                    $templateParams = $this->getDynamicTemplateParameters();
                }
                
                $result = $whatsappService->sendTemplateWithFallback($testPhoneNumber, $templateName, $templateParams);
                $messageTypeLabel = 'Template Message' . ($useDynamicParams ? ' (Dynamic)' : '');
            } else {
                $result = $whatsappService->sendTestMessageWithDebug($testPhoneNumber, $testMessage);
                $messageTypeLabel = 'Text Message';
            }
            
            if (isset($result['messages'][0]['id'])) {
                $messageId = $result['messages'][0]['id'];
                
                $bodyMessage = "Message ID: {$messageId}\n\n";
                if ($messageType === 'template') {
                    $bodyMessage .= "🎉 SUCCESS! Template message sent and should be delivered to WhatsApp!\n\n";
                    $bodyMessage .= "✅ Check your WhatsApp now - you should see the message\n";
                    $bodyMessage .= "✅ Template messages work even in test mode\n";
                    $bodyMessage .= "✅ No conversation window required";
                } else {
                    $bodyMessage .= "⚠️ Text messages only work in production mode.\nCheck troubleshooting tips if you don't receive it.";
                }
                
                Notification::make()
                    ->title("{$messageTypeLabel} Sent Successfully!")
                    ->body($bodyMessage)
                    ->success()
                    ->duration(12000) // Show for 12 seconds
                    ->send();
                    
                // Log additional debugging info
                Log::info('WhatsApp test message sent successfully', [
                    'phone_number' => $testPhoneNumber,
                    'message_type' => $messageType,
                    'template_name' => $templateName ?? null,
                    'message_id' => $messageId,
                    'user_id' => auth()->id()
                ]);
            } else {
                Notification::make()
                    ->title('Send Failed')
                    ->body('Failed to send test message. Please check your configuration.')
                    ->danger()
                    ->send();
            }
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            
            // Provide helpful error messages for common issues
            if (str_contains($errorMessage, 'SSL certificate problem')) {
                $errorMessage = 'SSL Certificate Error: This is handled automatically in development. If you\'re in production, please ensure SSL certificates are properly configured.';
            } elseif (str_contains($errorMessage, 'Invalid phone number')) {
                $errorMessage = 'Invalid phone number format. Please use international format (e.g., +1234567890).';
            } elseif (str_contains($errorMessage, 'Rate limit')) {
                $errorMessage = 'Rate limit exceeded. Please wait a moment before sending another message.';
            }
            
            Notification::make()
                ->title('Send Error')
                ->body($errorMessage)
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
                    $status = "🟢 Connected - {$result['verified_name']}";
                    
                    // Check if in production mode
                    try {
                        $isProduction = $whatsappService->isProductionMode();
                        if ($isProduction) {
                            $status .= '<br><span class="text-green-600 font-semibold">✅ Production Mode - Can send to any number</span>';
                        } else {
                            $status .= '<br><span class="text-amber-600 font-semibold">🧪 Test Mode - Limited to test numbers only</span>';
                        }
                    } catch (\Exception $e) {
                        $status .= '<br><span class="text-gray-500">⚠️ Could not determine account mode</span>';
                    }
                    
                    return $status;
                } else {
                    return '🟡 Configuration Error - Invalid response format';
                }
            }
            
            return '🟡 Configuration Error - Unexpected response format';
        } catch (\Exception $e) {
            return '🔴 Connection Failed - ' . $e->getMessage();
        }
    }

    private function getAvailableTemplates(): array
    {
        try {
            $whatsappService = app(WhatsAppCloudService::class);
            
            if (!$whatsappService->isConfigured()) {
                return [
                    'hello_world' => 'Hello World (Default Template)',
                    'custom_welcome' => 'Custom Welcome (Dynamic Parameters)',
                ];
            }
            
            $templates = $whatsappService->getMessageTemplates();
            $options = [
                'hello_world' => 'Hello World (Default Template)',
                'custom_welcome' => 'Custom Welcome (Dynamic Parameters)',
            ];
            
            if (isset($templates['data']) && is_array($templates['data'])) {
                foreach ($templates['data'] as $template) {
                    if (isset($template['name']) && $template['status'] === 'APPROVED') {
                        $options[$template['name']] = $template['name'] . ' (' . ($template['category'] ?? 'Template') . ')';
                    }
                }
            }
            
            return $options;
        } catch (\Exception $e) {
            Log::warning('Could not fetch WhatsApp templates', ['error' => $e->getMessage()]);
            return [
                'hello_world' => 'Hello World (Default Template)',
                'custom_welcome' => 'Custom Welcome (Dynamic Parameters)',
            ];
        }
    }

    private function getTemplatePreview(Forms\Get $get): string
    {
        $templateName = $get('test_template') ?? 'hello_world';
        $useDynamic = $get('use_dynamic_parameters') ?? false;
        
        $preview = '<div class="bg-gray-50 p-4 rounded-lg border">';
        $preview .= '<h5 class="font-semibold text-gray-800 mb-2">📱 WhatsApp Message Preview:</h5>';
        
        if ($templateName === 'hello_world') {
            $preview .= '<div class="bg-white p-3 rounded border-l-4 border-green-500">';
            $preview .= '<p class="text-gray-800">Hello World, welcome to WhatsApp!</p>';
            $preview .= '</div>';
            $preview .= '<p class="text-sm text-gray-600 mt-2">ℹ️ This is Meta\'s default template - no parameters needed</p>';
        } elseif ($templateName === 'custom_welcome') {
            $preview .= '<div class="bg-white p-3 rounded border-l-4 border-blue-500">';
            if ($useDynamic) {
                $welcomeMessage = ChatSetting::get('welcome_message', 'Hi! 👋 How can we help you today?');
                $businessHours = ChatSetting::get('business_hours_enabled', 'false') === 'true' 
                    ? ChatSetting::get('business_hours_start', '09:00') . ' - ' . ChatSetting::get('business_hours_end', '17:00')
                    : 'Always available';
                
                $preview .= '<p class="text-gray-800">' . htmlspecialchars($welcomeMessage) . '</p>';
                $preview .= '<p class="text-gray-600 text-sm mt-2">Business Hours: ' . htmlspecialchars($businessHours) . '</p>';
                $preview .= '<p class="text-gray-600 text-sm">Contact us anytime!</p>';
            } else {
                $preview .= '<p class="text-gray-800">Welcome to our business!</p>';
                $preview .= '<p class="text-gray-600 text-sm mt-2">How can we help you today?</p>';
            }
            $preview .= '</div>';
            $preview .= '<p class="text-sm text-gray-600 mt-2">ℹ️ Custom template with ' . ($useDynamic ? 'dynamic' : 'static') . ' content</p>';
        } else {
            $preview .= '<div class="bg-white p-3 rounded border-l-4 border-purple-500">';
            $preview .= '<p class="text-gray-800">Template: ' . htmlspecialchars($templateName) . '</p>';
            $preview .= '</div>';
            $preview .= '<p class="text-sm text-gray-600 mt-2">ℹ️ Custom template from your WhatsApp Business account</p>';
        }
        
        $preview .= '</div>';
        
        return $preview;
    }

    private function getDynamicTemplateParameters(): array
    {
        // Get dynamic data from chat settings
        $welcomeMessage = ChatSetting::get('welcome_message', 'Hi! 👋 How can we help you today?');
        $businessHoursEnabled = ChatSetting::get('business_hours_enabled', 'false') === 'true';
        
        $businessHours = 'Always available';
        if ($businessHoursEnabled) {
            $startTime = ChatSetting::get('business_hours_start', '09:00');
            $endTime = ChatSetting::get('business_hours_end', '17:00');
            $businessHours = "{$startTime} - {$endTime}";
        }
        
        // Return parameters for template
        return [
            $welcomeMessage,
            $businessHours,
            'Contact us anytime!'
        ];
    }
}
