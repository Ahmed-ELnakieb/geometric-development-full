<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
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
                                    ->default('default')
                                    ->live()
                                    ->required(),
                                Forms\Components\Toggle::make('is_published')
                                    ->label('Published'),
                            ])->columns(2),
                        
                        // ========================================
                        // HOME PAGE SECTIONS (only visible when template = 'home')
                        // ========================================
                        
                        // HERO SECTION TAB
                        Forms\Components\Tabs\Tab::make('🌟 Hero')
                            ->visible(fn (callable $get) => $get('template') === 'home')
                            ->schema([
                                Forms\Components\Section::make('Hero Section')
                                    ->description('Main banner with background, foreground image, and rotating text')
                                    ->schema([
                                        // Visibility Toggle
                                        Forms\Components\Toggle::make('sections.hero.is_active')
                                            ->label('Show Hero Section')
                                            ->default(true)
                                            ->helperText('Toggle to show/hide entire hero section on homepage')
                                            ->inline(false)
                                            ->columnSpanFull(),
                                        
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
                                    ])->collapsible()->collapsed(),
                            ]),
                        
                        // ABOUT SECTION TAB
                        Forms\Components\Tabs\Tab::make('ℹ️ About')
                            ->visible(fn (callable $get) => $get('template') === 'home')
                            ->schema([
                                Forms\Components\Section::make('About Section')
                                    ->description('Company intro with text and images')
                                    ->schema([
                                        // Visibility Toggle
                                        Forms\Components\Toggle::make('sections.about.is_active')
                                            ->label('Show About Section')
                                            ->default(true)
                                            ->helperText('Toggle to show/hide entire about section on homepage')
                                            ->inline(false)
                                            ->columnSpanFull(),
                                        
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
                                    ])->collapsible()->collapsed(),
                            ]),
                        
                        // COUNTERS TAB
                        Forms\Components\Tabs\Tab::make('📊 Counters')
                            ->visible(fn (callable $get) => $get('template') === 'home')
                            ->schema([
                                Forms\Components\Section::make('Statistics')
                                    ->description('Achievement counters - Lines 141-199')
                                    ->schema([
                                        // Visibility Toggle
                                        Forms\Components\Toggle::make('sections.counters.is_active')
                                            ->label('Show Counters Section')
                                            ->default(true)
                                            ->helperText('Toggle to show/hide entire counters section on homepage')
                                            ->inline(false)
                                            ->columnSpanFull(),
                                        
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
                                    ])->collapsible()->collapsed(),
                            ]),
                        
                        // VIDEO TAB
                        Forms\Components\Tabs\Tab::make('🎬 Video')
                            ->visible(fn (callable $get) => $get('template') === 'home')
                            ->schema([
                                Forms\Components\Section::make('Video Section')
                                    ->description('Promotional video with upload or YouTube link')
                                    ->schema([
                                        // Visibility Toggle
                                        Forms\Components\Toggle::make('sections.video.is_active')
                                            ->label('Show Video Section')
                                            ->default(true)
                                            ->helperText('Toggle to show/hide entire video section on homepage')
                                            ->inline(false)
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\FileUpload::make('sections.video.video_file')
                                            ->label('Upload Video File')
                                            ->disk('public')
                                            ->directory('videos')
                                            ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg'])
                                            ->maxSize(102400)
                                            ->helperText('Upload MP4, WebM, or OGG video (max 100MB). If uploaded, this will be used instead of YouTube URL.')
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\TextInput::make('sections.video.youtube_url')
                                            ->label('YouTube URL (Fallback)')
                                            ->placeholder('https://www.youtube.com/watch?v=...')
                                            ->helperText('Used only if no video file is uploaded')
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\Grid::make(3)
                                            ->schema([
                                                Forms\Components\Toggle::make('sections.video.autoplay')
                                                    ->label('Autoplay')
                                                    ->default(true)
                                                    ->inline(false),
                                                Forms\Components\Toggle::make('sections.video.loop')
                                                    ->label('Loop')
                                                    ->default(true)
                                                    ->inline(false),
                                                Forms\Components\Toggle::make('sections.video.muted')
                                                    ->label('Muted')
                                                    ->default(true)
                                                    ->inline(false),
                                            ]),
                                    ])->collapsible()->collapsed(),
                            ]),
                        
                        // SERVICES TABS
                        Forms\Components\Tabs\Tab::make('💼 Services')
                            ->visible(fn (callable $get) => $get('template') === 'home')
                            ->schema([
                                Forms\Components\Section::make('Services/Projects Tabs')
                                    ->description('Tabbed services section - Lines 223-466')
                                    ->schema([
                                        // Visibility Toggle
                                        Forms\Components\Toggle::make('sections.services.is_active')
                                            ->label('Show Services Section')
                                            ->default(true)
                                            ->helperText('Toggle to show/hide entire services section on homepage')
                                            ->inline(false)
                                            ->columnSpanFull(),
                                        
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
                                    ])->collapsible()->collapsed(),
                            ]),
                        
                        // PROJECTS TAB
                        Forms\Components\Tabs\Tab::make('🏘️ Projects')
                            ->visible(fn (callable $get) => $get('template') === 'home')
                            ->schema([
                                Forms\Components\Section::make('Featured Projects Section')
                                    ->description('Configure the featured projects display on homepage')
                                    ->schema([
                                        // Visibility Toggle
                                        Forms\Components\Toggle::make('sections.projects.is_active')
                                            ->label('Show Projects Section')
                                            ->default(true)
                                            ->helperText('Toggle to show/hide the entire projects section on homepage')
                                            ->inline(false)
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\TextInput::make('sections.projects.section_title')
                                            ->label('Section Title')
                                            ->default('RESIDENTIAL PROPERTIES')
                                            ->placeholder('RESIDENTIAL PROPERTIES')
                                            ->helperText('Main heading for the projects section')
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\TextInput::make('sections.projects.section_subtitle')
                                            ->label('Section Subtitle (Optional)')
                                            ->placeholder('Discover our exclusive properties')
                                            ->helperText('Optional subtitle text below the main title')
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\Select::make('sections.projects.project_limit')
                                                    ->label('Number of Projects to Display')
                                                    ->options([
                                                        3 => '3 Projects',
                                                        4 => '4 Projects',
                                                        6 => '6 Projects (Default)',
                                                        8 => '8 Projects',
                                                        9 => '9 Projects',
                                                        12 => '12 Projects',
                                                    ])
                                                    ->default(6)
                                                    ->helperText('Maximum number of featured projects to show')
                                                    ->native(false),
                                                
                                                Forms\Components\Toggle::make('sections.projects.show_button')
                                                    ->label('Show "View All" Button')
                                                    ->default(true)
                                                    ->helperText('Display button to view all projects page')
                                                    ->inline(false),
                                            ]),
                                        
                                        Forms\Components\TextInput::make('sections.projects.button_text')
                                            ->label('Button Text')
                                            ->default('view all projects')
                                            ->placeholder('view all projects')
                                            ->helperText('Text for the "View All Projects" button')
                                            ->visible(fn (callable $get) => $get('sections.projects.show_button'))
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\FileUpload::make('sections.projects.button_bg_image')
                                            ->label('Button Background Shape (Optional)')
                                            ->image()
                                            ->directory('projects/button-bg')
                                            ->visibility('public')
                                            ->helperText('Background decorative image for the button area')
                                            ->visible(fn (callable $get) => $get('sections.projects.show_button'))
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\Placeholder::make('projects_note')
                                            ->label('📌 Important Notes')
                                            ->content(new \Illuminate\Support\HtmlString('
                                                <div style="padding: 12px; background: #f3f4f6; border-radius: 6px; font-size: 14px;">
                                                    <ul style="margin: 0; padding-left: 20px; line-height: 2;">
                                                        <li><strong>Projects Source:</strong> Shows published projects marked as "Featured" from Projects menu</li>
                                                        <li><strong>Order:</strong> Projects are sorted by "Display Order" field</li>
                                                        <li><strong>To Add Projects:</strong> Go to Projects menu → Create/Edit → Toggle "Is Featured" → Set "Display Order"</li>
                                                        <li><strong>Layout:</strong> Grid layout automatically adjusts based on number of projects</li>
                                                        <li><strong>Images:</strong> Uses first image from project gallery</li>
                                                    </ul>
                                                </div>
                                            ')),
                                    ])->collapsible()->collapsed(),
                            ]),
                        
                        // SHOWCASE TAB
                        Forms\Components\Tabs\Tab::make('📸 Showcase')
                            ->visible(fn (callable $get) => $get('template') === 'home')
                            ->schema([
                                Forms\Components\Section::make('Showcase Carousel')
                                    ->description('Select up to 2 projects. Auto-fills data, but all fields are editable.')
                                    ->schema([
                                        // Visibility Toggle
                                        Forms\Components\Toggle::make('sections.showcase.is_active')
                                            ->label('Show Showcase Section')
                                            ->default(true)
                                            ->helperText('Toggle to show/hide entire showcase section on homepage')
                                            ->inline(false)
                                            ->columnSpanFull(),
                                        
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
                                    ])->collapsible()->collapsed(),
                            ]),
                        
                        // GALLERY TAB
                        Forms\Components\Tabs\Tab::make('🖼️ Gallery')
                            ->visible(fn (callable $get) => $get('template') === 'home')
                            ->schema([
                                Forms\Components\Section::make('Instagram Gallery')
                                    ->description('Social gallery grid with individual Instagram links')
                                    ->schema([
                                        // Visibility Toggle
                                        Forms\Components\Toggle::make('sections.gallery.is_active')
                                            ->label('Show Gallery Section')
                                            ->default(true)
                                            ->helperText('Toggle to show/hide entire gallery section on homepage')
                                            ->inline(false)
                                            ->columnSpanFull(),
                                        
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
                                    ])->collapsible()->collapsed(),
                            ]),
                        
                        // BLOG TAB
                        Forms\Components\Tabs\Tab::make('📝 Blog')
                            ->visible(fn (callable $get) => $get('template') === 'home')
                            ->schema([
                                Forms\Components\Section::make('Blog Posts Section')
                                    ->description('Configure the latest blog posts display on homepage')
                                    ->schema([
                                        // Visibility Toggle
                                        Forms\Components\Toggle::make('sections.blog.is_active')
                                            ->label('Show Blog Section')
                                            ->default(true)
                                            ->helperText('Toggle to show/hide the entire blog section on homepage')
                                            ->inline(false)
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\TextInput::make('sections.blog.section_subtitle')
                                            ->label('Section Subtitle')
                                            ->default('recent blog')
                                            ->placeholder('recent blog')
                                            ->helperText('Small text above the main title')
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\TextInput::make('sections.blog.section_title')
                                            ->label('Section Title')
                                            ->default('news & ideas')
                                            ->placeholder('news & ideas')
                                            ->helperText('Main heading for the blog section')
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\FileUpload::make('sections.blog.icon_image')
                                            ->label('Icon Image (Optional)')
                                            ->image()
                                            ->directory('blog/icons')
                                            ->visibility('public')
                                            ->helperText('Small decorative icon shown before subtitle')
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\Select::make('sections.blog.post_limit')
                                                    ->label('Number of Posts to Display')
                                                    ->options([
                                                        2 => '2 Posts',
                                                        3 => '3 Posts (Default)',
                                                        4 => '4 Posts',
                                                        6 => '6 Posts',
                                                        8 => '8 Posts',
                                                    ])
                                                    ->default(3)
                                                    ->helperText('Maximum number of latest blog posts to show')
                                                    ->native(false),
                                                
                                                Forms\Components\Toggle::make('sections.blog.show_button')
                                                    ->label('Show "View All" Button')
                                                    ->default(true)
                                                    ->helperText('Display button to view all blog posts page')
                                                    ->inline(false),
                                            ]),
                                        
                                        Forms\Components\TextInput::make('sections.blog.button_text')
                                            ->label('Button Text')
                                            ->default('view all blogs')
                                            ->placeholder('view all blogs')
                                            ->helperText('Text for the "View All Blogs" button')
                                            ->visible(fn (callable $get) => $get('sections.blog.show_button'))
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\Toggle::make('sections.blog.show_date')
                                            ->label('Show Publication Date')
                                            ->default(true)
                                            ->helperText('Display date on each blog post')
                                            ->inline(false),
                                        
                                        Forms\Components\Toggle::make('sections.blog.show_excerpt')
                                            ->label('Show Excerpt')
                                            ->default(true)
                                            ->helperText('Display short description/excerpt on each blog post')
                                            ->inline(false),
                                        
                                        Forms\Components\Placeholder::make('blog_note')
                                            ->label('📌 Important Notes')
                                            ->content(new \Illuminate\Support\HtmlString('
                                                <div style="padding: 12px; background: #f3f4f6; border-radius: 6px; font-size: 14px;">
                                                    <ul style="margin: 0; padding-left: 20px; line-height: 2;">
                                                        <li><strong>Posts Source:</strong> Shows published blog posts from Blog Posts menu</li>
                                                        <li><strong>Order:</strong> Posts sorted by publication date (newest first)</li>
                                                        <li><strong>To Add Posts:</strong> Go to Blog Posts menu → Create/Edit → Publish</li>
                                                        <li><strong>Featured Image:</strong> Uses the featured image from each post</li>
                                                        <li><strong>Excerpt:</strong> Short description shown on homepage (editable in post)</li>
                                                    </ul>
                                                </div>
                                            ')),
                                    ])->collapsible()->collapsed(),
                            ]),
                        
                        // ========================================
                        // ABOUT PAGE SECTIONS
                        // ========================================
                        
                        // BREADCRUMB TAB (About Page)
                        Forms\Components\Tabs\Tab::make('📍 Breadcrumb')
                            ->visible(fn (callable $get) => $get('template') === 'about')
                            ->schema([
                                Forms\Components\Section::make('Breadcrumb Section')
                                    ->description('Page header with title and background image')
                                    ->schema([
                                        Forms\Components\Toggle::make('sections.breadcrumb.is_active')
                                            ->label('Show Breadcrumb Section')
                                            ->default(true)
                                            ->inline(false)
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\TextInput::make('sections.breadcrumb.page_title')
                                            ->label('Page Title')
                                            ->default('About Us')
                                            ->required()
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\FileUpload::make('sections.breadcrumb.background_image')
                                            ->label('Background Image')
                                            ->image()
                                            ->imageEditor()
                                            ->directory('about/breadcrumb')
                                            ->visibility('public')
                                            ->helperText('Header background image')
                                            ->columnSpanFull(),
                                    ])->collapsible()->collapsed(),
                            ]),
                        
                        // CORE FEATURES TAB (About Page)
                        Forms\Components\Tabs\Tab::make('⭐ Core Features')
                            ->visible(fn (callable $get) => $get('template') === 'about')
                            ->schema([
                                Forms\Components\Section::make('Core Features Section')
                                    ->description('Key features with icons displayed horizontally')
                                    ->schema([
                                        Forms\Components\Toggle::make('sections.core_features.is_active')
                                            ->label('Show Core Features Section')
                                            ->default(true)
                                            ->inline(false)
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\Repeater::make('sections.core_features.items')
                                            ->label('Feature Items')
                                            ->schema([
                                                Forms\Components\FileUpload::make('icon')
                                                    ->label('Feature Icon')
                                                    ->image()
                                                    ->directory('about/features')
                                                    ->visibility('public'),
                                                Forms\Components\TextInput::make('title')
                                                    ->label('Title')
                                                    ->required(),
                                                Forms\Components\TextInput::make('description')
                                                    ->label('Description'),
                                                Forms\Components\TextInput::make('link')
                                                    ->label('Link URL')
                                                    ->default('#'),
                                            ])
                                            ->columns(2)
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'Feature')
                                            ->defaultItems(4)
                                            ->columnSpanFull(),
                                    ])->collapsible()->collapsed(),
                            ]),
                        
                        // ABOUT SECTION TAB (About Page)
                        Forms\Components\Tabs\Tab::make('ℹ️ About Company')
                            ->visible(fn (callable $get) => $get('template') === 'about')
                            ->schema([
                                Forms\Components\Section::make('About Section')
                                    ->description('Company introduction with image slider')
                                    ->schema([
                                        Forms\Components\Toggle::make('sections.about.is_active')
                                            ->label('Show About Section')
                                            ->default(true)
                                            ->inline(false)
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\TextInput::make('sections.about.subtitle')
                                            ->label('Subtitle')
                                            ->default('Welcome to Geometric Development')
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\TextInput::make('sections.about.title')
                                            ->label('Main Title')
                                            ->default('Exceptional Communities <br> Across Egypt')
                                            ->helperText('Use <br> for line breaks')
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\RichEditor::make('sections.about.description')
                                            ->label('Description')
                                            ->toolbarButtons(['bold', 'italic'])
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\Repeater::make('sections.about.images')
                                            ->label('Slider Images')
                                            ->schema([
                                                Forms\Components\FileUpload::make('image')
                                                    ->label('Image')
                                                    ->image()
                                                    ->imageEditor()
                                                    ->directory('about/slider')
                                                    ->visibility('public')
                                                    ->required(),
                                            ])
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => 'Image #' . ($state['image'] ? '✓' : ''))
                                            ->defaultItems(4)
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\Toggle::make('sections.about.show_button')
                                                    ->label('Show Button')
                                                    ->default(true)
                                                    ->inline(false),
                                                
                                                Forms\Components\TextInput::make('sections.about.button_text')
                                                    ->label('Button Text')
                                                    ->default('learn more about')
                                                    ->visible(fn (callable $get) => $get('sections.about.show_button')),
                                            ]),
                                        
                                        Forms\Components\TextInput::make('sections.about.button_link')
                                            ->label('Button Link')
                                            ->default('#')
                                            ->visible(fn (callable $get) => $get('sections.about.show_button'))
                                            ->columnSpanFull(),
                                    ])->collapsible()->collapsed(),
                            ]),
                        
                        // COUNTERS TAB (About Page)
                        Forms\Components\Tabs\Tab::make('📊 Statistics')
                            ->visible(fn (callable $get) => $get('template') === 'about')
                            ->schema([
                                Forms\Components\Section::make('Counters/Statistics Section')
                                    ->description('Achievement counters with animated numbers')
                                    ->schema([
                                        Forms\Components\Toggle::make('sections.counters.is_active')
                                            ->label('Show Counters Section')
                                            ->default(true)
                                            ->inline(false)
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\Repeater::make('sections.counters.items')
                                            ->label('Counter Items')
                                            ->schema([
                                                Forms\Components\TextInput::make('title')
                                                    ->label('Title')
                                                    ->required(),
                                                Forms\Components\TextInput::make('value')
                                                    ->label('Counter Value')
                                                    ->numeric()
                                                    ->required(),
                                                Forms\Components\TextInput::make('suffix')
                                                    ->label('Suffix')
                                                    ->placeholder('k+, %, +, etc.')
                                                    ->helperText('Symbol after the number'),
                                                Forms\Components\Textarea::make('description')
                                                    ->label('Description')
                                                    ->rows(2)
                                                    ->columnSpanFull(),
                                            ])
                                            ->columns(3)
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'Counter')
                                            ->defaultItems(4)
                                            ->columnSpanFull(),
                                    ])->collapsible()->collapsed(),
                            ]),
                        
                        // VALUES TAB (About Page)
                        Forms\Components\Tabs\Tab::make('💎 Brand Values')
                            ->visible(fn (callable $get) => $get('template') === 'about')
                            ->schema([
                                Forms\Components\Section::make('Brand Values Section')
                                    ->description('Company values with interactive cards')
                                    ->schema([
                                        Forms\Components\Toggle::make('sections.values.is_active')
                                            ->label('Show Values Section')
                                            ->default(true)
                                            ->inline(false)
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\TextInput::make('sections.values.subtitle')
                                            ->label('Subtitle')
                                            ->default('OUR BRAND VALUES')
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\TextInput::make('sections.values.title')
                                            ->label('Main Title')
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\Textarea::make('sections.values.description')
                                            ->label('Description')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\FileUpload::make('sections.values.section_image')
                                            ->label('Section Image (Left Side)')
                                            ->image()
                                            ->directory('about/values')
                                            ->visibility('public')
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\FileUpload::make('sections.values.background_image')
                                            ->label('Background Image')
                                            ->image()
                                            ->directory('about/backgrounds')
                                            ->visibility('public')
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\Toggle::make('sections.values.show_button')
                                                    ->label('Show Button')
                                                    ->default(true)
                                                    ->inline(false),
                                                
                                                Forms\Components\TextInput::make('sections.values.button_text')
                                                    ->label('Button Text')
                                                    ->default('learn more')
                                                    ->visible(fn (callable $get) => $get('sections.values.show_button')),
                                            ]),
                                        
                                        Forms\Components\TextInput::make('sections.values.button_link')
                                            ->label('Button Link')
                                            ->default('#')
                                            ->visible(fn (callable $get) => $get('sections.values.show_button'))
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\Repeater::make('sections.values.values')
                                            ->label('Value Cards')
                                            ->schema([
                                                Forms\Components\TextInput::make('title')
                                                    ->label('Value Title')
                                                    ->required()
                                                    ->columnSpanFull(),
                                                
                                                Forms\Components\Textarea::make('description')
                                                    ->label('Description')
                                                    ->rows(3)
                                                    ->required()
                                                    ->columnSpanFull(),
                                                
                                                Forms\Components\FileUpload::make('image')
                                                    ->label('Value Image')
                                                    ->image()
                                                    ->directory('about/values')
                                                    ->visibility('public')
                                                    ->columnSpanFull(),
                                                
                                                Forms\Components\TextInput::make('link')
                                                    ->label('Link URL')
                                                    ->default('#'),
                                                
                                                Forms\Components\Toggle::make('active')
                                                    ->label('Active by Default')
                                                    ->helperText('First value card to show expanded')
                                                    ->inline(false),
                                            ])
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'Value')
                                            ->defaultItems(5)
                                            ->columnSpanFull(),
                                    ])->collapsible()->collapsed(),
                            ]),
                        
                        // EXPERTISE TAB (About Page)
                        Forms\Components\Tabs\Tab::make('🎯 Expertise')
                            ->visible(fn (callable $get) => $get('template') === 'about')
                            ->schema([
                                Forms\Components\Section::make('Expertise Tags Section')
                                    ->description('Expertise areas with animated floating tags')
                                    ->schema([
                                        Forms\Components\Toggle::make('sections.expertise.is_active')
                                            ->label('Show Expertise Section')
                                            ->default(true)
                                            ->inline(false)
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\TextInput::make('sections.expertise.title')
                                            ->label('Section Title')
                                            ->default('Geometric Development')
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\Repeater::make('sections.expertise.tags')
                                            ->label('Expertise Tags')
                                            ->schema([
                                                Forms\Components\TextInput::make('text')
                                                    ->label('Tag Text')
                                                    ->required(),
                                                
                                                Forms\Components\TextInput::make('icon')
                                                    ->label('Icon Class')
                                                    ->default('flaticon-check')
                                                    ->helperText('Font icon class (e.g., flaticon-check)'),
                                                
                                                Forms\Components\Select::make('icon_position')
                                                    ->label('Icon Position')
                                                    ->options([
                                                        'left' => 'Left',
                                                        'right' => 'Right',
                                                    ])
                                                    ->default('left')
                                                    ->native(false),
                                                
                                                Forms\Components\TextInput::make('icon_color')
                                                    ->label('Icon Color Class')
                                                    ->placeholder('has-clr-3, has-clr-2, etc.')
                                                    ->helperText('Optional color class'),
                                            ])
                                            ->columns(2)
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['text'] ?? 'Tag')
                                            ->defaultItems(6)
                                            ->columnSpanFull(),
                                    ])->collapsible()->collapsed(),
                            ]),
                        
                        // PORTFOLIO TAB (About Page)
                        Forms\Components\Tabs\Tab::make('🏘️ Portfolio')
                            ->visible(fn (callable $get) => $get('template') === 'about')
                            ->schema([
                                Forms\Components\Section::make('Projects Portfolio Section')
                                    ->description('Showcase featured projects')
                                    ->schema([
                                        Forms\Components\Toggle::make('sections.portfolio.is_active')
                                            ->label('Show Portfolio Section')
                                            ->default(true)
                                            ->inline(false)
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\TextInput::make('sections.portfolio.subtitle')
                                            ->label('Subtitle')
                                            ->default('projects')
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\TextInput::make('sections.portfolio.title')
                                            ->label('Main Title')
                                            ->default('Our Development Portfolio')
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\FileUpload::make('sections.portfolio.featured_image')
                                            ->label('Featured Image (Left Side)')
                                            ->image()
                                            ->directory('about/portfolio')
                                            ->visibility('public')
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\Toggle::make('sections.portfolio.use_real_projects')
                                            ->label('Use Real Projects from Database')
                                            ->default(true)
                                            ->helperText('Pull projects automatically from Projects menu')
                                            ->live()
                                            ->inline(false)
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\Select::make('sections.portfolio.project_limit')
                                            ->label('Number of Projects to Display')
                                            ->options([
                                                3 => '3 Projects',
                                                4 => '4 Projects',
                                                5 => '5 Projects',
                                                6 => '6 Projects',
                                                8 => '8 Projects',
                                            ])
                                            ->default(6)
                                            ->visible(fn (callable $get) => $get('sections.portfolio.use_real_projects'))
                                            ->native(false),
                                        
                                        Forms\Components\Placeholder::make('portfolio_note')
                                            ->label('📌 Projects Source')
                                            ->content(new \Illuminate\Support\HtmlString('
                                                <div style="padding: 10px; background: #f0f9ff; border-radius: 4px; font-size: 13px;">
                                                    Projects are pulled from the <strong>Projects menu</strong> where <strong>Is Featured</strong> is enabled, 
                                                    sorted by <strong>Display Order</strong>.
                                                </div>
                                            '))
                                            ->visible(fn (callable $get) => $get('sections.portfolio.use_real_projects')),
                                    ])->collapsible()->collapsed(),
                            ]),
                        
                        // WHY CHOOSE US TAB (About Page)
                        Forms\Components\Tabs\Tab::make('❓ Why Choose Us')
                            ->visible(fn (callable $get) => $get('template') === 'about')
                            ->schema([
                                Forms\Components\Section::make('Why Choose Us Section')
                                    ->description('Reasons to choose with progress bars and features')
                                    ->schema([
                                        Forms\Components\Toggle::make('sections.why_choose_us.is_active')
                                            ->label('Show Why Choose Us Section')
                                            ->default(true)
                                            ->inline(false)
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\TextInput::make('sections.why_choose_us.subtitle')
                                            ->label('Subtitle')
                                            ->default('Why choose us')
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\TextInput::make('sections.why_choose_us.title')
                                            ->label('Main Title')
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\Textarea::make('sections.why_choose_us.description')
                                            ->label('Description')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\FileUpload::make('sections.why_choose_us.background_image')
                                            ->label('Background Image')
                                            ->image()
                                            ->directory('about/backgrounds')
                                            ->visibility('public')
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\Toggle::make('sections.why_choose_us.show_video')
                                                    ->label('Show Video Button')
                                                    ->default(true)
                                                    ->inline(false),
                                                
                                                Forms\Components\TextInput::make('sections.why_choose_us.video_url')
                                                    ->label('Video URL')
                                                    ->placeholder('https://www.youtube.com/watch?v=...')
                                                    ->visible(fn (callable $get) => $get('sections.why_choose_us.show_video')),
                                            ]),
                                        
                                        Forms\Components\Repeater::make('sections.why_choose_us.progress')
                                            ->label('Progress Bars')
                                            ->schema([
                                                Forms\Components\TextInput::make('title')
                                                    ->label('Progress Title')
                                                    ->required(),
                                                
                                                Forms\Components\TextInput::make('percentage')
                                                    ->label('Percentage')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->maxValue(100)
                                                    ->suffix('%')
                                                    ->required(),
                                            ])
                                            ->columns(2)
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => ($state['title'] ?? 'Progress') . ' - ' . ($state['percentage'] ?? '0') . '%')
                                            ->defaultItems(3)
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\Repeater::make('sections.why_choose_us.features')
                                            ->label('Feature Cards')
                                            ->schema([
                                                Forms\Components\TextInput::make('icon')
                                                    ->label('Icon Class')
                                                    ->default('flaticon-minimalist')
                                                    ->helperText('Font icon class'),
                                                
                                                Forms\Components\TextInput::make('title')
                                                    ->label('Feature Title')
                                                    ->required(),
                                                
                                                Forms\Components\Textarea::make('description')
                                                    ->label('Description')
                                                    ->rows(2)
                                                    ->columnSpanFull(),
                                                
                                                Forms\Components\TextInput::make('link')
                                                    ->label('Link URL')
                                                    ->default('#'),
                                            ])
                                            ->columns(2)
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'Feature')
                                            ->defaultItems(4)
                                            ->columnSpanFull(),
                                    ])->collapsible()->collapsed(),
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
