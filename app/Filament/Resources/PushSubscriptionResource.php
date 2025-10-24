<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PushSubscriptionResource\Pages;
use App\Filament\Resources\PushSubscriptionResource\RelationManagers;
use App\Models\PushSubscription;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PushSubscriptionResource extends Resource
{
    protected static ?string $model = PushSubscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';
    
    protected static ?string $navigationLabel = 'Push Subscriptions';
    
    protected static ?string $navigationGroup = 'PWA Management';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Subscription Details')
                    ->schema([
                        Forms\Components\TextInput::make('endpoint')
                            ->label('Endpoint URL')
                            ->disabled()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('User')
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('user_agent')
                            ->label('Browser/Device')
                            ->disabled(),
                        Forms\Components\DateTimePicker::make('last_used_at')
                            ->label('Last Used')
                            ->disabled(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->default('Guest')
                    ->badge()
                    ->color(fn ($record) => $record->user_id ? 'success' : 'gray'),
                Tables\Columns\TextColumn::make('endpoint')
                    ->label('Endpoint')
                    ->limit(50)
                    ->searchable()
                    ->copyable()
                    ->tooltip(fn ($record) => $record->endpoint),
                Tables\Columns\TextColumn::make('user_agent')
                    ->label('Device')
                    ->limit(30)
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_used_at')
                    ->label('Last Used')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->default('Never'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Subscribed')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('User Type')
                    ->options([
                        'with_user' => 'Registered Users',
                        'without_user' => 'Guest Users',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['value'] === 'with_user') {
                            return $query->whereNotNull('user_id');
                        } elseif ($data['value'] === 'without_user') {
                            return $query->whereNull('user_id');
                        }
                    }),
                Tables\Filters\Filter::make('active')
                    ->label('Active Subscriptions')
                    ->query(fn (Builder $query) => $query->active()),
            ])
            ->actions([
                Tables\Actions\Action::make('test')
                    ->label('Send Test')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('info')
                    ->action(function (PushSubscription $record) {
                        $service = app(\App\Services\PushNotificationService::class);
                        $notification = $service->createNotification(
                            'Test Notification',
                            'This is a test notification from the admin panel.',
                            ['data' => ['url' => '/', 'action' => 'admin_test']]
                        );
                        $result = $service->sendToSubscription($record, $notification);
                        
                        if ($result['success']) {
                            \Filament\Notifications\Notification::make()
                                ->title('Test notification sent successfully')
                                ->success()
                                ->send();
                        } else {
                            \Filament\Notifications\Notification::make()
                                ->title('Failed to send test notification')
                                ->danger()
                                ->send();
                        }
                    }),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListPushSubscriptions::route('/'),
            'create' => Pages\CreatePushSubscription::route('/create'),
            'edit' => Pages\EditPushSubscription::route('/{record}/edit'),
        ];
    }
}
