<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class MailSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    
    protected static ?string $navigationLabel = 'Mail Settings';
    
    protected static ?string $navigationGroup = 'Mail';
    
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.mail-settings';

    public ?array $data = [];

    /**
     * Mount the page and load existing mail settings from .env
     */
    public function mount(): void
    {
        $this->form->fill([
            'mail_mailer' => env('MAIL_MAILER', 'smtp'),
            'mail_host' => env('MAIL_HOST', 'smtp.mailtrap.io'),
            'mail_port' => env('MAIL_PORT', '2525'),
            'mail_username' => env('MAIL_USERNAME', ''),
            'mail_password' => env('MAIL_PASSWORD', ''),
            'mail_encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'mail_from_address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
            'mail_from_name' => env('MAIL_FROM_NAME', env('APP_NAME')),
            'imap_host' => env('IMAP_HOST', 'imap.gmail.com'),
            'imap_port' => env('IMAP_PORT', '993'),
            'imap_username' => env('IMAP_USERNAME', ''),
            'imap_password' => env('IMAP_PASSWORD', ''),
            'imap_encryption' => env('IMAP_ENCRYPTION', 'ssl'),
        ]);
    }

    /**
     * Define the form schema
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Mail Driver Configuration')
                    ->description('Configure your mail service provider')
                    ->schema([
                        Forms\Components\Select::make('mail_mailer')
                            ->label('Mail Driver')
                            ->options([
                                'smtp' => 'SMTP',
                                'sendmail' => 'Sendmail',
                                'mailgun' => 'Mailgun',
                                'ses' => 'Amazon SES',
                                'postmark' => 'Postmark',
                                'log' => 'Log (Development)',
                            ])
                            ->required()
                            ->reactive()
                            ->helperText('Select your preferred mail service'),
                    ]),

                Forms\Components\Section::make('SMTP Settings')
                    ->description('Configure SMTP server details')
                    ->schema([
                        Forms\Components\TextInput::make('mail_host')
                            ->label('Mail Host')
                            ->required()
                            ->placeholder('smtp.gmail.com'),

                        Forms\Components\TextInput::make('mail_port')
                            ->label('Mail Port')
                            ->required()
                            ->numeric()
                            ->placeholder('587'),

                        Forms\Components\TextInput::make('mail_username')
                            ->label('Mail Username')
                            ->placeholder('your-email@gmail.com'),

                        Forms\Components\TextInput::make('mail_password')
                            ->label('Mail Password')
                            ->password()
                            ->revealable()
                            ->placeholder('Your app password'),

                        Forms\Components\Select::make('mail_encryption')
                            ->label('Encryption')
                            ->options([
                                'tls' => 'TLS',
                                'ssl' => 'SSL',
                                'none' => 'None',
                            ])
                            ->required()
                            ->helperText('Recommended: TLS for port 587, SSL for port 465'),
                    ])
                    ->columns(2)
                    ->visible(fn (Forms\Get $get) => $get('mail_mailer') === 'smtp'),

                Forms\Components\Section::make('Sender Information')
                    ->description('Default sender details for all outgoing emails')
                    ->schema([
                        Forms\Components\TextInput::make('mail_from_address')
                            ->label('From Email Address')
                            ->email()
                            ->required()
                            ->placeholder('noreply@example.com'),

                        Forms\Components\TextInput::make('mail_from_name')
                            ->label('From Name')
                            ->required()
                            ->placeholder('GEOMETRIC Development'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('IMAP Settings (For Inbox Viewer)')
                    ->description('Configure IMAP to view received emails in admin panel')
                    ->schema([
                        Forms\Components\TextInput::make('imap_host')
                            ->label('IMAP Host')
                            ->placeholder('imap.gmail.com')
                            ->helperText('For Gmail: imap.gmail.com'),

                        Forms\Components\TextInput::make('imap_port')
                            ->label('IMAP Port')
                            ->numeric()
                            ->placeholder('993')
                            ->helperText('Usually 993 for SSL or 143 for TLS'),

                        Forms\Components\TextInput::make('imap_username')
                            ->label('IMAP Username')
                            ->placeholder('your-email@gmail.com')
                            ->helperText('Usually your email address'),

                        Forms\Components\TextInput::make('imap_password')
                            ->label('IMAP Password')
                            ->password()
                            ->revealable()
                            ->placeholder('Your email password or app password')
                            ->helperText('For Gmail, use App Password'),

                        Forms\Components\Select::make('imap_encryption')
                            ->label('Encryption')
                            ->options([
                                'ssl' => 'SSL',
                                'tls' => 'TLS',
                                'none' => 'None',
                            ])
                            ->default('ssl')
                            ->helperText('SSL for port 993, TLS for port 143'),
                    ])
                    ->columns(2)
                    ->collapsed(),

                Forms\Components\Section::make('Test Email')
                    ->description('Send a test email to verify your configuration')
                    ->schema([
                        Forms\Components\TextInput::make('test_email')
                            ->label('Test Email Address')
                            ->email()
                            ->placeholder('test@example.com')
                            ->helperText('Enter an email address to receive a test email'),
                    ]),
            ])
            ->statePath('data');
    }

    /**
     * Get form actions
     */
    protected function getFormActions(): array
    {
        return [
            \Filament\Actions\Action::make('save')
                ->label('Save Configuration')
                ->icon('heroicon-o-check')
                ->action('save'),
            
            \Filament\Actions\Action::make('test')
                ->label('Send Test Email')
                ->icon('heroicon-o-paper-airplane')
                ->color('info')
                ->requiresConfirmation()
                ->action('sendTestEmail'),
        ];
    }

    /**
     * Save the mail settings to .env file
     */
    public function save(): void
    {
        $data = $this->form->getState();

        // Update .env file
        $this->updateEnvFile([
            'MAIL_MAILER' => $data['mail_mailer'],
            'MAIL_HOST' => $data['mail_host'],
            'MAIL_PORT' => $data['mail_port'],
            'MAIL_USERNAME' => $data['mail_username'],
            'MAIL_PASSWORD' => $data['mail_password'],
            'MAIL_ENCRYPTION' => $data['mail_encryption'],
            'MAIL_FROM_ADDRESS' => $data['mail_from_address'],
            'MAIL_FROM_NAME' => $data['mail_from_name'],
            'IMAP_HOST' => $data['imap_host'] ?? '',
            'IMAP_PORT' => $data['imap_port'] ?? '993',
            'IMAP_USERNAME' => $data['imap_username'] ?? '',
            'IMAP_PASSWORD' => $data['imap_password'] ?? '',
            'IMAP_ENCRYPTION' => $data['imap_encryption'] ?? 'ssl',
        ]);

        // Clear config cache
        Artisan::call('config:clear');

        Notification::make()
            ->title('Settings saved successfully!')
            ->body('Mail configuration has been updated. Please test your settings.')
            ->success()
            ->send();
    }

    /**
     * Send a test email
     */
    public function sendTestEmail(): void
    {
        $data = $this->form->getState();

        if (empty($data['test_email'])) {
            Notification::make()
                ->title('Email Required')
                ->body('Please enter a test email address.')
                ->warning()
                ->send();
            return;
        }

        try {
            \Illuminate\Support\Facades\Mail::raw(
                'This is a test email from GEOMETRIC Development CMS. If you received this, your mail configuration is working correctly!',
                function ($message) use ($data) {
                    $message->to($data['test_email'])
                        ->subject('Test Email - GEOMETRIC Development');
                }
            );

            Notification::make()
                ->title('Test Email Sent!')
                ->body("A test email has been sent to {$data['test_email']}")
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Test Email Failed')
                ->body("Error: {$e->getMessage()}")
                ->danger()
                ->send();
        }
    }

    /**
     * Update .env file with new values
     */
    protected function updateEnvFile(array $data): void
    {
        $envPath = base_path('.env');

        if (!File::exists($envPath)) {
            return;
        }

        $envContent = File::get($envPath);

        foreach ($data as $key => $value) {
            $key = strtoupper($key);
            
            // Escape special characters in value
            $escapedValue = $this->escapeEnvValue($value);

            // Check if key exists
            if (preg_match("/^{$key}=/m", $envContent)) {
                // Update existing key
                $envContent = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}={$escapedValue}",
                    $envContent
                );
            } else {
                // Add new key at the end
                $envContent .= "\n{$key}={$escapedValue}";
            }
        }

        File::put($envPath, $envContent);
    }

    /**
     * Escape special characters in .env values
     */
    protected function escapeEnvValue(string $value): string
    {
        // If value contains spaces or special characters, wrap in quotes
        if (preg_match('/\s/', $value) || preg_match('/[#\$&]/', $value)) {
            return '"' . str_replace('"', '\\"', $value) . '"';
        }

        return $value;
    }
}
