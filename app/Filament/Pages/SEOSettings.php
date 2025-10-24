<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Cache;

class SEOSettings extends Page implements HasForms, HasActions
{
    use InteractsWithForms, InteractsWithActions;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';
    protected static ?string $navigationLabel = 'SEO Management';
    protected static ?string $title = 'SEO Management';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 10;
    protected static string $view = 'filament.pages.seo-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = \App\Models\SeoSetting::first();
        
        if ($settings) {
            $keywords = $settings->site_keywords;
            // Ensure keywords is always an array
            if (is_string($keywords)) {
                $keywords = json_decode($keywords, true) ?? [];
            }
            
            $this->form->fill([
                'site_title' => $settings->site_title ?? '',
                'site_description' => $settings->site_description ?? '',
                'site_keywords' => is_array($keywords) ? $keywords : [],
                'facebook_url' => $settings->facebook_url ?? '',
                'twitter_handle' => $settings->twitter_handle ?? '',
                'linkedin_url' => $settings->linkedin_url ?? '',
                'instagram_url' => $settings->instagram_url ?? '',
                'youtube_url' => $settings->youtube_url ?? '',
                'google_analytics_id' => $settings->google_analytics_id ?? '',
                'google_site_verification' => $settings->google_site_verification ?? '',
            ]);
        } else {
            // Default values if no settings exist
            $this->form->fill([
                'site_title' => 'Geometric Development',
                'site_description' => 'Leading engineering and construction company',
                'site_keywords' => [],
                'facebook_url' => '',
                'twitter_handle' => '',
                'linkedin_url' => '',
                'instagram_url' => '',
                'youtube_url' => '',
                'google_analytics_id' => '',
                'google_site_verification' => '',
            ]);
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Basic SEO Settings')
                    ->schema([
                        TextInput::make('site_title')
                            ->label('Site Title')
                            ->maxLength(60),
                        
                        Textarea::make('site_description')
                            ->label('Site Description')
                            ->maxLength(160)
                            ->rows(2),
                        
                        TagsInput::make('site_keywords')
                            ->label('Keywords')
                            ->separator(','),
                    ]),
                
                Section::make('Social Media')
                    ->schema([
                        TextInput::make('facebook_url')
                            ->label('Facebook')
                            ->url(),
                        
                        TextInput::make('twitter_handle')
                            ->label('Twitter Handle')
                            ->placeholder('@YourHandle'),
                        
                        TextInput::make('linkedin_url')
                            ->label('LinkedIn')
                            ->url(),
                        
                        TextInput::make('instagram_url')
                            ->label('Instagram')
                            ->url(),
                        
                        TextInput::make('youtube_url')
                            ->label('YouTube')
                            ->url(),
                    ])
                    ->columns(3),
                
                Section::make('Analytics')
                    ->schema([
                        TextInput::make('google_analytics_id')
                            ->label('Google Analytics ID')
                            ->placeholder('G-XXXXXXXXXX'),
                        
                        TextInput::make('google_site_verification')
                            ->label('Google Site Verification'),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        
        $settings = \App\Models\SeoSetting::first();
        
        if ($settings) {
            $settings->update($data);
        } else {
            \App\Models\SeoSetting::create($data);
        }
        
        Notification::make()
            ->title('SEO Settings Saved')
            ->success()
            ->send();
    }
}