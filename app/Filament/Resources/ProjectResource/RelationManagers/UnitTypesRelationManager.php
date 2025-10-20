<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UnitTypesRelationManager extends RelationManager
{
    protected static string $relationship = 'unitTypes';

    protected static ?string $title = 'Unit Types';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(191)
                    ->label('Unit Type Name'),

                Forms\Components\TextInput::make('size_min')
                    ->numeric()
                    ->minValue(0)
                    ->suffix('sq ft')
                    ->label('Minimum Size'),

                Forms\Components\TextInput::make('size_max')
                    ->numeric()
                    ->minValue(0)
                    ->suffix('sq ft')
                    ->label('Maximum Size'),

                Forms\Components\Textarea::make('description')
                    ->rows(3)
                    ->columnSpanFull(),

                Forms\Components\Select::make('image_id')
                    ->relationship('image', 'name')
                    ->searchable()
                    ->helperText('Select an image from the media library or upload via Media Manager'),

                Forms\Components\TextInput::make('display_order')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->label('Display Order'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->defaultSort('display_order')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('size_min')
                    ->numeric()
                    ->sortable()
                    ->suffix(' sq ft'),

                Tables\Columns\TextColumn::make('size_max')
                    ->numeric()
                    ->sortable()
                    ->suffix(' sq ft'),

                Tables\Columns\TextColumn::make('description')
                    ->limit(50),

                Tables\Columns\TextColumn::make('display_order')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
            ->reorderable('display_order');
    }
}