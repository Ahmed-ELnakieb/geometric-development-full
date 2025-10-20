<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CareerResource\Pages;
use App\Filament\Resources\CareerResource\RelationManagers;
use App\Models\Career;
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
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Get;

class CareerResource extends Resource
{
    protected static ?string $model = Career::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Job Details')
                            ->schema([
                                Section::make('Basic Information')
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->required()
                                            ->maxLength(191)
                                            ->autofocus()
                                            ->reactive(),
                                        Forms\Components\TextInput::make('slug')
                                            ->required()
                                            ->maxLength(191)
                                            ->unique(ignoreRecord: true)
                                            ->disabled(fn (?Career $record) => $record !== null),
                                        Forms\Components\TextInput::make('location')
                                            ->required()
                                            ->maxLength(191),
                                        Select::make('job_type')
                                            ->required()
                                            ->options([
                                                'full_time' => 'Full Time',
                                                'part_time' => 'Part Time',
                                                'contract' => 'Contract',
                                                'internship' => 'Internship',
                                            ]),
                                        Forms\Components\TextInput::make('salary_range')
                                            ->maxLength(191)
                                            ->placeholder('e.g., Competitive, $50k-$70k'),
                                        Forms\Components\TextInput::make('working_days')
                                            ->maxLength(191)
                                            ->placeholder('e.g., Sunday – Thursday'),
                                        Forms\Components\Textarea::make('overview')
                                            ->required()
                                            ->rows(4)
                                            ->columnSpanFull()
                                            ->helperText('Brief overview of the position'),
                                    ]),
                                Section::make('Detailed Description')
                                    ->schema([
                                        RichEditor::make('responsibilities')
                                            ->required()
                                            ->columnSpanFull()
                                            ->toolbarButtons(['bold', 'italic', 'link', 'bulletList', 'orderedList'])
                                            ->helperText('List key responsibilities using bullet points'),
                                        RichEditor::make('requirements')
                                            ->columnSpanFull()
                                            ->toolbarButtons(['bold', 'italic', 'link', 'bulletList', 'orderedList'])
                                            ->helperText('List required qualifications and skills'),
                                        RichEditor::make('benefits')
                                            ->columnSpanFull()
                                            ->toolbarButtons(['bold', 'italic', 'link', 'bulletList', 'orderedList'])
                                            ->helperText('List benefits and perks offered'),
                                    ]),
                            ]),
                        Tabs\Tab::make('Media (Optional)')
                            ->schema([
                                Section::make('Job Documents')
                                    ->schema([
                                        SpatieMediaLibraryFileUpload::make('job_description')
                                            ->collection('job_description')
                                            ->maxSize(10240)
                                            ->visibility('public')
                                            ->helperText('Optional: Upload a detailed job description document'),
                                        SpatieMediaLibraryFileUpload::make('department_image')
                                            ->collection('department_image')
                                            ->visibility('public')
                                            ->helperText('Optional: Upload a department or team photo'),
                                    ]),
                            ]),
                        Tabs\Tab::make('Publishing & SEO')
                            ->schema([
                                Section::make('Status & Visibility')
                                    ->schema([
                                        Forms\Components\Toggle::make('is_featured')
                                            ->label('Featured Position')
                                            ->default(false)
                                            ->helperText('Featured positions appear prominently on the careers page'),
                                        Forms\Components\Toggle::make('is_active')
                                            ->label('Active')
                                            ->default(true)
                                            ->helperText('Only active positions are visible on the website'),
                                        Forms\Components\DatePicker::make('expires_at')
                                            ->label('Expiration Date')
                                            ->helperText('Optional: Set a date when this position should no longer be visible'),
                                        Forms\Components\TextInput::make('display_order')
                                            ->numeric()
                                            ->default(0)
                                            ->minValue(0)
                                            ->helperText('Lower numbers appear first'),
                                    ]),
                                Section::make('SEO Settings')
                                    ->schema([
                                        Forms\Components\TextInput::make('meta_title')
                                            ->maxLength(191)
                                            ->helperText('Leave empty to use job title'),
                                        Forms\Components\Textarea::make('meta_description')
                                            ->rows(3)
                                            ->columnSpanFull()
                                            ->maxLength(160)
                                            ->helperText('Recommended length: 150-160 characters'),
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
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('location')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('job_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'full_time' => 'success',
                        'part_time' => 'info',
                        'contract' => 'warning',
                        'internship' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('salary_range')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('working_days')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('applications_count')
                    ->counts('applications')
                    ->label('Applications')
                    ->badge()
                    ->color('primary')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->getStateUsing(fn ($record) => !$record->is_active ? 'Inactive' : ($record->isExpired() ? 'Expired' : 'Active'))
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Active' => 'success',
                        'Expired' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('expires_at')
                    ->date()
                    ->sortable()
                    ->color(fn ($state) => $state && $state->isPast() ? 'danger' : null)
                    ->label('Expires'),
                Tables\Columns\TextColumn::make('display_order')
                    ->numeric()
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
                SelectFilter::make('job_type')
                    ->options([
                        'full_time' => 'Full Time',
                        'part_time' => 'Part Time',
                        'contract' => 'Contract',
                        'internship' => 'Internship',
                    ]),
                TernaryFilter::make('is_featured')
                    ->label('Featured')
                    ->placeholder('All positions')
                    ->trueLabel('Featured only')
                    ->falseLabel('Not featured'),
                TernaryFilter::make('is_active')
                    ->label('Active')
                    ->placeholder('All positions')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
                Filter::make('expired')
                    ->query(fn (Builder $query): Builder => $query->whereDate('expires_at', '<', today()))
                    ->label('Expired Positions')
                    ->toggle(),
                Filter::make('not_expired')
                    ->query(fn (Builder $query): Builder => $query->where(fn ($q) => $q->whereNull('expires_at')->orWhereDate('expires_at', '>=', today())))
                    ->label('Active (Not Expired)')
                    ->toggle()
                    ->default(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('display_order');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ApplicationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCareers::route('/'),
            'create' => Pages\CreateCareer::route('/create'),
            'edit' => Pages\EditCareer::route('/{record}/edit'),
        ];
    }
}