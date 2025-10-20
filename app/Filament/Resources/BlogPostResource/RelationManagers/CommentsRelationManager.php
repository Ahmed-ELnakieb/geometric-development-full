<?php

namespace App\Filament\Resources\BlogPostResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    protected static ?string $title = 'Comments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->helperText('Registered user who posted the comment'),

                Forms\Components\TextInput::make('author_name')
                    ->maxLength(191)
                    ->label('Guest Name')
                    ->helperText('Name for non-registered commenters'),

                Forms\Components\TextInput::make('author_email')
                    ->email()
                    ->maxLength(191)
                    ->label('Guest Email')
                    ->helperText('Email for non-registered commenters'),

                Forms\Components\Textarea::make('content')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull()
                    ->label('Comment Content'),

                Forms\Components\Select::make('status')
                    ->required()
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'spam' => 'Spam',
                    ])
                    ->default('pending'),

                Forms\Components\Select::make('parent_id')
                    ->relationship('parent', 'content')
                    ->searchable()
                    ->helperText('Select a parent comment if this is a reply'),

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
            ->recordTitleAttribute('content')
            ->defaultSort('created_at', 'desc')
            ->defaultPaginationPageOption(25)
            ->columns([
                Tables\Columns\TextColumn::make('author')
                    ->label('Author')
                    ->getStateUsing(fn ($record) => $record->user ? $record->user->name : $record->author_name)
                    ->searchable(['author_name', 'user.name']),

                Tables\Columns\TextColumn::make('content')
                    ->limit(80)
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'spam' => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('parent_id')
                    ->label('Reply To')
                    ->formatStateUsing(fn ($state) => $state ? 'Reply' : 'Parent')
                    ->badge()
                    ->color(fn ($state) => $state ? 'info' : null),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Posted At'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'spam' => 'Spam',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->disabled(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('approve')
                    ->visible(fn ($record) => $record->status !== 'approved')
                    ->action(fn ($record) => $record->approve())
                    ->icon('heroicon-o-check-circle')
                    ->color('success'),
                Tables\Actions\Action::make('reject')
                    ->visible(fn ($record) => $record->status !== 'rejected')
                    ->action(fn ($record) => $record->reject())
                    ->icon('heroicon-o-x-circle')
                    ->color('danger'),
                Tables\Actions\Action::make('mark as spam')
                    ->visible(fn ($record) => $record->status !== 'spam')
                    ->action(fn ($record) => $record->markAsSpam())
                    ->icon('heroicon-o-exclamation-triangle')
                    ->color('warning'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('approve selected')
                        ->action(fn ($records) => $records->each->approve()),
                    Tables\Actions\BulkAction::make('reject selected')
                        ->action(fn ($records) => $records->each->reject()),
                    Tables\Actions\BulkAction::make('mark as spam')
                        ->action(fn ($records) => $records->each->markAsSpam()),
                ]),
            ]);
    }
}