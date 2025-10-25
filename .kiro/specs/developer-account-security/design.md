# Design Document

## Overview

This design implements a secure developer account protection system with activity log management capabilities. The solution adds a protected developer account that cannot be modified through the admin interface and requires developer authentication for sensitive operations like clearing activity logs.

The implementation leverages Laravel's existing authentication system, Filament's resource customization capabilities, and Spatie's Activity Log package to create a secure, maintainable solution.

## Architecture

### High-Level Components

1. **Database Layer**
   - Migration to add `is_developer` flag to users table
   - Developer account seeder
   - Activity log table (already exists via Spatie package)

2. **Model Layer**
   - User model enhancement with developer account methods
   - Activity log model (provided by Spatie)

3. **Filament Admin Layer**
   - UserResource modifications for developer account protection
   - ActivityLogResource for viewing and managing logs
   - Custom action for clearing logs with authentication modal

4. **Documentation**
   - DEVELOPER-CREDENTIALS.md file with access information

### Data Flow

```
Admin User Action → Filament Resource → Model Query → Database
                         ↓
                  Developer Check
                         ↓
              Allow/Deny Operation
```

For clearing logs:
```
Click Clear Logs → Modal Opens → Enter Credentials → Validate → Clear Logs → Success Message
```

## Components and Interfaces

### 1. Database Migration

**File**: `database/migrations/2024_01_XX_add_is_developer_to_users_table.php`

```php
Schema::table('users', function (Blueprint $table) {
    $table->boolean('is_developer')->default(false)->after('is_active');
    $table->index('is_developer');
});
```

**Purpose**: Add a flag to identify the protected developer account

### 2. Developer Account Seeder

**File**: `database/seeders/DeveloperAccountSeeder.php`

**Responsibilities**:
- Create developer account if it doesn't exist
- Set email: ahmedelnakieb95@gmail.com
- Set password: elnakieb (hashed)
- Set role: super_admin
- Set is_developer: true
- Set is_active: true

**Key Logic**:
```php
User::updateOrCreate(
    ['email' => 'ahmedelnakieb95@gmail.com'],
    [
        'name' => 'Developer',
        'password' => Hash::make('elnakieb'),
        'role' => 'super_admin',
        'is_developer' => true,
        'is_active' => true,
        'email_verified_at' => now(),
    ]
);
```

### 3. User Model Enhancement

**File**: `app/Models/User.php`

**New Methods**:

```php
// Check if user is the developer account
public function isDeveloper(): bool
{
    return $this->is_developer === true;
}

// Scope to exclude developer account
public function scopeNonDeveloper($query)
{
    return $query->where('is_developer', false);
}

// Scope to get only developer account
public function scopeDeveloper($query)
{
    return $query->where('is_developer', true);
}
```

**Updated Fillable**:
Add `is_developer` to fillable array (but protect it in resource)

**Updated Casts**:
```php
'is_developer' => 'boolean',
```

### 4. UserResource Modifications

**File**: `app/Filament/Resources/UserResource.php`

**Query Modification**:
```php
public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()->nonDeveloper();
}
```

**Edit Page Protection**:
Override `canEdit()` method in EditUser page:
```php
public function mount(int | string $record): void
{
    parent::mount($record);
    
    if ($this->record->isDeveloper()) {
        Notification::make()
            ->danger()
            ->title('Access Denied')
            ->body('The developer account cannot be modified.')
            ->send();
            
        $this->redirect(UserResource::getUrl('index'));
    }
}
```

**Delete Protection**:
Add to table configuration:
```php
->actions([
    Tables\Actions\EditAction::make()
        ->hidden(fn (User $record) => $record->isDeveloper()),
    Tables\Actions\DeleteAction::make()
        ->hidden(fn (User $record) => $record->isDeveloper()),
])
->bulkActions([
    Tables\Actions\BulkActionGroup::make([
        Tables\Actions\DeleteBulkAction::make()
            ->before(function ($records) {
                // Filter out developer accounts
                return $records->reject(fn ($record) => $record->isDeveloper());
            }),
    ]),
])
```

### 5. ActivityLogResource

**File**: `app/Filament/Resources/ActivityLogResource.php`

**Table Configuration**:
```php
->columns([
    Tables\Columns\TextColumn::make('id')->sortable(),
    Tables\Columns\TextColumn::make('log_name')->sortable()->searchable(),
    Tables\Columns\TextColumn::make('description')->sortable()->searchable(),
    Tables\Columns\TextColumn::make('subject_type')->sortable(),
    Tables\Columns\TextColumn::make('subject_id')->sortable(),
    Tables\Columns\TextColumn::make('causer.name')
        ->label('User')
        ->sortable()
        ->searchable(),
    Tables\Columns\TextColumn::make('created_at')
        ->label('Date')
        ->dateTime()
        ->sortable(),
])
->defaultSort('created_at', 'desc')
```

**Filters**:
```php
->filters([
    Tables\Filters\SelectFilter::make('log_name'),
    Tables\Filters\SelectFilter::make('subject_type'),
    Tables\Filters\Filter::make('created_at')
        ->form([
            Forms\Components\DatePicker::make('created_from'),
            Forms\Components\DatePicker::make('created_until'),
        ])
        ->query(function (Builder $query, array $data): Builder {
            return $query
                ->when($data['created_from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                ->when($data['created_until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
        }),
])
```

**Disable Create/Edit**:
```php
public static function canCreate(): bool
{
    return false;
}

public static function canEdit(Model $record): bool
{
    return false;
}
```

### 6. Clear All Logs Action

**Implementation**: Custom header action in ActivityLogResource

```php
protected function getHeaderActions(): array
{
    return [
        Action::make('clearAllLogs')
            ->label('Clear All Logs')
            ->icon('heroicon-o-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->form([
                Forms\Components\TextInput::make('developer_email')
                    ->label('Developer Email')
                    ->email()
                    ->required()
                    ->placeholder('ahmedelnakieb95@gmail.com'),
                Forms\Components\TextInput::make('developer_password')
                    ->label('Developer Password')
                    ->password()
                    ->required()
                    ->placeholder('Enter developer password'),
            ])
            ->action(function (array $data) {
                // Validate developer credentials
                $developer = User::where('email', $data['developer_email'])
                    ->where('is_developer', true)
                    ->first();
                
                if (!$developer || !Hash::check($data['developer_password'], $developer->password)) {
                    Notification::make()
                        ->danger()
                        ->title('Authentication Failed')
                        ->body('Invalid developer credentials.')
                        ->send();
                    return;
                }
                
                // Clear all logs
                ActivityLog::truncate();
                
                // Log this action
                activity()
                    ->causedBy(auth()->user())
                    ->log('Cleared all activity logs (authenticated by developer)');
                
                Notification::make()
                    ->success()
                    ->title('Success')
                    ->body('All activity logs have been cleared.')
                    ->send();
            }),
    ];
}
```

### 7. Documentation File

**File**: `DEVELOPER-CREDENTIALS.md`

**Content Structure**:
```markdown
# Developer Credentials

⚠️ **SECURITY WARNING**: This file contains sensitive credentials. Keep it secure and never commit to version control.

## Developer Account

- **Email**: ahmedelnakieb95@gmail.com
- **Password**: elnakieb
- **Role**: Super Admin
- **Protected**: Yes (cannot be edited or deleted via dashboard)

## Admin Panel Access

- **Local URL**: http://127.0.0.1:8000/admin
- **Production URL**: [Add your production URL here]

## Activity Logs

- **Local URL**: http://127.0.0.1:8000/admin/activity-logs
- **Production URL**: [Add your production URL here]

## Important Notes

1. The developer account is protected and cannot be modified through the admin dashboard
2. Clearing activity logs requires developer authentication
3. This account should only be used for critical system operations
4. Change the password in production environments
5. Keep this file secure and out of version control

## Security Recommendations

- [ ] Change developer password after initial setup
- [ ] Use environment variables for credentials in production
- [ ] Enable two-factor authentication if available
- [ ] Regularly review activity logs for suspicious activity
- [ ] Backup logs before clearing them
```

## Data Models

### User Model Updates

**New Field**:
- `is_developer` (boolean): Identifies protected developer account

**New Methods**:
- `isDeveloper()`: Check if user is developer
- `scopeNonDeveloper()`: Query scope to exclude developer
- `scopeDeveloper()`: Query scope to get developer only

### Activity Log Model

Uses Spatie's ActivityLog model (no modifications needed):
- `id`: Primary key
- `log_name`: Category of log
- `description`: Action description
- `subject_type`: Model type that was acted upon
- `subject_id`: ID of the model
- `causer_type`: User model type
- `causer_id`: User ID who performed action
- `properties`: JSON data with additional info
- `created_at`: Timestamp

## Error Handling

### Developer Account Protection

**Scenario**: Admin tries to edit developer account
- **Detection**: Check `is_developer` flag in mount method
- **Response**: Show danger notification and redirect to user list
- **Logging**: Log the attempt in activity log

**Scenario**: Admin tries to delete developer account
- **Detection**: Hide delete action for developer records
- **Response**: Action not available in UI
- **Logging**: No action needed (prevented at UI level)

### Clear Logs Authentication

**Scenario**: Invalid credentials provided
- **Detection**: Credential validation fails
- **Response**: Show danger notification with error message
- **Action**: Do not clear logs, keep modal open
- **Logging**: Log failed authentication attempt

**Scenario**: Non-developer email provided
- **Detection**: User found but `is_developer` is false
- **Response**: Show authentication failed message
- **Action**: Do not clear logs
- **Logging**: Log unauthorized attempt

### Database Errors

**Scenario**: Seeder fails to create developer account
- **Detection**: Exception during User::updateOrCreate()
- **Response**: Log error and throw exception
- **Action**: Halt seeding process
- **Logging**: Laravel's default exception logging

## Testing Strategy

### Unit Tests

**File**: `tests/Unit/Models/UserTest.php`

Tests:
1. `test_is_developer_returns_true_for_developer_account()`
2. `test_is_developer_returns_false_for_regular_account()`
3. `test_non_developer_scope_excludes_developer()`
4. `test_developer_scope_returns_only_developer()`

### Feature Tests

**File**: `tests/Feature/DeveloperAccountProtectionTest.php`

Tests:
1. `test_developer_account_is_created_by_seeder()`
2. `test_developer_account_is_hidden_from_user_list()`
3. `test_developer_account_cannot_be_edited_via_url()`
4. `test_developer_account_cannot_be_deleted()`
5. `test_clear_logs_requires_valid_developer_credentials()`
6. `test_clear_logs_fails_with_invalid_credentials()`
7. `test_clear_logs_succeeds_with_valid_credentials()`
8. `test_clear_logs_action_is_logged()`

### Manual Testing Checklist

1. Run migrations and seeders
2. Verify developer account exists in database
3. Login to admin panel
4. Verify developer account not visible in user list
5. Try to access developer edit page via URL
6. Verify redirect and error message
7. Navigate to activity logs
8. Click "Clear All Logs" button
9. Enter invalid credentials - verify error
10. Enter valid credentials - verify logs cleared
11. Check DEVELOPER-CREDENTIALS.md file exists
12. Verify file is in .gitignore

## Security Considerations

### Password Storage
- Developer password stored using Laravel's bcrypt hashing
- Never store plain text passwords
- Consider using environment variables in production

### Credential File Protection
- Add DEVELOPER-CREDENTIALS.md to .gitignore
- Consider encrypting file in production
- Restrict file permissions on server (chmod 600)

### Authentication Validation
- Validate both email AND is_developer flag
- Use Hash::check() for password verification
- Rate limit authentication attempts (future enhancement)

### Activity Logging
- Log all attempts to access developer account
- Log all clear logs operations
- Include authenticated user information
- Preserve logs before clearing (backup recommendation)

### UI Protection
- Hide actions at UI level (first line of defense)
- Validate at controller level (second line of defense)
- Validate at model level if needed (third line of defense)

## Performance Considerations

### Query Optimization
- Index on `is_developer` column for fast filtering
- Use query scopes to avoid N+1 queries
- Eager load relationships in activity log resource

### Caching
- Consider caching developer account lookup
- Cache activity log counts if displayed
- Clear cache when developer account is modified

### Bulk Operations
- Filter developer account before bulk delete
- Use database transactions for log clearing
- Consider chunking for large log deletions

## Future Enhancements

1. **Two-Factor Authentication**: Add 2FA for developer account
2. **Rate Limiting**: Limit authentication attempts for clear logs
3. **Log Backup**: Automatic backup before clearing logs
4. **Audit Trail**: Separate audit log for developer actions
5. **Environment-Based Credentials**: Use .env for developer credentials
6. **Multiple Developer Accounts**: Support for multiple protected accounts
7. **Log Export**: Export logs before clearing
8. **Scheduled Log Cleanup**: Automatic old log deletion with retention policy
