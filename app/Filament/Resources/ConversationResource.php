<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConversationResource\Pages;
use App\Filament\Resources\ConversationResource\RelationManagers;
use App\Models\Conversation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ConversationResource extends Resource
{
    protected static ?string $model = Conversation::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    
    protected static ?string $navigationLabel = 'Conversations';
    
    protected static ?string $modelLabel = 'Conversation';
    
    protected static ?string $pluralModelLabel = 'Conversations';
    
    protected static ?string $navigationGroup = 'Chat Management';
    
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Conversation Details')
                    ->schema([
                        Forms\Components\TextInput::make('visitor_phone_number')
                            ->label('Visitor Phone')
                            ->tel()
                            ->placeholder('+1234567890'),
                        
                        Forms\Components\Select::make('agent_id')
                            ->label('Assigned Agent')
                            ->relationship('agent', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'active' => 'Active',
                                'waiting' => 'Waiting',
                                'closed' => 'Closed',
                            ])
                            ->required(),
                        
                        Forms\Components\TextInput::make('priority')
                            ->label('Priority')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->maxValue(5),
                        
                        Forms\Components\TextInput::make('source')
                            ->label('Source')
                            ->placeholder('website, whatsapp, etc.'),
                    ])->columns(2),
                
                Forms\Components\Section::make('Metadata')
                    ->schema([
                        Forms\Components\KeyValue::make('metadata')
                            ->label('Additional Information')
                            ->keyLabel('Key')
                            ->valueLabel('Value')
                            ->formatStateUsing(function ($state) {
                                if (is_array($state)) {
                                    return collect($state)->map(function ($value, $key) {
                                        return [
                                            'key' => $key,
                                            'value' => is_array($value) ? json_encode($value) : (string) $value
                                        ];
                                    })->pluck('value', 'key')->toArray();
                                }
                                return $state;
                            }),
                    ])->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->copyable()
                    ->limit(8),
                
                Tables\Columns\TextColumn::make('visitor_phone_number')
                    ->label('Visitor Phone')
                    ->searchable()
                    ->copyable(),
                
                Tables\Columns\TextColumn::make('agent.name')
                    ->label('Agent')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Unassigned'),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'active',
                        'warning' => 'waiting',
                        'danger' => 'closed',
                    ])
                    ->icons([
                        'heroicon-o-chat-bubble-left-right' => 'active',
                        'heroicon-o-clock' => 'waiting',
                        'heroicon-o-x-circle' => 'closed',
                    ]),
                
                Tables\Columns\TextColumn::make('messages_count')
                    ->label('Messages')
                    ->counts('messages')
                    ->badge()
                    ->color('primary'),
                
                Tables\Columns\TextColumn::make('priority')
                    ->label('Priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'gray',
                        '2' => 'info',
                        '3' => 'warning',
                        '4' => 'danger',
                        '5' => 'danger',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('source')
                    ->label('Source')
                    ->badge()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Started')
                    ->dateTime()
                    ->sortable()
                    ->since(),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Activity')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'waiting' => 'Waiting',
                        'closed' => 'Closed',
                    ]),
                
                Tables\Filters\SelectFilter::make('agent_id')
                    ->label('Agent')
                    ->relationship('agent', 'name')
                    ->searchable()
                    ->preload(),
                
                Tables\Filters\SelectFilter::make('priority')
                    ->options([
                        '1' => 'Priority 1 (Low)',
                        '2' => 'Priority 2',
                        '3' => 'Priority 3 (Medium)',
                        '4' => 'Priority 4',
                        '5' => 'Priority 5 (High)',
                    ]),
                
                Tables\Filters\Filter::make('unassigned')
                    ->label('Unassigned')
                    ->query(fn (Builder $query): Builder => $query->whereNull('agent_id')),
            ])
            ->actions([
                Tables\Actions\Action::make('assign_agent')
                    ->label('Assign Agent')
                    ->icon('heroicon-o-user-plus')
                    ->color('primary')
                    ->form([
                        Forms\Components\Select::make('agent_id')
                            ->label('Select Agent')
                            ->options(function () {
                                return \App\Models\ChatAgent::with('user')
                                    ->where('status', 'online')
                                    ->get()
                                    ->pluck('user.name', 'user_id')
                                    ->filter();
                            })
                            ->required(),
                    ])
                    ->action(function (array $data, Conversation $record): void {
                        $record->update(['agent_id' => $data['agent_id']]);
                    })
                    ->visible(fn (Conversation $record): bool => $record->agent_id === null),
                
                Tables\Actions\Action::make('close_conversation')
                    ->label('Close')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(fn (Conversation $record) => $record->update(['status' => 'closed']))
                    ->requiresConfirmation()
                    ->visible(fn (Conversation $record): bool => $record->status !== 'closed'),
                
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('assign_agent')
                        ->label('Assign Agent')
                        ->icon('heroicon-o-user-plus')
                        ->color('primary')
                        ->form([
                            Forms\Components\Select::make('agent_id')
                                ->label('Select Agent')
                                ->options(function () {
                                    return \App\Models\ChatAgent::with('user')
                                        ->where('status', 'online')
                                        ->get()
                                        ->pluck('user.name', 'user_id')
                                        ->filter();
                                })
                                ->required(),
                        ])
                        ->action(function (array $data, $records): void {
                            $records->each(function ($record) use ($data) {
                                $record->update(['agent_id' => $data['agent_id']]);
                            });
                        }),
                    
                    Tables\Actions\BulkAction::make('close_conversations')
                        ->label('Close Conversations')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function ($records): void {
                            $records->each(function ($record) {
                                $record->update(['status' => 'closed']);
                            });
                        })
                        ->requiresConfirmation(),
                    
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('updated_at', 'desc');
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
            'index' => Pages\ListConversations::route('/'),
            'create' => Pages\CreateConversation::route('/create'),
            'edit' => Pages\EditConversation::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['agent', 'messages', 'visitorSession']);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'waiting')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $waitingCount = static::getModel()::where('status', 'waiting')->count();
        
        if ($waitingCount === 0) {
            return 'success';
        } elseif ($waitingCount < 5) {
            return 'warning';
        }
        
        return 'danger';
    }
}
