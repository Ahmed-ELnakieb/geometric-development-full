<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogPostResource\Pages;
use App\Filament\Resources\BlogPostResource\RelationManagers;
use App\Models\BlogPost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms\Get;
use Illuminate\Support\Str;
use Filament\Forms\Set;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make('Content')
                            ->schema([
                                Section::make('Post Information')
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->required()
                                            ->maxLength(191)
                                            ->autofocus()
                                            ->reactive()
                                            ->afterStateUpdated(fn ($state, Set $set) => $set('slug', Str::slug($state))),
                                        Forms\Components\TextInput::make('slug')
                                            ->required()
                                            ->maxLength(191)
                                            ->unique(ignoreRecord: true)
                                            ->disabled(fn (?BlogPost $record) => $record !== null),
                                        Select::make('author_id')
                                            ->relationship('author', 'name')
                                            ->required()
                                            ->searchable()
                                            ->default(auth()->id()),
                                        Forms\Components\Textarea::make('excerpt')
                                            ->rows(3)
                                            ->columnSpanFull()
                                            ->maxLength(500)
                                            ->helperText('Brief summary of the post'),
                                        RichEditor::make('content')
                                            ->required()
                                            ->columnSpanFull()
                                            ->toolbarButtons(['bold', 'italic', 'link', 'bulletList', 'orderedList', 'h2', 'h3', 'blockquote', 'codeBlock'])
                                            ->helperText('Main post content with rich formatting'),
                                        Forms\Components\TextInput::make('read_time')
                                            ->numeric()
                                            ->minValue(1)
                                            ->suffix('min')
                                            ->helperText('Estimated reading time in minutes. Leave empty to auto-calculate'),
                                    ]),
                            ]),
                        Tab::make('Media')
                            ->schema([
                                Section::make('Featured Image')
                                    ->schema([
                                        SpatieMediaLibraryFileUpload::make('featured_image')
                                            ->collection('featured_image')
                                            ->required()
                                            ->visibility('public')
                                            ->helperText('Main image displayed in blog listings and post header. Recommended size: 1200x630px'),
                                    ]),
                                Section::make('Content Images')
                                    ->schema([
                                        SpatieMediaLibraryFileUpload::make('content_images')
                                            ->collection('content_images')
                                            ->multiple()
                                            ->visibility('public')
                                            ->helperText('Additional images that can be referenced in the post content'),
                                    ]),
                            ]),
                        Tab::make('Taxonomy')
                            ->schema([
                                Section::make('Categories & Tags')
                                    ->schema([
                                        Select::make('categories')
                                            ->multiple()
                                            ->relationship('categories', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->helperText('Select one or more categories for this post'),
                                        Select::make('tags')
                                            ->multiple()
                                            ->relationship('tags', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->createOptionForm([
                                                Forms\Components\TextInput::make('name')
                                                    ->required()
                                                    ->unique('blog_tags', 'name')
                                                    ->reactive()
                                                    ->afterStateUpdated(fn ($state, Set $set) => $set('slug', Str::slug($state))),
                                                Forms\Components\TextInput::make('slug')
                                                    ->disabled(),
                                            ])
                                            ->helperText('Select or create tags for this post'),
                                    ]),
                            ]),
                        Tab::make('SEO & Publishing')
                            ->schema([
                                Section::make('SEO Settings')
                                    ->schema([
                                        Forms\Components\TextInput::make('meta_title')
                                            ->maxLength(191)
                                            ->helperText('Leave empty to use post title. Recommended length: 50-60 characters'),
                                        Forms\Components\Textarea::make('meta_description')
                                            ->rows(3)
                                            ->columnSpanFull()
                                            ->maxLength(160)
                                            ->helperText('Recommended length: 150-160 characters'),
                                    ]),
                                Section::make('Publishing Options')
                                    ->schema([
                                        Forms\Components\Toggle::make('is_featured')
                                            ->label('Featured Post')
                                            ->default(false)
                                            ->helperText('Featured posts appear prominently on the blog homepage'),
                                        Forms\Components\Toggle::make('is_published')
                                            ->label('Published')
                                            ->default(false)
                                            ->reactive(),
                                        Forms\Components\DateTimePicker::make('published_at')
                                            ->label('Publish Date')
                                            ->visible(fn (Get $get) => $get('is_published'))
                                            ->dehydrated(fn (Get $get) => $get('is_published'))
                                            ->default(now())
                                            ->helperText('Set a future date to schedule publication'),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('featured_image')
                    ->collection('featured_image')
                    ->label('Image')
                    ->circular(false)
                    ->width(60)
                    ->height(60),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('author.name')
                    ->label('Author')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('categories.name')
                    ->badge()
                    ->color('success')
                    ->separator(', ')
                    ->state(fn (BlogPost $record) => $record->categories->pluck('name')->take(2))
                    ->label('Categories'),
                Tables\Columns\TextColumn::make('tags.name')
                    ->badge()
                    ->color('info')
                    ->separator(', ')
                    ->state(fn (BlogPost $record) => $record->tags->pluck('name')->take(3))
                    ->label('Tags'),
                Tables\Columns\TextColumn::make('comments_count')
                    ->counts('comments')
                    ->label('Comments')
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('read_time')
                    ->numeric()
                    ->sortable()
                    ->suffix(' min')
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Featured')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_published')
                    ->boolean()
                    ->label('Published')
                    ->sortable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime('M d, Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('author')
                    ->relationship('author', 'name')
                    ->label('Author'),
                SelectFilter::make('categories')
                    ->relationship('categories', 'name')
                    ->multiple()
                    ->label('Categories'),
                SelectFilter::make('tags')
                    ->relationship('tags', 'name')
                    ->multiple()
                    ->label('Tags'),
                TernaryFilter::make('is_featured')
                    ->label('Featured')
                    ->placeholder('All posts')
                    ->trueLabel('Featured only')
                    ->falseLabel('Not featured'),
                TernaryFilter::make('is_published')
                    ->label('Published')
                    ->placeholder('All posts')
                    ->trueLabel('Published only')
                    ->falseLabel('Drafts only'),
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
            RelationManagers\CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'edit' => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }
}