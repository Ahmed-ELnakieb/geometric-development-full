<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NavbarMenuResource\Pages;
use App\Models\MenuItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NavbarMenuResource extends Resource
{
    protected static ?string $model = MenuItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3';

    protected static ?string $navigationLabel = 'Navbar Menu';

    protected static ?string $modelLabel = 'Navbar Item';

    protected static ?string $pluralModelLabel = 'Navbar Menu';

    protected static ?string $navigationGroup = 'Menu Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Menu Item Details')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Home, About, Services, etc.')
                            ->columnSpanFull(),

                        Forms\Components\Select::make('parent_id')
                            ->label('Parent Menu Item')
                            ->placeholder('None (Top Level)')
                            ->relationship('parent', 'title', function ($query) {
                                return $query->where('type', 'navbar')->whereNull('parent_id');
                            })
                            ->searchable()
                            ->preload()
                            ->helperText('Select a parent item to create a dropdown menu')
                            ->columnSpanFull(),

                        Forms\Components\Radio::make('link_type')
                            ->label('Link Type')
                            ->options([
                                'page' => 'CMS Page',
                                'project' => 'Project',
                                'route' => 'Route Name',
                                'url' => 'Custom URL',
                                'external' => 'External URL',
                            ])
                            ->default('page')
                            ->inline()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                // Clear fields when switching
                                $set('url', null);
                                $set('route', null);
                                $set('project_id', null);
                            })
                            ->columnSpanFull(),

                        Forms\Components\Select::make('url')
                            ->label('Select CMS Page')
                            ->options(function () {
                                return \App\Models\Page::pluck('title', 'slug')
                                    ->mapWithKeys(fn ($title, $slug) => ["/{$slug}" => "📄 {$title}"])
                                    ->toArray();
                            })
                            ->searchable()
                            ->helperText('Select a page from your CMS')
                            ->visible(fn (Forms\Get $get) => $get('link_type') === 'page')
                            ->columnSpanFull(),

                        Forms\Components\Select::make('project_id')
                            ->label('Select Project')
                            ->options(function () {
                                return \App\Models\Project::pluck('title', 'id')
                                    ->mapWithKeys(fn ($title, $id) => [$id => "🏢 {$title}"])
                                    ->toArray();
                            })
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                // Auto-fill title with project name if title is empty
                                if ($state && !$get('title')) {
                                    $project = \App\Models\Project::find($state);
                                    if ($project) {
                                        $set('title', $project->title);
                                    }
                                }
                            })
                            ->helperText('Select a project - title will auto-fill but you can customize it')
                            ->visible(fn (Forms\Get $get) => $get('link_type') === 'project')
                            ->columnSpanFull(),

                        Forms\Components\Select::make('route')
                            ->label('Select Route')
                            ->options(function () {
                                $routes = [];
                                foreach (\Illuminate\Support\Facades\Route::getRoutes() as $route) {
                                    if ($route->getName() && !str_starts_with($route->getName(), 'filament.') && !str_starts_with($route->getName(), 'livewire.')) {
                                        $icon = match(true) {
                                            str_contains($route->getName(), 'home') => '🏠',
                                            str_contains($route->getName(), 'project') => '🏢',
                                            str_contains($route->getName(), 'blog') => '📰',
                                            str_contains($route->getName(), 'career') => '💼',
                                            str_contains($route->getName(), 'contact') => '📧',
                                            default => '🔗',
                                        };
                                        $routes[$route->getName()] = $icon . ' ' . $route->getName() . ' (' . implode(', ', $route->methods()) . ')';
                                    }
                                }
                                ksort($routes);
                                return $routes;
                            })
                            ->searchable()
                            ->helperText('Select from all available routes')
                            ->visible(fn (Forms\Get $get) => $get('link_type') === 'route')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('url')
                            ->label('URL Path')
                            ->maxLength(255)
                            ->placeholder('/about, /privacy, /terms, etc.')
                            ->helperText('Internal path (e.g., /about, /privacy)')
                            ->visible(fn (Forms\Get $get) => $get('link_type') === 'url')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('url')
                            ->label('External URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://example.com')
                            ->helperText('Full external URL with https://')
                            ->visible(fn (Forms\Get $get) => $get('link_type') === 'external')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('order')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->helperText('Lower numbers appear first (e.g., 1, 2, 3...)')
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('icon')
                            ->maxLength(255)
                            ->placeholder('fa-home, fa-phone, etc.')
                            ->helperText('Font Awesome icon class (optional)')
                            ->columnSpan(1),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Toggle to show/hide this menu item')
                            ->columnSpan(1),

                        Forms\Components\Toggle::make('open_in_new_tab')
                            ->label('Open in New Tab')
                            ->default(false)
                            ->columnSpan(1),

                        Forms\Components\Hidden::make('type')
                            ->default('navbar'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('order')
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->label('#')
                    ->sortable()
                    ->width(50),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->description(fn (MenuItem $record): string => $record->parent ? 'Child of: ' . $record->parent->title : 'Top Level'),

                Tables\Columns\TextColumn::make('url')
                    ->label('URL/Route')
                    ->formatStateUsing(fn (MenuItem $record) => $record->url ?: ($record->route ? "route: {$record->route}" : '-'))
                    ->copyable()
                    ->color('gray'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('open_in_new_tab')
                    ->label('New Tab')
                    ->boolean(),

                Tables\Columns\TextColumn::make('icon')
                    ->badge()
                    ->color('info')
                    ->default('-'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->placeholder('All items')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),

                Tables\Filters\Filter::make('parent_items')
                    ->label('Top Level Only')
                    ->query(fn ($query) => $query->whereNull('parent_id')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('order')
            ->defaultGroup('parent.title');
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
            'index' => Pages\ListNavbarMenus::route('/'),
            'create' => Pages\CreateNavbarMenu::route('/create'),
            'edit' => Pages\EditNavbarMenu::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('type', 'navbar');
    }
}
