<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FooterMenuResource\Pages;
use App\Models\MenuItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FooterMenuResource extends Resource
{
    protected static ?string $model = MenuItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationLabel = 'Footer Menu';

    protected static ?string $modelLabel = 'Footer Item';

    protected static ?string $pluralModelLabel = 'Footer Menu';

    protected static ?string $navigationGroup = 'Menu Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Footer Menu Item Details')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Privacy Policy, Terms, Contact, etc.')
                            ->columnSpanFull(),

                        Forms\Components\Select::make('parent_id')
                            ->label('Parent Menu Item')
                            ->placeholder('None (Top Level)')
                            ->relationship('parent', 'title', function ($query) {
                                return $query->where('type', 'footer')->whereNull('parent_id');
                            })
                            ->searchable()
                            ->preload()
                            ->helperText('Select a parent item to group footer links')
                            ->columnSpanFull(),

                        Forms\Components\Radio::make('link_type')
                            ->label('Link Type')
                            ->options([
                                'page' => 'CMS Page',
                                'project' => 'Project',
                                'route' => 'Route Name',
                                'url' => 'Custom URL',
                                'external' => 'External URL (Social Media)',
                            ])
                            ->default('page')
                            ->inline()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
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
                            ->placeholder('/privacy, /terms, /about, etc.')
                            ->helperText('Internal path (e.g., /privacy, /terms)')
                            ->visible(fn (Forms\Get $get) => $get('link_type') === 'url')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('url')
                            ->label('External URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://facebook.com/yourpage')
                            ->helperText('Social media or external link with https://')
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
                            ->placeholder('fa-facebook, fa-twitter, fa-instagram, etc.')
                            ->helperText('Font Awesome icon class (for social links)')
                            ->columnSpan(1),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Toggle to show/hide this menu item')
                            ->columnSpan(1),

                        Forms\Components\Toggle::make('open_in_new_tab')
                            ->label('Open in New Tab')
                            ->default(false)
                            ->helperText('Useful for social media links')
                            ->columnSpan(1),

                        Forms\Components\Hidden::make('type')
                            ->default('footer'),
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
            'index' => Pages\ListFooterMenus::route('/'),
            'create' => Pages\CreateFooterMenu::route('/create'),
            'edit' => Pages\EditFooterMenu::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('type', 'footer');
    }
}
