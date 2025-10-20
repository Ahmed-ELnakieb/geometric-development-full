<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Filament\Resources\PageResource\RelationManagers;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    protected static ?string $navigationGroup = 'Content';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Page Management')
                    ->tabs([
                        // BASIC INFO TAB
                        Forms\Components\Tabs\Tab::make('Basic Info')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->maxLength(191)
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->maxLength(191)
                                    ->columnSpan(1),
                                Forms\Components\Select::make('template')
                                    ->options([
                                        'default' => 'Default',
                                        'home' => 'Homepage',
                                        'about' => 'About',
                                        'contact' => 'Contact',
                                    ])
                                    ->default('default'),
                                Forms\Components\Toggle::make('is_published')
                                    ->label('Published'),
                            ])->columns(2),
                        
                        // HERO SECTION TAB
                        Forms\Components\Tabs\Tab::make('🌟 Hero')
                            ->schema([
                                Forms\Components\Section::make('Hero Section')
                                    ->description('Main banner with background, foreground image, and rotating text')
                                    ->schema([
                                        // Text Content
                                        Forms\Components\TextInput::make('sections.hero.main_title')
                                            ->label('Main Title')
                                            ->default('Leading Community Developer in MUROJ')
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\TextInput::make('sections.hero.subtitle')
                                            ->label('Subtitle')
                                            ->default('Inspiration of MUROJ in EGYPT')
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\TagsInput::make('sections.hero.rotating_texts')
                                            ->label('Rotating Texts')
                                            ->helperText('Add multiple texts that rotate/animate')
                                            ->placeholder('Luxury Living, Invest Smart, etc.')
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('sections.hero.button_text')
                                                    ->label('Button Text')
                                                    ->default('IN GEOMETRIC'),
                                                
                                                Forms\Components\TextInput::make('sections.hero.button_link')
                                                    ->label('Button Link')
                                                    ->default('/projects')
                                                    ->placeholder('/projects'),
                                            ]),
                                        
                                        Forms\Components\TextInput::make('sections.hero.icon_class')
                                            ->label('Button Icon Class')
                                            ->default('flaticon-next-1')
                                            ->helperText('Font icon class for button')
                                            ->columnSpanFull(),
                                        
                                        // Images Section
                                        Forms\Components\Section::make('Hero Images')
                                            ->description('Upload images for background, foreground, and button icon')
                                            ->schema([
                                                Forms\Components\FileUpload::make('sections.hero.background_image')
                                                    ->label('Background Image (Parallax)')
                                                    ->image()
                                                    ->imageEditor()
                                                    ->directory('hero/backgrounds')
                                                    ->visibility('public')
                                                    ->maxSize(5120)
                                                    ->helperText('Large background image for parallax effect (h5-bg-img-1.png)')
                                                    ->columnSpanFull(),
                                                
                                                Forms\Components\FileUpload::make('sections.hero.foreground_image')
                                                    ->label('Foreground Image (Main)')
                                                    ->image()
                                                    ->imageEditor()
                                                    ->directory('hero/foreground')
                                                    ->visibility('public')
                                                    ->maxSize(5120)
                                                    ->helperText('Large image displayed in front (h5-img-1.png)')
                                                    ->columnSpanFull(),
                                                
                                                Forms\Components\FileUpload::make('sections.hero.icon_image')
                                                    ->label('Button Icon Background Image')
                                                    ->image()
                                                    ->imageEditor()
                                                    ->directory('hero/icons')
                                                    ->visibility('public')
                                                    ->maxSize(2048)
                                                    ->helperText('Small icon background image (h5-img-3.png)')
                                                    ->columnSpanFull(),
                                            ])
                                            ->collapsed()
                                            ->collapsible(),
                                        
                                        // Background Color
                                        Forms\Components\ColorPicker::make('sections.hero.background_color')
                                            ->label('Background Color')
                                            ->default('#ffffff')
                                            ->helperText('Fallback background color'),
                                    ])->collapsible(),
                            ]),
                        
                        // ABOUT SECTION TAB
                        Forms\Components\Tabs\Tab::make('ℹ️ About')
                            ->schema([
                                Forms\Components\Section::make('About Section')
                                    ->description('Company intro with text and images')
                                    ->schema([
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('sections.about.section_number')
                                                    ->label('Section Number')
                                                    ->default('01')
                                                    ->maxLength(5),
                                                
                                                Forms\Components\TextInput::make('sections.about.section_subtitle')
                                                    ->label('Section Subtitle')
                                                    ->default('about us'),
                                            ]),
                                        
                                        Forms\Components\TextInput::make('sections.about.section_title')
                                            ->label('Section Title')
                                            ->default('Your trusted partner in finding properties...')
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\Textarea::make('sections.about.description')
                                            ->label('Description')
                                            ->rows(4)
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('sections.about.button_text')
                                                    ->label('Button Text')
                                                    ->default('know about us'),
                                                
                                                Forms\Components\TextInput::make('sections.about.button_link')
                                                    ->label('Button Link')
                                                    ->default('/about')
                                                    ->placeholder('/about'),
                                            ]),
                                        
                                        Forms\Components\Repeater::make('sections.about.features')
                                            ->label('Features')
                                            ->schema([
                                                Forms\Components\TextInput::make('title')
                                                    ->label('Feature Title')
                                                    ->placeholder('Prime Locations'),
                                                Forms\Components\TextInput::make('icon')
                                                    ->label('Icon Class')
                                                    ->default('fa-solid fa-plus'),
                                            ])
                                            ->columns(2)
                                            ->collapsible()
                                            ->defaultItems(0)
                                            ->columnSpanFull(),
                                        
                                        // Images Section
                                        Forms\Components\Section::make('About Images')
                                            ->description('Upload background shapes and content images')
                                            ->schema([
                                                Forms\Components\FileUpload::make('sections.about.bg_shape_1')
                                                    ->label('Background Shape 1')
                                                    ->image()
                                                    ->imageEditor()
                                                    ->directory('about/shapes')
                                                    ->visibility('public')
                                                    ->maxSize(2048)
                                                    ->helperText('Decorative background shape (a5-bg-shape.png)')
                                                    ->columnSpanFull(),
                                                
                                                Forms\Components\FileUpload::make('sections.about.bg_shape_2')
                                                    ->label('Background Shape 2')
                                                    ->image()
                                                    ->imageEditor()
                                                    ->directory('about/shapes')
                                                    ->visibility('public')
                                                    ->maxSize(2048)
                                                    ->helperText('Second decorative background shape (a5-bg-shape-2.png)')
                                                    ->columnSpanFull(),
                                                
                                                Forms\Components\FileUpload::make('sections.about.image_1')
                                                    ->label('Content Image 1 (Left)')
                                                    ->image()
                                                    ->imageEditor()
                                                    ->directory('about/images')
                                                    ->visibility('public')
                                                    ->maxSize(5120)
                                                    ->helperText('Main content image on left side (a5-img-1.png)')
                                                    ->columnSpanFull(),
                                                
                                                Forms\Components\FileUpload::make('sections.about.image_2')
                                                    ->label('Content Image 2 (Right)')
                                                    ->image()
                                                    ->imageEditor()
                                                    ->directory('about/images')
                                                    ->visibility('public')
                                                    ->maxSize(5120)
                                                    ->helperText('Main content image on right side (a5-img-2.png)')
                                                    ->columnSpanFull(),
                                            ])
                                            ->collapsed()
                                            ->collapsible(),
                                    ])->collapsible(),
                            ]),
                        
                        // COUNTERS TAB
                        Forms\Components\Tabs\Tab::make('📊 Counters')
                            ->schema([
                                Forms\Components\Section::make('Statistics')
                                    ->description('Achievement counters - Lines 141-199')
                                    ->schema([
                                        Forms\Components\Repeater::make('sections.counters.items')
                                            ->label('Counter Items')
                                            ->schema([
                                                Forms\Components\TextInput::make('title'),
                                                Forms\Components\TextInput::make('value')->numeric(),
                                                Forms\Components\TextInput::make('suffix')->placeholder('k+, %, st'),
                                                Forms\Components\Textarea::make('description')->rows(2)->columnSpanFull(),
                                            ])
                                            ->columns(3)
                                            ->collapsible()
                                            ->columnSpanFull(),
                                    ])->collapsible(),
                            ]),
                        
                        // VIDEO TAB
                        Forms\Components\Tabs\Tab::make('🎬 Video')
                            ->schema([
                                Forms\Components\Section::make('Video Section')
                                    ->description('Promotional video - Lines 201-221')
                                    ->schema([
                                        Forms\Components\TextInput::make('sections.video.youtube_url')
                                            ->label('YouTube URL')
                                            ->placeholder('https://www.youtube.com/watch?v=...')
                                            ->columnSpanFull(),
                                        Forms\Components\Toggle::make('sections.video.autoplay')->inline(false),
                                        Forms\Components\Toggle::make('sections.video.loop')->inline(false),
                                        Forms\Components\Toggle::make('sections.video.muted')->inline(false),
                                    ])->columns(3)->collapsible(),
                            ]),
                        
                        // SERVICES TABS
                        Forms\Components\Tabs\Tab::make('💼 Services')
                            ->schema([
                                Forms\Components\Section::make('Service Tabs')
                                    ->description('Project tabs - Lines 222-464')
                                    ->schema([
                                        Forms\Components\TextInput::make('sections.services.section_title')
                                            ->label('Section Title')
                                            ->columnSpanFull(),
                                        Forms\Components\FileUpload::make('sections.services.background_image')
                                            ->label('Section Background Image')
                                            ->image()
                                            ->imageEditor()
                                            ->directory('services/backgrounds')
                                            ->visibility('public')
                                            ->helperText('Background image for the entire services section')
                                            ->columnSpanFull(),
                                        Forms\Components\Repeater::make('sections.services.tabs')
                                            ->label('Service/Project Tabs')
                                            ->schema([
                                                Forms\Components\TextInput::make('name')
                                                    ->label('Project Name')
                                                    ->columnSpanFull(),
                                                Forms\Components\Grid::make(2)
                                                    ->schema([
                                                        Forms\Components\TextInput::make('year')
                                                            ->label('Year')
                                                            ->default('2025'),
                                                        Forms\Components\Toggle::make('active')
                                                            ->label('Active Tab')
                                                            ->default(false),
                                                    ]),
                                                Forms\Components\TextInput::make('location')
                                                    ->label('Location')
                                                    ->columnSpanFull(),
                                                Forms\Components\Grid::make(2)
                                                    ->schema([
                                                        Forms\Components\TextInput::make('start_date')
                                                            ->label('Start Date')
                                                            ->placeholder('jan 02, 2025'),
                                                        Forms\Components\TextInput::make('end_date')
                                                            ->label('Completion Date')
                                                            ->placeholder('aug 02, 2025'),
                                                    ]),
                                                Forms\Components\TextInput::make('link')
                                                    ->label('Project Link')
                                                    ->default('/projects')
                                                    ->placeholder('/projects')
                                                    ->columnSpanFull(),
                                                
                                                // Project Image
                                                Forms\Components\FileUpload::make('main_image')
                                                    ->label('Project Image')
                                                    ->image()
                                                    ->imageEditor()
                                                    ->directory('services/projects')
                                                    ->visibility('public')
                                                    ->helperText('Main project image - click to upload or drag & drop')
                                                    ->columnSpanFull(),
                                                
                                                // Social Links Section
                                                Forms\Components\Section::make('Social Media Links')
                                                    ->description('Share project on social media')
                                                    ->schema([
                                                        Forms\Components\Repeater::make('social_links')
                                                            ->label('Social Links')
                                                            ->schema([
                                                                Forms\Components\Select::make('platform')
                                                                    ->label('Platform')
                                                                    ->options([
                                                                        'facebook' => 'Facebook',
                                                                        'twitter' => 'Twitter/X',
                                                                        'linkedin' => 'LinkedIn',
                                                                        'pinterest' => 'Pinterest',
                                                                        'instagram' => 'Instagram',
                                                                        'youtube' => 'YouTube',
                                                                    ])
                                                                    ->live()
                                                                    ->afterStateUpdated(function ($state, callable $set) {
                                                                        $icons = [
                                                                            'facebook' => 'fa-brands fa-facebook-f',
                                                                            'twitter' => 'fa-brands fa-x-twitter',
                                                                            'linkedin' => 'fa-brands fa-linkedin-in',
                                                                            'pinterest' => 'fa-brands fa-pinterest-p',
                                                                            'instagram' => 'fa-brands fa-instagram',
                                                                            'youtube' => 'fa-brands fa-youtube',
                                                                        ];
                                                                        $set('icon', $icons[$state] ?? '');
                                                                    }),
                                                                Forms\Components\TextInput::make('icon')
                                                                    ->label('Icon Class')
                                                                    ->default('fa-brands fa-facebook-f')
                                                                    ->helperText('FontAwesome icon class'),
                                                                Forms\Components\TextInput::make('url')
                                                                    ->label('URL')
                                                                    ->default('#')
                                                                    ->placeholder('https://facebook.com/...')
                                                                    ->columnSpanFull(),
                                                            ])
                                                            ->columns(2)
                                                            ->defaultItems(0)
                                                            ->collapsible()
                                                            ->itemLabel(fn (array $state): ?string => $state['platform'] ?? null)
                                                            ->columnSpanFull(),
                                                    ])
                                                    ->collapsed()
                                                    ->collapsible(),
                                            ])
                                            ->columns(1)
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? 'New Service')
                                            ->columnSpanFull(),
                                    ])->collapsible(),
                            ]),
                        
                        // SHOWCASE TAB
                        Forms\Components\Tabs\Tab::make('📸 Showcase')
                            ->schema([
                                Forms\Components\Section::make('Showcase Carousel')
                                    ->description('Select up to 2 projects. Auto-fills data, but all fields are editable.')
                                    ->schema([
                                        Forms\Components\Repeater::make('sections.showcase.items')
                                            ->label('Showcase Items')
                                            ->schema([
                                                // Project Selection
                                                Forms\Components\Select::make('project_id')
                                                    ->label('Select Project')
                                                    ->options(function () {
                                                        return \App\Models\Project::query()
                                                            ->where('is_published', true)
                                                            ->orderBy('title')
                                                            ->pluck('title', 'id');
                                                    })
                                                    ->searchable()
                                                    ->live()
                                                    ->helperText('Select a published project')
                                                    ->afterStateUpdated(function ($state, callable $set) {
                                                        if ($state) {
                                                            $project = \App\Models\Project::with('media')->find($state);
                                                            if ($project) {
                                                                $set('subtitle', $project->title);
                                                                $set('title', $project->excerpt ?? 'Luxury living experience');
                                                                $set('link', '/projects/' . $project->slug);
                                                                // Don't set image - let user upload or it will cause errors
                                                            }
                                                        }
                                                    })
                                                    ->columnSpanFull(),
                                                
                                                // Visibility Toggle
                                                Forms\Components\Toggle::make('showcase')
                                                    ->label('Show in Showcase')
                                                    ->default(true)
                                                    ->helperText('Controls visibility on homepage')
                                                    ->columnSpanFull(),
                                                
                                                // Subtitle (Project Name) - Editable
                                                Forms\Components\TextInput::make('subtitle')
                                                    ->label('Subtitle (Project Name)')
                                                    ->placeholder('Muroj Villa')
                                                    ->helperText('Display name for this showcase item')
                                                    ->columnSpanFull(),
                                                
                                                // Title (Description) - Editable
                                                Forms\Components\Textarea::make('title')
                                                    ->label('Title/Description')
                                                    ->rows(2)
                                                    ->placeholder('Luxury waterfront living with muroj views')
                                                    ->helperText('Showcase description text')
                                                    ->columnSpanFull(),
                                                
                                                // Link Preview - Clickable
                                                Forms\Components\Placeholder::make('link_preview')
                                                    ->label('Project Link (Auto-synced)')
                                                    ->content(function (callable $get) {
                                                        $link = $get('link') ?? '/projects';
                                                        $fullUrl = 'http://127.0.0.1:8000' . $link;
                                                        return new \Illuminate\Support\HtmlString('
                                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                                <a href="' . $fullUrl . '" 
                                                                   target="_blank" 
                                                                   style="color: #3b82f6; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 5px;">
                                                                    ' . $link . '
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                                                        <polyline points="15 3 21 3 21 9"></polyline>
                                                                        <line x1="10" y1="14" x2="21" y2="3"></line>
                                                                    </svg>
                                                                </a>
                                                                <span style="color: #6b7280; font-size: 12px;">Click to preview project</span>
                                                            </div>
                                                        ');
                                                    })
                                                    ->columnSpanFull(),
                                                
                                                // Hidden field to store link value
                                                Forms\Components\Hidden::make('link')
                                                    ->default('/projects'),
                                                
                                                Forms\Components\TextInput::make('button_text')
                                                    ->label('Button Text')
                                                    ->default('more details')
                                                    ->columnSpanFull(),
                                                
                                                // Image Upload - Now Editable!
                                                Forms\Components\FileUpload::make('image')
                                                    ->label('Showcase Image')
                                                    ->image()
                                                    ->imageEditor()
                                                    ->directory('showcase')
                                                    ->visibility('public')
                                                    ->maxSize(5120)
                                                    ->helperText('Upload custom image or it will use project thumbnail')
                                                    ->columnSpanFull(),
                                                
                                                // Info about synced data with image preview
                                                Forms\Components\Placeholder::make('sync_info')
                                                    ->label('ℹ️ Project Info & Image Preview')
                                                    ->content(function (callable $get) {
                                                        $projectId = $get('project_id');
                                                        if ($projectId) {
                                                            $project = \App\Models\Project::with('media')->find($projectId);
                                                            if ($project) {
                                                                $imageUrl = $project->getFirstMediaUrl('hero_thumbnails');
                                                                $imageHtml = $imageUrl 
                                                                    ? '<img src="' . $imageUrl . '" style="max-width: 200px; border-radius: 8px; margin-top: 10px;" />' 
                                                                    : '<p style="color: #999;">No project image available</p>';
                                                                
                                                                return new \Illuminate\Support\HtmlString('
                                                                    <div style="padding: 10px; background: #f3f4f6; border-radius: 6px;">
                                                                        <strong>Selected Project:</strong> ' . $project->title . '<br>
                                                                        <strong>Type:</strong> ' . ($project->type ?? 'N/A') . '<br>
                                                                        <strong>Auto Link:</strong> /projects/' . $project->slug . '<br>
                                                                        <strong>Project Image:</strong><br>
                                                                        ' . $imageHtml . '
                                                                        <p style="margin-top: 10px; font-size: 12px; color: #666;">
                                                                            💡 Tip: Upload a custom image above or this project image will be used
                                                                        </p>
                                                                    </div>
                                                                ');
                                                            }
                                                        }
                                                        return 'Select a project to see details and image preview';
                                                    })
                                                    ->columnSpanFull(),
                                            ])
                                            ->collapsible()
                                            ->collapsed()
                                            ->itemLabel(fn (array $state): ?string => 
                                                ($state['showcase'] ?? true ? '✓ ' : '✗ ') . 
                                                ($state['subtitle'] ?? 'New Showcase Item')
                                            )
                                            ->defaultItems(0)
                                            ->maxItems(2)
                                            ->reorderable()
                                            ->columnSpanFull()
                                            ->addActionLabel('Add Project to Showcase'),
                                        
                                        Forms\Components\Placeholder::make('showcase_info')
                                            ->label('ℹ️ How It Works')
                                            ->content(new \Illuminate\Support\HtmlString('
                                                <ul style="line-height: 1.8;">
                                                    <li><strong>Select Project:</strong> Choose from published projects - auto-fills fields</li>
                                                    <li><strong>Link URL:</strong> Auto-synced from project slug (disabled - changes when project changes)</li>
                                                    <li><strong>Editable Fields:</strong> Subtitle, title, button text, and image</li>
                                                    <li><strong>Image Upload:</strong> Upload custom image or use project thumbnail</li>
                                                    <li><strong>Showcase Toggle:</strong> Control visibility on homepage</li>
                                                    <li><strong>Item Label:</strong> Shows subtitle (project name) in collapsed view</li>
                                                    <li><strong>Limit:</strong> Maximum 2 projects in showcase</li>
                                                </ul>
                                            ')),
                                    ])->collapsible(),
                            ]),
                        
                        // GALLERY TAB
                        Forms\Components\Tabs\Tab::make('🖼️ Gallery')
                            ->schema([
                                Forms\Components\Section::make('Instagram Gallery')
                                    ->description('Social gallery grid with individual Instagram links')
                                    ->schema([
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('sections.gallery.section_subtitle')
                                                    ->label('Section Subtitle')
                                                    ->default('Stay Inspired with Instagram')
                                                    ->columnSpan(1),
                                                Forms\Components\TextInput::make('sections.gallery.section_title')
                                                    ->label('Section Title')
                                                    ->default('<i class="fa-brands fa-instagram"></i> Instagram')
                                                    ->columnSpan(1),
                                            ]),
                                        
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('sections.gallery.button_text')
                                                    ->label('Follow Button Text')
                                                    ->default('Follow Us'),
                                                Forms\Components\TextInput::make('sections.gallery.button_link')
                                                    ->label('Follow Button Link')
                                                    ->default('https://instagram.com/geometric_development')
                                                    ->placeholder('https://instagram.com/...'),
                                            ]),
                                        
                                        Forms\Components\Repeater::make('sections.gallery.items')
                                            ->label('Gallery Images')
                                            ->schema([
                                                Forms\Components\FileUpload::make('image')
                                                    ->label('Image')
                                                    ->image()
                                                    ->imageEditor()
                                                    ->directory('gallery')
                                                    ->visibility('public')
                                                    ->maxSize(5120)
                                                    ->helperText('Upload gallery image (max 5MB)')
                                                    ->columnSpanFull(),
                                                
                                                Forms\Components\TextInput::make('instagram_url')
                                                    ->label('Instagram Post URL')
                                                    ->placeholder('https://instagram.com/p/...')
                                                    ->helperText('Link to specific Instagram post')
                                                    ->columnSpanFull(),
                                                
                                                Forms\Components\Select::make('size')
                                                    ->label('Image Size')
                                                    ->options([
                                                        'normal' => 'Normal',
                                                        'xs-size' => 'Extra Small',
                                                        'sm-size' => 'Small',
                                                    ])
                                                    ->default('normal')
                                                    ->helperText('Display size in gallery grid')
                                                    ->columnSpanFull(),
                                            ])
                                            ->collapsible()
                                            ->collapsed()
                                            ->itemLabel(fn (array $state): ?string => 'Gallery Image #' . ($state['image'] ? '✓' : ''))
                                            ->defaultItems(0)
                                            ->reorderable()
                                            ->columnSpanFull()
                                            ->addActionLabel('Add Gallery Image'),
                                    ])->collapsible(),
                            ]),
                        
                        // SEO TAB
                        Forms\Components\Tabs\Tab::make('🔍 SEO')
                            ->schema([
                                Forms\Components\TextInput::make('meta_title')
                                    ->maxLength(191),
                                Forms\Components\Textarea::make('meta_description')
                                    ->rows(3),
                                Forms\Components\TagsInput::make('meta_keywords'),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('template'),
                Tables\Columns\TextColumn::make('meta_title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('og_image_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_published')
                    ->boolean(),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
