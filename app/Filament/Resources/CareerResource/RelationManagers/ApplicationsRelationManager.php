<?php

namespace App\Filament\Resources\CareerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Filters\SelectFilter;

class ApplicationsRelationManager extends RelationManager
{
    protected static string $relationship = 'applications';

    protected static ?string $title = 'Applications';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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

                Forms\Components\SpatieMediaLibraryFileUpload::make('cv_files')
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
                    ->label('CV/Resume')
                    ->helperText('Upload applicant CV or resume'),

                Forms\Components\TextInput::make('portfolio_url')
                    ->url()
                    ->maxLength(500)
                    ->label('Portfolio URL'),

                Forms\Components\Select::make('status')
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
                    ->helperText('Internal notes about this application'),

                Forms\Components\TextInput::make('source_url')
                    ->maxLength(500)
                    ->disabled()
                    ->label('Source URL'),

                Forms\Components\TextInput::make('ip_address')
                    ->maxLength(45)
                    ->disabled()
                    ->label('IP Address'),

                Forms\Components\Textarea::make('user_agent')
                    ->rows(2)
                    ->columnSpanFull()
                    ->disabled()
                    ->label('User Agent'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->getStateUsing(fn ($record) => $record->full_name)
                    ->label('Applicant')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('phone')
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

                Tables\Columns\TextColumn::make('cv')
                    ->label('CV')
                    ->getStateUsing(fn ($record) => $record->getFirstMedia('cv_files') ? 'Download' : 'N/A')
                    ->url(fn ($record) => $record->getFirstMedia('cv_files')?->getUrl(), shouldOpenInNewTab: true)
                    ->color('primary'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->label('Applied At'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'new' => 'New',
                        'reviewing' => 'Reviewing',
                        'shortlisted' => 'Shortlisted',
                        'interviewed' => 'Interviewed',
                        'hired' => 'Hired',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->headerActions([
                CreateAction::make()->disabled(),
            ])
            ->actions([
                EditAction::make()
                    ->after(function ($record) {
                        // Sync cv_file_id with Spatie Media Library after save
                        $cvMedia = $record->getFirstMedia('cv_files');
                        if ($cvMedia && $record->cv_file_id !== $cvMedia->id) {
                            $record->update(['cv_file_id' => $cvMedia->id]);
                        }
                    }),
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
                    BulkAction::make('mark as reviewing')
                        ->action(fn ($records) => $records->each->review()),
                    BulkAction::make('shortlist selected')
                        ->action(fn ($records) => $records->each->shortlist()),
                    BulkAction::make('reject selected')
                        ->action(fn ($records) => $records->each->reject()),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->defaultPaginationPageOption(25);
    }
}