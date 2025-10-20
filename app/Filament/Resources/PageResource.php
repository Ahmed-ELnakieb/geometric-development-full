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
                                    ->description('Main banner - Lines 9-56')
                                    ->schema([
                                        Forms\Components\TextInput::make('sections.hero.main_title')
                                            ->label('Main Title')
                                            ->columnSpanFull(),
                                        Forms\Components\TextInput::make('sections.hero.subtitle')
                                            ->label('Subtitle')
                                            ->columnSpanFull(),
                                        Forms\Components\TagsInput::make('sections.hero.rotating_texts')
                                            ->label('Rotating Texts')
                                            ->columnSpanFull(),
                                        Forms\Components\TextInput::make('sections.hero.button_text')
                                            ->label('Button Text'),
                                        Forms\Components\TextInput::make('sections.hero.button_link')
                                            ->label('Button Link'),
                                    ])->columns(2)->collapsible(),
                            ]),
                        
                        // ABOUT SECTION TAB
                        Forms\Components\Tabs\Tab::make('ℹ️ About')
                            ->schema([
                                Forms\Components\Section::make('About Section')
                                    ->description('Company intro - Lines 58-139')
                                    ->schema([
                                        Forms\Components\TextInput::make('sections.about.section_title')
                                            ->label('Section Title')
                                            ->columnSpanFull(),
                                        Forms\Components\RichEditor::make('sections.about.description')
                                            ->label('Description')
                                            ->columnSpanFull(),
                                        Forms\Components\Repeater::make('sections.about.features')
                                            ->label('Features')
                                            ->schema([
                                                Forms\Components\TextInput::make('title')->required(),
                                                Forms\Components\TextInput::make('icon')->default('fa-solid fa-plus'),
                                            ])
                                            ->columns(2)
                                            ->collapsible()
                                            ->columnSpanFull(),
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
                                                Forms\Components\TextInput::make('title')->required(),
                                                Forms\Components\TextInput::make('value')->numeric()->required(),
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
                                            ->url()
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
                                        Forms\Components\Repeater::make('sections.services.tabs')
                                            ->schema([
                                                Forms\Components\TextInput::make('name')->required(),
                                                Forms\Components\TextInput::make('year')->default('2025'),
                                                Forms\Components\TextInput::make('location')->columnSpanFull(),
                                                Forms\Components\TextInput::make('start_date'),
                                                Forms\Components\TextInput::make('end_date'),
                                            ])
                                            ->columns(2)
                                            ->collapsible()
                                            ->columnSpanFull(),
                                    ])->collapsible(),
                            ]),
                        
                        // SHOWCASE TAB
                        Forms\Components\Tabs\Tab::make('📸 Showcase')
                            ->schema([
                                Forms\Components\Section::make('Showcase Carousel')
                                    ->description('Featured projects - Lines 596-648')
                                    ->schema([
                                        Forms\Components\Repeater::make('sections.showcase.items')
                                            ->schema([
                                                Forms\Components\TextInput::make('subtitle')->required(),
                                                Forms\Components\TextInput::make('title')->required()->columnSpanFull(),
                                                Forms\Components\TextInput::make('button_text')->default('more details'),
                                            ])
                                            ->collapsible()
                                            ->columnSpanFull(),
                                    ])->collapsible(),
                            ]),
                        
                        // GALLERY TAB
                        Forms\Components\Tabs\Tab::make('🖼️ Gallery')
                            ->schema([
                                Forms\Components\Section::make('Instagram Gallery')
                                    ->description('Social gallery - Lines 650-748')
                                    ->schema([
                                        Forms\Components\TextInput::make('sections.gallery.section_subtitle')
                                            ->label('Section Subtitle')
                                            ->columnSpanFull(),
                                        Forms\Components\TextInput::make('sections.gallery.instagram_link')
                                            ->label('Instagram URL')
                                            ->url(),
                                        Forms\Components\TextInput::make('sections.gallery.button_text')
                                            ->label('Button Text'),
                                    ])->columns(2)->collapsible(),
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
