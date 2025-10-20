<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Get;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Basic Information')
                            ->schema([
                                Section::make('Project Details')
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->required()
                                            ->maxLength(191)
                                            ->autofocus(),
                                        Forms\Components\TextInput::make('slug')
                                            ->required()
                                            ->maxLength(191)
                                            ->unique(ignoreRecord: true)
                                            ->disabled(fn (?Project $record) => $record !== null),
                                        Forms\Components\TextInput::make('location')
                                            ->required()
                                            ->maxLength(191),
                                        Select::make('type')
                                            ->required()
                                            ->options([
                                                'villa' => 'Villa',
                                                'apartment' => 'Apartment',
                                                'commercial' => 'Commercial',
                                                'investment' => 'Investment',
                                                'mixed_use' => 'Mixed Use',
                                            ]),
                                        Select::make('status')
                                            ->required()
                                            ->options([
                                                'in_progress' => 'In Progress',
                                                'completed' => 'Completed',
                                                'upcoming' => 'Upcoming',
                                                'on_hold' => 'On Hold',
                                            ])
                                            ->default('in_progress'),
                                        Forms\Components\Textarea::make('excerpt')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        RichEditor::make('description')
                                            ->required()
                                            ->columnSpanFull()
                                            ->toolbarButtons([
                                                'bold',
                                                'italic',
                                                'link',
                                                'bulletList',
                                                'orderedList',
                                                'h2',
                                                'h3',
                                            ]),
                                    ]),
                            ]),
                        Tabs\Tab::make('Media & Assets')
                            ->schema([
                                Section::make('Hero Section Media')
                                    ->schema([
                                        SpatieMediaLibraryFileUpload::make('hero_slider')
                                            ->collection('hero_slider')
                                            ->multiple()
                                            ->reorderable()
                                            ->maxFiles(10)
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
                                            ->visibility('public')
                                            ->previewable()
                                            ->openable()
                                            ->downloadable()
                                            ->moveFiles()
                                            ->afterStateHydrated(function ($component, $state) {
                                                if (is_array($state)) {
                                                    $component->state(array_filter($state, fn($item) => !empty($item)));
                                                }
                                            })
                                            ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values(array_filter($state, fn($item) => !empty($item))) : $state)
                                            ->helperText('Upload images for the hero slider. Recommended size: 1920x1080px. You can drag to reorder images.'),
                                        SpatieMediaLibraryFileUpload::make('hero_thumbnails')
                                            ->collection('hero_thumbnails')
                                            ->multiple()
                                            ->reorderable()
                                            ->maxFiles(3)
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
                                            ->visibility('public')
                                            ->previewable()
                                            ->openable()
                                            ->downloadable()
                                            ->moveFiles()
                                            ->afterStateHydrated(function ($component, $state) {
                                                if (is_array($state)) {
                                                    $component->state(array_filter($state, fn($item) => !empty($item)));
                                                }
                                            })
                                            ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values(array_filter($state, fn($item) => !empty($item))) : $state)
                                            ->helperText('Upload up to 3 thumbnail images (max enforced). If not uploaded, thumbnails will be auto-generated from hero slider. You can drag to reorder.'),
                                    ]),
                                Section::make('Gallery & About')
                                    ->schema([
                                        SpatieMediaLibraryFileUpload::make('gallery')
                                            ->collection('gallery')
                                            ->multiple()
                                            ->reorderable()
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
                                            ->visibility('public')
                                            ->previewable()
                                            ->openable()
                                            ->downloadable()
                                            ->moveFiles()
                                            ->afterStateHydrated(function ($component, $state) {
                                                if (is_array($state)) {
                                                    $component->state(array_filter($state, fn($item) => !empty($item)));
                                                }
                                            })
                                            ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values(array_filter($state, fn($item) => !empty($item))) : $state)
                                            ->helperText('Upload project gallery images. You can drag to reorder.'),
                                        SpatieMediaLibraryFileUpload::make('about_image')
                                            ->collection('about_image')
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
                                            ->visibility('public')
                                            ->afterStateHydrated(function ($component, $state) {
                                                if (is_array($state)) {
                                                    $component->state(array_filter($state, fn($item) => !empty($item)));
                                                }
                                            })
                                            ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values(array_filter($state, fn($item) => !empty($item))) : $state)
                                            ->helperText('Upload a single image for the About section'),
                                    ]),
                                Section::make('Video')
                                    ->schema([
                                        Forms\Components\TextInput::make('video_url')
                                            ->label('Video URL')
                                            ->maxLength(500)
                                            ->url()
                                            ->placeholder('https://www.youtube.com/watch?v=...')
                                            ->helperText('YouTube or external video URL'),
                                        SpatieMediaLibraryFileUpload::make('video_preview')
                                            ->collection('video_preview')
                                            ->maxSize(51200)
                                            ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg'])
                                            ->visibility('public')
                                            ->label('Video Preview File')
                                            ->afterStateHydrated(function ($component, $state) {
                                                if (is_array($state)) {
                                                    $component->state(array_filter($state, fn($item) => !empty($item)));
                                                }
                                            })
                                            ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values(array_filter($state, fn($item) => !empty($item))) : $state)
                                            ->helperText('Upload a preview/background video (MP4, WebM, OGG). Max 50MB.')
                                            ->columnSpanFull(),
                                    ]),
                                Section::make('Documents')
                                    ->schema([
                                        SpatieMediaLibraryFileUpload::make('brochure')
                                            ->collection('brochure')
                                            ->maxSize(10240)
                                            ->acceptedFileTypes(['application/pdf'])
                                            ->visibility('public')
                                            ->afterStateHydrated(function ($component, $state) {
                                                if (is_array($state)) {
                                                    $component->state(array_filter($state, fn($item) => !empty($item)));
                                                }
                                            })
                                            ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values(array_filter($state, fn($item) => !empty($item))) : $state)
                                            ->helperText('Upload project brochure (PDF only)'),
                                        SpatieMediaLibraryFileUpload::make('factsheet')
                                            ->collection('factsheet')
                                            ->maxSize(10240)
                                            ->acceptedFileTypes(['application/pdf'])
                                            ->visibility('public')
                                            ->afterStateHydrated(function ($component, $state) {
                                                if (is_array($state)) {
                                                    $component->state(array_filter($state, fn($item) => !empty($item)));
                                                }
                                            })
                                            ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values(array_filter($state, fn($item) => !empty($item))) : $state)
                                            ->helperText('Upload project factsheet (PDF only)'),
                                        SpatieMediaLibraryFileUpload::make('documents')
                                            ->collection('documents')
                                            ->multiple()
                                            ->maxSize(10240)
                                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                                            ->visibility('public')
                                            ->afterStateHydrated(function ($component, $state) {
                                                if (is_array($state)) {
                                                    $component->state(array_filter($state, fn($item) => !empty($item)));
                                                }
                                            })
                                            ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values(array_filter($state, fn($item) => !empty($item))) : $state)
                                            ->helperText('Upload additional project documents'),
                                    ]),
                            ]),
                        Tabs\Tab::make('Property Details')
                            ->schema([
                                Section::make('Unit Information')
                                    ->schema([
                                        Forms\Components\TextInput::make('total_units')
                                            ->numeric()
                                            ->minValue(0)
                                            ->label('Total Units'),
                                        Forms\Components\TextInput::make('property_size_min')
                                            ->numeric()
                                            ->minValue(0)
                                            ->suffix('sq ft')
                                            ->label('Minimum Property Size'),
                                        Forms\Components\TextInput::make('property_size_max')
                                            ->numeric()
                                            ->minValue(0)
                                            ->suffix('sq ft')
                                            ->label('Maximum Property Size'),
                                        Forms\Components\DatePicker::make('completion_date')
                                            ->label('Completion Date')
                                            ->displayFormat('M Y'),
                                    ]),
                                Section::make('Categories')
                                    ->schema([
                                        Select::make('categories')
                                            ->multiple()
                                            ->relationship('categories', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->helperText('Select one or more categories for this project'),
                                    ]),
                            ]),
                        Tabs\Tab::make('SEO & Publishing')
                            ->schema([
                                Section::make('SEO Settings')
                                    ->schema([
                                        Forms\Components\TextInput::make('meta_title')
                                            ->maxLength(191)
                                            ->helperText('Leave empty to use project title'),
                                        Forms\Components\Textarea::make('meta_description')
                                            ->rows(3)
                                            ->columnSpanFull()
                                            ->maxLength(160)
                                            ->helperText('Recommended length: 150-160 characters'),
                                    ]),
                                Section::make('Publishing')
                                    ->schema([
                                        Forms\Components\Toggle::make('is_featured')
                                            ->label('Featured Project')
                                            ->default(false),
                                        Forms\Components\TextInput::make('display_order')
                                            ->numeric()
                                            ->default(0)
                                            ->minValue(0)
                                            ->helperText('Lower numbers appear first'),
                                        Forms\Components\Toggle::make('is_published')
                                            ->label('Published')
                                            ->default(false)
                                            ->reactive(),
                                        Forms\Components\DateTimePicker::make('published_at')
                                            ->label('Publish Date')
                                            ->visible(fn (Get $get) => $get('is_published')),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('location')
                    ->searchable(),
                BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'villa',
                        'success' => 'apartment',
                        'warning' => 'commercial',
                        'danger' => 'investment',
                        'gray' => 'mixed_use',
                    ]),
                BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'in_progress',
                        'success' => 'completed',
                        'info' => 'upcoming',
                        'gray' => 'on_hold',
                    ]),
                SpatieMediaLibraryImageColumn::make('hero_slider')
                    ->collection('hero_slider')
                    ->label('Hero Image')
                    ->circular(false)
                    ->stacked()
                    ->limit(3),
                Tables\Columns\TextColumn::make('categories.name')
                    ->badge()
                    ->limitList(3),
                Tables\Columns\TextColumn::make('total_units')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('completion_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean(),
                Tables\Columns\TextColumn::make('display_order')
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
                SelectFilter::make('type')
                    ->options([
                        'villa' => 'Villa',
                        'apartment' => 'Apartment',
                        'commercial' => 'Commercial',
                        'investment' => 'Investment',
                        'mixed_use' => 'Mixed Use',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'upcoming' => 'Upcoming',
                        'on_hold' => 'On Hold',
                    ]),
                TernaryFilter::make('is_featured')
                    ->label('Featured')
                    ->placeholder('All projects')
                    ->trueLabel('Featured only')
                    ->falseLabel('Not featured'),
                TernaryFilter::make('is_published')
                    ->label('Published')
                    ->placeholder('All projects')
                    ->trueLabel('Published only')
                    ->falseLabel('Drafts only'),
                SelectFilter::make('categories')
                    ->relationship('categories', 'name')
                    ->multiple(),
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
            RelationManagers\UnitTypesRelationManager::class,
            RelationManagers\AmenitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}