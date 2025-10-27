<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class PreloaderSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-play-circle';
    
    protected static ?string $navigationLabel = 'Preloader Settings';
    
    protected static ?string $title = 'Preloader Settings';
    
    protected static ?string $navigationGroup = 'Website Settings';
    
    protected static ?int $navigationSort = 5;

    protected static string $view = 'filament.pages.preloader-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'preloader_enabled' => settings('preloader_enabled', true),
            'preloader_use_logo' => settings('preloader_use_logo', true),
            'preloader_main_text' => settings('preloader_main_text', settings('site_name', 'GEOMETRIC')),
            'preloader_sub_text' => settings('preloader_sub_text', settings('site_tagline', 'DEVELOPMENT')),
            'preloader_background_type' => settings('preloader_background_type', 'color'),
            'preloader_background_color' => settings('preloader_background_color', '#060606'),
            'preloader_background_image' => settings('preloader_background_image', ''),
            'preloader_custom_image' => settings('preloader_custom_image', ''),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Preloader Configuration')
                    ->description('Configure the preloader animation and content')
                    ->schema([
                        Toggle::make('preloader_enabled')
                            ->label('Enable Preloader')
                            ->helperText('Show preloader animation when pages load')
                            ->default(true),
                        
                        Toggle::make('preloader_use_logo')
                            ->label('Use Website Logo')
                            ->helperText('Use your website logo')
                            ->default(true)
                            ->reactive(),
                        
                        FileUpload::make('preloader_custom_image')
                            ->label('Custom Preloader Image')
                            ->image()
                            ->directory('preloader')
                            ->visibility('public')
                            ->helperText('Upload a custom image for the preloader (GIF, SVG, PNG, JPG supported)')
                            ->acceptedFileTypes(['image/*'])
                            ->maxSize(10240)
                            ->visible(fn ($get) => $get('preloader_use_logo'))
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Preloader Text')
                    ->description('Customize the text shown in the preloader animation')
                    ->schema([
                        TextInput::make('preloader_main_text')
                            ->label('Main Text')
                            ->helperText('Primary text (will be converted to uppercase)')
                            ->placeholder('GEOMETRIC')
                            ->maxLength(50),
                        
                        TextInput::make('preloader_sub_text')
                            ->label('Subtitle Text')
                            ->helperText('Secondary text (will be converted to uppercase)')
                            ->placeholder('DEVELOPMENT')
                            ->maxLength(50),
                    ])
                    ->columns(2),

                Section::make('Background Settings')
                    ->description('Customize the preloader background')
                    ->schema([
                        Select::make('preloader_background_type')
                            ->label('Background Type')
                            ->options([
                                'color' => 'Solid Color',
                                'image' => 'Background Image',
                            ])
                            ->default('color')
                            ->reactive()
                            ->helperText('Choose between solid color or background image'),
                        
                        ColorPicker::make('preloader_background_color')
                            ->label('Background Color')
                            ->default('#060606')
                            ->visible(fn ($get) => $get('preloader_background_type') === 'color')
                            ->helperText('Choose the background color for the preloader'),
                        
                        FileUpload::make('preloader_background_image')
                            ->label('Background Image')
                            ->image()
                            ->directory('preloader')
                            ->visibility('public')
                            ->visible(fn ($get) => $get('preloader_background_type') === 'image')
                            ->helperText('Upload a background image (recommended: 1920x1080 or larger)')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(5120), // 5MB max
                    ])
                    ->columns(2),

                Section::make('Preview')
                    ->description('Preview how your preloader will look')
                    ->schema([
                        \Filament\Forms\Components\Placeholder::make('preview')
                            ->content(function ($get) {
                                $mainText = strtoupper($get('preloader_main_text') ?: settings('site_name', 'GEOMETRIC'));
                                $subText = strtoupper($get('preloader_sub_text') ?: settings('site_tagline', 'DEVELOPMENT'));
                                $useLogo = $get('preloader_use_logo');
                                $backgroundType = $get('preloader_background_type') ?: 'color';
                                $backgroundColor = $get('preloader_background_color') ?: '#060606';
                                $backgroundImage = $get('preloader_background_image');
                                
                                // Generate background style
                                if ($backgroundType === 'image' && $backgroundImage) {
                                    $backgroundStyle = "linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('/storage/{$backgroundImage}') center/cover no-repeat";
                                } else {
                                    $backgroundStyle = $backgroundColor;
                                }
                                
                                return view('filament.components.preloader-preview', [
                                    'mainText' => $mainText,
                                    'subText' => $subText,
                                    'useLogo' => $useLogo,
                                    'backgroundColor' => $backgroundStyle,
                                ]);
                            }),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->icon('heroicon-o-check')
                ->color('success')
                ->action('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();
        
        foreach ($data as $key => $value) {
            $type = 'text';
            
            // Set appropriate type for image fields
            if (in_array($key, ['preloader_background_image', 'preloader_custom_image'])) {
                $type = 'image';
            }
            
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'type' => $type,
                    'group' => 'preloader'
                ]
            );
        }
        
        Notification::make()
            ->title('Preloader settings saved successfully')
            ->body('Your preloader configuration has been updated.')
            ->success()
            ->send();
    }
}
