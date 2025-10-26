<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VisitResource\Pages;
use App\Filament\Resources\VisitResource\RelationManagers;
use App\Models\Visit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VisitResource extends Resource
{
    protected static ?string $model = Visit::class;

    protected static ?string $navigationIcon = 'heroicon-o-eye';
    
    protected static ?string $navigationGroup = 'Analytics';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('visitable_type')
                    ->maxLength(191),
                Forms\Components\TextInput::make('visitable_id')
                    ->numeric(),
                Forms\Components\TextInput::make('visitor_type')
                    ->maxLength(191),
                Forms\Components\TextInput::make('visitor_id')
                    ->numeric(),
                Forms\Components\TextInput::make('ip')
                    ->maxLength(191),
                Forms\Components\Textarea::make('user_agent')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('referer')
                    ->maxLength(191),
                Forms\Components\TextInput::make('url')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('method')
                    ->required()
                    ->maxLength(191)
                    ->default('GET'),
                Forms\Components\TextInput::make('data'),
                Forms\Components\Toggle::make('is_admin')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->searchable()
                    ->limit(50),
                    
                Tables\Columns\TextColumn::make('visitable_type')
                    ->label('Content Type')
                    ->formatStateUsing(fn ($state) => $state ? class_basename($state) : 'N/A')
                    ->badge()
                    ->color('primary'),
                    
                Tables\Columns\TextColumn::make('visitable.title')
                    ->label('Content')
                    ->limit(30)
                    ->placeholder('N/A'),
                    
                Tables\Columns\TextColumn::make('visitor.name')
                    ->label('Visitor')
                    ->placeholder('Anonymous'),
                    
                Tables\Columns\TextColumn::make('ip')
                    ->label('IP Address')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('referer')
                    ->label('Referrer')
                    ->limit(30)
                    ->placeholder('Direct'),
                    
                Tables\Columns\IconColumn::make('is_admin')
                    ->label('Admin')
                    ->boolean()
                    ->trueIcon('heroicon-o-shield-check')
                    ->falseIcon('heroicon-o-globe-alt'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Visited At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('is_admin')
                    ->label('Visit Type')
                    ->options([
                        0 => 'Frontend',
                        1 => 'Admin',
                    ]),
                    
                Tables\Filters\SelectFilter::make('visitable_type')
                    ->label('Content Type')
                    ->options([
                        'App\\Models\\Page' => 'Pages',
                        'App\\Models\\BlogPost' => 'Blog Posts',
                        'App\\Models\\Project' => 'Projects',
                    ]),
                    
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVisits::route('/'),
            'create' => Pages\CreateVisit::route('/create'),
            'edit' => Pages\EditVisit::route('/{record}/edit'),
        ];
    }
}
