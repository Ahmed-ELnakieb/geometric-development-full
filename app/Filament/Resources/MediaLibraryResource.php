<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MediaLibraryResource\Pages;
use App\Filament\Resources\MediaLibraryResource\RelationManagers;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class MediaLibraryResource extends Resource
{
    protected static ?string $model = Media::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'Media Library';

    protected static ?string $navigationGroup = 'Media Management';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';
    
    protected static ?string $modelLabel = 'Media File';
    
    protected static ?string $pluralModelLabel = 'Media Files';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Media Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(191)
                            ->label('Media Name'),
                        Forms\Components\TextInput::make('file_name')
                            ->required()
                            ->maxLength(191)
                            ->label('File Name')
                            ->disabled(),
                        Forms\Components\TextInput::make('collection_name')
                            ->required()
                            ->maxLength(191)
                            ->label('Collection'),
                        Forms\Components\TextInput::make('mime_type')
                            ->disabled()
                            ->label('MIME Type'),
                        Forms\Components\TextInput::make('size')
                            ->disabled()
                            ->formatStateUsing(fn ($state) => number_format($state / 1024, 2) . ' KB')
                            ->label('File Size'),
                        Forms\Components\ViewField::make('preview')
                            ->label('Preview')
                            ->view('filament.forms.components.media-preview')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('preview')
                    ->label('Preview')
                    ->getStateUsing(fn ($record) => $record->mime_type && str_starts_with($record->mime_type, 'image/') 
                        ? $record->getUrl() 
                        : null)
                    ->defaultImageUrl(url('/images/file-icon.png'))
                    ->size(60)
                    ->square(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('file_name')
                    ->searchable()
                    ->label('File Name'),
                TextColumn::make('collection_name')
                    ->label('Collection')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('mime_type')
                    ->label('Type')
                    ->badge(),
                TextColumn::make('size')
                    ->label('Size')
                    ->formatStateUsing(fn ($state) => number_format($state / 1024, 2) . ' KB')
                    ->sortable(),
                TextColumn::make('model_type')
                    ->label('Attached To')
                    ->formatStateUsing(fn ($state) => $state ? class_basename($state) : 'Unattached')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('collection_name')
                    ->options(fn () => Media::query()
                        ->distinct()
                        ->pluck('collection_name', 'collection_name')
                        ->toArray())
                    ->label('Collection'),
                Tables\Filters\SelectFilter::make('mime_type')
                    ->options([
                        'image/jpeg' => 'JPEG',
                        'image/png' => 'PNG',
                        'image/gif' => 'GIF',
                        'image/webp' => 'WebP',
                        'image/svg+xml' => 'SVG',
                        'application/pdf' => 'PDF',
                        'video/mp4' => 'MP4',
                        'video/webm' => 'WebM',
                    ])
                    ->label('File Type'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn ($record) => $record->getUrl())
                    ->openUrlInNewTab()
                    ->label('View File')
                    ->icon('heroicon-o-eye'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn ($record) => $record->getUrl())
                    ->openUrlInNewTab(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListMediaLibraries::route('/'),
            'edit' => Pages\EditMediaLibrary::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Media is created through model uploads only
    }
}
