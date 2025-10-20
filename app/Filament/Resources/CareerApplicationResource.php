<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CareerApplicationResource\Pages;
use App\Models\CareerApplication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;

class CareerApplicationResource extends Resource
{
    protected static ?string $model = CareerApplication::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationLabel = 'Job Applications';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('career_id')
                    ->relationship('career', 'title')
                    ->required()
                    ->searchable()
                    ->disabledOn('edit'),
                Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('last_name')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('email')
                    ->required()
                    ->email()
                    ->maxLength(191),
                Forms\Components\TextInput::make('phone')
                    ->required()
                    ->maxLength(50),
                Forms\Components\Textarea::make('cover_letter')
                    ->rows(4)
                    ->columnSpanFull(),
                SpatieMediaLibraryFileUpload::make('cv_files')
                    ->collection('cv_files')
                    ->maxSize(10240)
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                    ->visibility('public')
                    ->afterStateHydrated(function ($component, $state) {
                        if (is_array($state)) {
                            $component->state(array_filter($state, fn($item) => !empty($item)));
                        }
                    })
                    ->dehydrateStateUsing(fn ($state) => is_array($state) ? array_values(array_filter($state, fn($item) => !empty($item))) : $state)
                    ->label('CV/Resume'),
                Forms\Components\TextInput::make('portfolio_url')
                    ->url()
                    ->maxLength(500),
                Select::make('status')
                    ->required()
                    ->options([
                        'new' => 'New',
                        'reviewing' => 'Reviewing',
                        'shortlisted' => 'Shortlisted',
                        'interviewed' => 'Interviewed',
                        'hired' => 'Hired',
                        'rejected' => 'Rejected',
                    ])
                    ->default('new'),
                Forms\Components\Textarea::make('notes')
                    ->rows(3)
                    ->columnSpanFull()
                    ->helperText('Internal notes'),
                Section::make('Application Metadata')
                    ->collapsed()
                    ->schema([
                        Forms\Components\TextInput::make('source_url')
                            ->disabled(),
                        Forms\Components\TextInput::make('ip_address')
                            ->disabled(),
                        Forms\Components\Textarea::make('user_agent')
                            ->disabled()
                            ->rows(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('career.title')
                    ->label('Position')
                    ->searchable()
                    ->sortable()
                    ->limit(40),
                TextColumn::make('full_name')
                    ->getStateUsing(fn ($record) => $record->full_name)
                    ->label('Applicant')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('phone')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'info',
                        'reviewing' => 'warning',
                        'shortlisted' => 'primary',
                        'interviewed' => 'success',
                        'hired' => 'success',
                        'rejected' => 'danger',
                    })
                    ->sortable(),
                TextColumn::make('cv')
                    ->label('CV')
                    ->getStateUsing(fn ($record) => $record->getFirstMedia('cv_files') ? 'Download' : 'N/A')
                    ->url(fn ($record) => $record->getFirstMedia('cv_files')?->getUrl())
                    ->openUrlInNewTab()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Applied At'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('career')
                    ->relationship('career', 'title')
                    ->searchable(),
                SelectFilter::make('status')
                    ->options([
                        'new' => 'New',
                        'reviewing' => 'Reviewing',
                        'shortlisted' => 'Shortlisted',
                        'interviewed' => 'Interviewed',
                        'hired' => 'Hired',
                        'rejected' => 'Rejected',
                    ]),
                Filter::make('recent')
                    ->query(fn (Builder $query): Builder => $query->where('created_at', '>=', now()->subDays(30)))
                    ->toggle()
                    ->default(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                Action::make('review')
                    ->visible(fn ($record) => $record->status === 'new')
                    ->action(fn ($record) => $record->review())
                    ->icon('heroicon-o-eye')
                    ->color('info'),
                Action::make('shortlist')
                    ->visible(fn ($record) => in_array($record->status, ['new', 'reviewing']))
                    ->action(fn ($record) => $record->shortlist())
                    ->icon('heroicon-o-star')
                    ->color('warning'),
                Action::make('interview')
                    ->visible(fn ($record) => $record->status === 'shortlisted')
                    ->action(fn ($record) => $record->interview())
                    ->icon('heroicon-o-calendar')
                    ->color('primary'),
                Action::make('hire')
                    ->visible(fn ($record) => in_array($record->status, ['interviewed', 'shortlisted']))
                    ->action(fn ($record) => $record->hire())
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation(),
                Action::make('reject')
                    ->visible(fn ($record) => !in_array($record->status, ['hired', 'rejected']))
                    ->action(fn ($record) => $record->reject())
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('mark_as_reviewing')
                        ->action(fn (Collection $records) => $records->each->review())
                        ->deselectRecordsAfterCompletion()
                        ->icon('heroicon-o-eye')
                        ->color('info'),
                    BulkAction::make('shortlist_selected')
                        ->action(fn (Collection $records) => $records->each->shortlist())
                        ->deselectRecordsAfterCompletion()
                        ->icon('heroicon-o-star')
                        ->color('warning'),
                    BulkAction::make('reject_selected')
                        ->action(fn (Collection $records) => $records->each->reject())
                        ->deselectRecordsAfterCompletion()
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCareerApplications::route('/'),
            'view' => Pages\ViewCareerApplication::route('/{record}'),
            'edit' => Pages\EditCareerApplication::route('/{record}/edit'),
        ];
    }
}