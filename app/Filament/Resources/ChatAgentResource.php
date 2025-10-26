<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChatAgentResource\Pages;
use App\Filament\Resources\ChatAgentResource\RelationManagers;
use App\Models\ChatAgent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChatAgentResource extends Resource
{
    protected static ?string $model = ChatAgent::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    
    protected static ?string $navigationLabel = 'Chat Agents';
    
    protected static ?string $modelLabel = 'Chat Agent';
    
    protected static ?string $pluralModelLabel = 'Chat Agents';
    
    protected static ?string $navigationGroup = 'Chat Management';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Agent Information')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('User')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->helperText('Select a user to assign as a chat agent'),
                        
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'online' => 'Online',
                                'away' => 'Away',
                                'offline' => 'Offline',
                            ])
                            ->default('offline')
                            ->required()
                            ->helperText('Current availability status'),
                        
                        Forms\Components\TextInput::make('max_concurrent_chats')
                            ->label('Max Concurrent Chats')
                            ->numeric()
                            ->default(5)
                            ->minValue(1)
                            ->maxValue(20)
                            ->required()
                            ->helperText('Maximum number of simultaneous conversations'),
                        
                        Forms\Components\Toggle::make('auto_assign')
                            ->label('Auto Assign')
                            ->default(true)
                            ->helperText('Automatically assign new conversations to this agent'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Agent Name')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'online',
                        'warning' => 'away',
                        'danger' => 'offline',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'online',
                        'heroicon-o-clock' => 'away',
                        'heroicon-o-x-circle' => 'offline',
                    ]),
                
                Tables\Columns\TextColumn::make('active_conversations_count')
                    ->label('Active Chats')
                    ->counts('activeConversations')
                    ->badge()
                    ->color('primary'),
                
                Tables\Columns\TextColumn::make('max_concurrent_chats')
                    ->label('Max Chats')
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('auto_assign')
                    ->label('Auto Assign')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                Tables\Columns\TextColumn::make('last_activity_at')
                    ->label('Last Activity')
                    ->dateTime()
                    ->sortable()
                    ->toggleable()
                    ->since(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'online' => 'Online',
                        'away' => 'Away',
                        'offline' => 'Offline',
                    ]),
                
                Tables\Filters\TernaryFilter::make('auto_assign')
                    ->label('Auto Assign')
                    ->placeholder('All agents')
                    ->trueLabel('Auto assign enabled')
                    ->falseLabel('Auto assign disabled'),
            ])
            ->actions([
                Tables\Actions\Action::make('toggle_status')
                    ->label('Toggle Status')
                    ->icon('heroicon-o-arrow-path')
                    ->color('secondary')
                    ->action(function (ChatAgent $record) {
                        $newStatus = match ($record->status) {
                            'online' => 'away',
                            'away' => 'offline',
                            'offline' => 'online',
                        };
                        
                        $record->update([
                            'status' => $newStatus,
                            'last_activity_at' => now()
                        ]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Change Agent Status')
                    ->modalDescription('Are you sure you want to change this agent\'s status?'),
                
                Tables\Actions\EditAction::make(),
                
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('set_online')
                        ->label('Set Online')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update([
                                    'status' => 'online',
                                    'last_activity_at' => now()
                                ]);
                            });
                        })
                        ->requiresConfirmation(),
                    
                    Tables\Actions\BulkAction::make('set_offline')
                        ->label('Set Offline')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update([
                                    'status' => 'offline',
                                    'last_activity_at' => now()
                                ]);
                            });
                        })
                        ->requiresConfirmation(),
                    
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
            'index' => Pages\ListChatAgents::route('/'),
            'create' => Pages\CreateChatAgent::route('/create'),
            'edit' => Pages\EditChatAgent::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['user', 'activeConversations']);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'online')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $onlineCount = static::getModel()::where('status', 'online')->count();
        
        if ($onlineCount === 0) {
            return 'danger';
        } elseif ($onlineCount < 3) {
            return 'warning';
        }
        
        return 'success';
    }
}
