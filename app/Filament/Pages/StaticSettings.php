<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;

class StaticSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Static Settings';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.pages.static-settings';

    public ?array $data = [];

    /**
     * Mount the page and load existing settings
     */
    public function mount(): void
    {
        $this->form->fill($this->getSettingsData());
    }

    /**
     * Get all settings data
     */
    protected function getSettingsData(): array
    {
        return [
            // Branding
            'site_name' => Setting::get('site_name', 'Geometric Development'),
            'site_tagline' => Setting::get('site_tagline', 'Leading Community Developer'),
            'logo_dark' => Setting::get('logo_dark', 'assets/img/logo/logo_dark.png'),
            'logo_light' => Setting::get('logo_light', 'assets/img/logo/logo_light.png'),
            'favicon' => Setting::get('favicon', 'assets/img/favicon.ico'),

            // Footer Design
            'footer_bg_image' => Setting::get('footer_bg_image', 'assets/img/footer/f4-bg-1.png'),
            'footer_bg_color' => Setting::get('footer_bg_color', '#1a1a1a'),
            'footer_text_color' => Setting::get('footer_text_color', '#ffffff'),
            'footer_copyright_text' => Setting::get('footer_copyright_text', 'Geometric-Development'),
            'footer_contact_title' => Setting::get('footer_contact_title', 'contact us'),

            // Primary Contact
            'contact_email_label' => Setting::get('contact_email_label', 'Email'),
            'contact_email' => Setting::get('contact_email', 'info@geometric-development.com'),
            'contact_phone_label' => Setting::get('contact_phone_label', 'phone'),
            'phone_1' => Setting::get('phone_1', '+20 127 2777919'),
            'phone_1_whatsapp' => Setting::get('phone_1_whatsapp', '201272777919'),
            'phone_2' => Setting::get('phone_2', '+20 120 0111338'),
            'phone_2_whatsapp' => Setting::get('phone_2_whatsapp', '201200111338'),
            'contact_address_label' => Setting::get('contact_address_label', 'Address'),
            'address' => Setting::get('address', '6 October - Sheikh Zayed'),
            'address_map_url' => Setting::get('address_map_url', 'https://maps.google.com/?q=6+October+Sheikh+Zayed+Egypt'),

            // Additional Contact Items
            'additional_contact_items' => $this->decodeJsonSetting(Setting::get('additional_contact_items', '[]')),

            // Social Media
            'facebook_url' => Setting::get('facebook_url', ''),
            'instagram_url' => Setting::get('instagram_url', ''),
            'twitter_url' => Setting::get('twitter_url', ''),
            'linkedin_url' => Setting::get('linkedin_url', ''),
            'youtube_url' => Setting::get('youtube_url', ''),
            'tiktok_url' => Setting::get('tiktok_url', ''),

            // Additional Contacts
            'additional_contacts' => $this->decodeJsonSetting(Setting::get('additional_contacts', '[]')),

            // SEO
            'meta_description' => Setting::get('meta_description', ''),
            'meta_keywords' => Setting::get('meta_keywords', ''),

            // Navbar
            'navbar_button_text_1' => Setting::get('navbar_button_text_1', 'request a quote'),
            'navbar_button_text_2' => Setting::get('navbar_button_text_2', 'Contact us'),
            'navbar_button_link' => Setting::get('navbar_button_link', 'contact.index'),
        ];
    }

    /**
     * Define the form schema
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Settings')
                    ->tabs([
                        // Branding Tab
                        Forms\Components\Tabs\Tab::make('🎨 Branding')
                            ->schema([
                                Forms\Components\Section::make('Site Identity')
                                    ->description('Configure your site name, tagline, and logos')
                                    ->schema([
                                        Forms\Components\TextInput::make('site_name')
                                            ->label('Site Name')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('Geometric Development'),

                                        Forms\Components\TextInput::make('site_tagline')
                                            ->label('Site Tagline')
                                            ->maxLength(255)
                                            ->placeholder('Leading Community Developer'),

                                        Forms\Components\FileUpload::make('logo_dark')
                                            ->label('Dark Logo (for light backgrounds)')
                                            ->image()
                                            ->directory('logos')
                                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/svg+xml'])
                                            ->maxSize(2048)
                                            ->helperText('Used in navbar and light sections. Recommended: PNG with transparency'),

                                        Forms\Components\FileUpload::make('logo_light')
                                            ->label('Light Logo (for dark backgrounds)')
                                            ->image()
                                            ->directory('logos')
                                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/svg+xml'])
                                            ->maxSize(2048)
                                            ->helperText('Used in footer and dark sections. Recommended: PNG with transparency'),

                                        Forms\Components\FileUpload::make('favicon')
                                            ->label('Favicon')
                                            ->image()
                                            ->directory('branding')
                                            ->acceptedFileTypes(['image/x-icon', 'image/png'])
                                            ->maxSize(1024)
                                            ->helperText('Browser tab icon. Recommended: 32x32px or 64x64px'),
                                    ])
                                    ->columns(2),
                            ]),

                        // Footer Design Tab
                        Forms\Components\Tabs\Tab::make('🎨 Footer Design')
                            ->schema([
                                Forms\Components\Section::make('Footer Appearance')
                                    ->description('Customize footer background, colors, and text')
                                    ->schema([
                                        Forms\Components\FileUpload::make('footer_bg_image')
                                            ->label('Footer Background Image')
                                            ->image()
                                            ->directory('footer')
                                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp'])
                                            ->maxSize(5120)
                                            ->helperText('Optional: Upload a background image for the footer'),

                                        Forms\Components\ColorPicker::make('footer_bg_color')
                                            ->label('Footer Background Color')
                                            ->helperText('Fallback color or overlay if using background image'),

                                        Forms\Components\ColorPicker::make('footer_text_color')
                                            ->label('Footer Text Color'),

                                        Forms\Components\TextInput::make('footer_contact_title')
                                            ->label('Contact Section Title')
                                            ->maxLength(100)
                                            ->placeholder('contact us'),

                                        Forms\Components\TextInput::make('footer_copyright_text')
                                            ->label('Copyright Text')
                                            ->maxLength(255)
                                            ->placeholder('Geometric-Development')
                                            ->helperText('Appears in footer: © 2025, [Your Text] All Rights Reserved'),
                                    ])
                                    ->columns(2),
                            ]),

                        // Contact Information Tab
                        Forms\Components\Tabs\Tab::make('📧 Contact Information')
                            ->schema([
                                Forms\Components\Section::make('Primary Contact Details')
                                    ->description('Main contact information displayed across the site')
                                    ->schema([
                                        Forms\Components\TextInput::make('contact_email_label')
                                            ->label('Email Section Label')
                                            ->maxLength(50)
                                            ->placeholder('Email')
                                            ->helperText('Label shown above email in footer'),

                                        Forms\Components\TextInput::make('contact_email')
                                            ->label('Email Address')
                                            ->email()
                                            ->placeholder('info@example.com')
                                            ->prefixIcon('heroicon-o-envelope')
                                            ->helperText('Leave empty to hide email section'),

                                        Forms\Components\TextInput::make('contact_phone_label')
                                            ->label('Phone Section Label')
                                            ->maxLength(50)
                                            ->placeholder('phone')
                                            ->helperText('Label shown above phone numbers in footer')
                                            ->columnSpanFull(),

                                        Forms\Components\TextInput::make('phone_1')
                                            ->label('Phone Number 1')
                                            ->tel()
                                            ->placeholder('+20 127 2777919')
                                            ->prefixIcon('heroicon-o-phone')
                                            ->helperText('Leave empty to hide'),

                                        Forms\Components\TextInput::make('phone_1_whatsapp')
                                            ->label('WhatsApp Number 1')
                                            ->tel()
                                            ->placeholder('201272777919')
                                            ->helperText('Without + or spaces (e.g., 201272777919)')
                                            ->prefixIcon('heroicon-o-chat-bubble-left-right'),

                                        Forms\Components\TextInput::make('phone_2')
                                            ->label('Phone Number 2')
                                            ->tel()
                                            ->placeholder('+20 120 0111338')
                                            ->prefixIcon('heroicon-o-phone')
                                            ->helperText('Leave empty to hide'),

                                        Forms\Components\TextInput::make('phone_2_whatsapp')
                                            ->label('WhatsApp Number 2')
                                            ->tel()
                                            ->placeholder('201200111338')
                                            ->helperText('Without + or spaces')
                                            ->prefixIcon('heroicon-o-chat-bubble-left-right'),

                                        Forms\Components\TextInput::make('contact_address_label')
                                            ->label('Address Section Label')
                                            ->maxLength(50)
                                            ->placeholder('Address')
                                            ->helperText('Label shown above address in footer')
                                            ->columnSpanFull(),

                                        Forms\Components\Textarea::make('address')
                                            ->label('Physical Address')
                                            ->rows(2)
                                            ->placeholder('6 October - Sheikh Zayed')
                                            ->helperText('Leave empty to hide address section')
                                            ->columnSpanFull(),

                                        Forms\Components\TextInput::make('address_map_url')
                                            ->label('Google Maps URL')
                                            ->url()
                                            ->placeholder('https://maps.google.com/?q=...')
                                            ->prefixIcon('heroicon-o-map-pin')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),

                                Forms\Components\Section::make('Additional Contact Items')
                                    ->description('Add custom contact fields (Fax, Mobile, WhatsApp, Telegram, etc.)')
                                    ->schema([
                                        Forms\Components\Repeater::make('additional_contact_items')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\TextInput::make('label')
                                                    ->label('Label')
                                                    ->required()
                                                    ->placeholder('Fax / Mobile / WhatsApp / Telegram / Skype')
                                                    ->helperText('Title shown in footer (e.g., "Fax", "Mobile", "WhatsApp")'),

                                                Forms\Components\TextInput::make('value')
                                                    ->label('Value')
                                                    ->required()
                                                    ->placeholder('+1 234 567 8900 / username@skype')
                                                    ->helperText('The actual value to display'),

                                                Forms\Components\TextInput::make('link_url')
                                                    ->label('Link URL (Optional)')
                                                    ->url()
                                                    ->placeholder('https://wa.me/123456789 or tel:+123456789')
                                                    ->helperText('Leave empty for non-clickable text'),

                                                Forms\Components\Toggle::make('open_in_new_tab')
                                                    ->label('Open in New Tab')
                                                    ->default(false)
                                                    ->helperText('For external links like WhatsApp, Telegram, etc.'),

                                                Forms\Components\Toggle::make('is_active')
                                                    ->label('Active')
                                                    ->default(true),
                                            ])
                                            ->columns(2)
                                            ->defaultItems(0)
                                            ->addActionLabel('Add Contact Item')
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? 'New Contact Item')
                                            ->reorderable()
                                            ->columnSpanFull(),
                                    ]),

                                Forms\Components\Section::make('Additional Contacts')
                                    ->description('Add multiple contact persons or departments')
                                    ->schema([
                                        Forms\Components\Repeater::make('additional_contacts')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\TextInput::make('name')
                                                    ->label('Name / Department')
                                                    ->required()
                                                    ->placeholder('John Doe / Sales Department'),

                                                Forms\Components\TextInput::make('role')
                                                    ->label('Role / Title')
                                                    ->placeholder('Sales Manager'),

                                                Forms\Components\TextInput::make('email')
                                                    ->label('Email')
                                                    ->email()
                                                    ->placeholder('john@example.com'),

                                                Forms\Components\TextInput::make('phone')
                                                    ->label('Phone')
                                                    ->tel()
                                                    ->placeholder('+20 123 456 7890'),

                                                Forms\Components\Toggle::make('is_active')
                                                    ->label('Active')
                                                    ->default(true),
                                            ])
                                            ->columns(2)
                                            ->defaultItems(0)
                                            ->addActionLabel('Add Contact')
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? 'New Contact')
                                            ->reorderable()
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        // Social Media Tab
                        Forms\Components\Tabs\Tab::make('🌐 Social Media')
                            ->schema([
                                Forms\Components\Section::make('Social Media Links')
                                    ->description('Add your social media profile URLs')
                                    ->schema([
                                        Forms\Components\TextInput::make('facebook_url')
                                            ->label('Facebook')
                                            ->url()
                                            ->placeholder('https://facebook.com/yourpage')
                                            ->prefixIcon('heroicon-o-globe-alt')
                                            ->suffixIcon('heroicon-o-arrow-top-right-on-square'),

                                        Forms\Components\TextInput::make('instagram_url')
                                            ->label('Instagram')
                                            ->url()
                                            ->placeholder('https://instagram.com/yourprofile')
                                            ->prefixIcon('heroicon-o-globe-alt')
                                            ->suffixIcon('heroicon-o-arrow-top-right-on-square'),

                                        Forms\Components\TextInput::make('twitter_url')
                                            ->label('Twitter / X')
                                            ->url()
                                            ->placeholder('https://twitter.com/yourhandle')
                                            ->prefixIcon('heroicon-o-globe-alt')
                                            ->suffixIcon('heroicon-o-arrow-top-right-on-square'),

                                        Forms\Components\TextInput::make('linkedin_url')
                                            ->label('LinkedIn')
                                            ->url()
                                            ->placeholder('https://linkedin.com/company/yourcompany')
                                            ->prefixIcon('heroicon-o-globe-alt')
                                            ->suffixIcon('heroicon-o-arrow-top-right-on-square'),

                                        Forms\Components\TextInput::make('youtube_url')
                                            ->label('YouTube')
                                            ->url()
                                            ->placeholder('https://youtube.com/@yourchannel')
                                            ->prefixIcon('heroicon-o-globe-alt')
                                            ->suffixIcon('heroicon-o-arrow-top-right-on-square'),

                                        Forms\Components\TextInput::make('tiktok_url')
                                            ->label('TikTok')
                                            ->url()
                                            ->placeholder('https://tiktok.com/@youraccount')
                                            ->prefixIcon('heroicon-o-globe-alt')
                                            ->suffixIcon('heroicon-o-arrow-top-right-on-square'),
                                    ])
                                    ->columns(2),

                                Forms\Components\Section::make('Social Media Display')
                                    ->description('These links also appear in Footer Menu → Social Links')
                                    ->schema([
                                        Forms\Components\Placeholder::make('social_info')
                                            ->label('')
                                            ->content('💡 Tip: You can also manage social links through "Menu Management → Footer Menu" for more control over display order and icons.'),
                                    ]),
                            ]),

                        // Navbar Settings Tab
                        Forms\Components\Tabs\Tab::make('📍 Navbar Settings')
                            ->schema([
                                Forms\Components\Section::make('Navbar Button')
                                    ->description('Customize the call-to-action button in navigation')
                                    ->schema([
                                        Forms\Components\TextInput::make('navbar_button_text_1')
                                            ->label('Button Text Line 1')
                                            ->maxLength(50)
                                            ->placeholder('request a quote'),

                                        Forms\Components\TextInput::make('navbar_button_text_2')
                                            ->label('Button Text Line 2')
                                            ->maxLength(50)
                                            ->placeholder('Contact us'),

                                        Forms\Components\Select::make('navbar_button_link')
                                            ->label('Button Link')
                                            ->options([
                                                'home' => '🏠 Home',
                                                'contact.index' => '📧 Contact',
                                                'projects.index' => '🏢 Projects',
                                                'blog.index' => '📰 Blog',
                                                'careers.index' => '💼 Careers',
                                            ])
                                            ->default('contact.index')
                                            ->helperText('Page to link when button is clicked'),
                                    ])
                                    ->columns(3),
                            ]),

                        // SEO Tab
                        Forms\Components\Tabs\Tab::make('🔍 SEO')
                            ->schema([
                                Forms\Components\Section::make('Meta Tags')
                                    ->description('Improve your site\'s search engine visibility')
                                    ->schema([
                                        Forms\Components\Textarea::make('meta_description')
                                            ->label('Meta Description')
                                            ->rows(3)
                                            ->maxLength(160)
                                            ->placeholder('A brief description of your site...')
                                            ->helperText('Recommended: 150-160 characters')
                                            ->columnSpanFull(),

                                        Forms\Components\Textarea::make('meta_keywords')
                                            ->label('Meta Keywords')
                                            ->rows(2)
                                            ->placeholder('real estate, development, UAE, RAK')
                                            ->helperText('Comma-separated keywords')
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->persistTabInQueryString(),
            ])
            ->statePath('data');
    }

    /**
     * Get header actions
     */
    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('preview')
                ->label('Preview Site')
                ->icon('heroicon-o-eye')
                ->color('gray')
                ->url('/', shouldOpenInNewTab: true),
        ];
    }

    /**
     * Get form actions
     */
    protected function getFormActions(): array
    {
        return [
            \Filament\Actions\Action::make('save')
                ->label('Save Changes')
                ->icon('heroicon-o-check')
                ->action('save'),
        ];
    }

    /**
     * Save the settings
     */
    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            if (in_array($key, ['additional_contacts', 'additional_contact_items'])) {
                Setting::set($key, json_encode($value), 'json', 'contact');
            } elseif (in_array($key, ['logo_dark', 'logo_light', 'favicon', 'footer_bg_image'])) {
                Setting::set($key, $value, 'image', $this->getGroup($key));
            } else {
                Setting::set($key, $value, 'text', $this->getGroup($key));
            }
        }

        // Clear cache to reflect changes immediately
        Artisan::call('optimize:clear');

        Notification::make()
            ->title('Settings saved successfully!')
            ->success()
            ->send();
    }

    /**
     * Safely decode JSON setting value
     */
    protected function decodeJsonSetting($value): array
    {
        // If already an array (from Setting model accessor), return it
        if (is_array($value)) {
            return $value;
        }
        
        // If string, decode it
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        
        // Otherwise return empty array
        return [];
    }

    /**
     * Get setting group based on key
     */
    protected function getGroup(string $key): string
    {
        return match (true) {
            str_starts_with($key, 'logo_') || str_starts_with($key, 'site_') || $key === 'favicon' => 'branding',
            str_starts_with($key, 'footer_') => 'footer',
            str_starts_with($key, 'contact_') || str_starts_with($key, 'phone_') || str_starts_with($key, 'address') || str_starts_with($key, 'additional_') => 'contact',
            str_contains($key, '_url') && !str_starts_with($key, 'address_') => 'social',
            str_starts_with($key, 'navbar_') => 'navbar',
            str_starts_with($key, 'meta_') => 'seo',
            default => 'general',
        };
    }
}
