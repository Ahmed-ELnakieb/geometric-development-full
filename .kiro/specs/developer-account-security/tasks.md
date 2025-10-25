# Implementation Plan

- [x] 1. Add is_developer column to users table


  - Create migration file to add `is_developer` boolean column with default false
  - Add index on `is_developer` column for query performance
  - Run migration to update database schema
  - _Requirements: 1.3_

- [x] 2. Enhance User model with developer account methods


  - Add `is_developer` to fillable array
  - Add `is_developer` to casts array as boolean
  - Implement `isDeveloper()` method to check if user is developer account
  - Implement `scopeNonDeveloper()` query scope to exclude developer accounts
  - Implement `scopeDeveloper()` query scope to get only developer account
  - _Requirements: 1.3, 2.1, 3.4_

- [x] 3. Create developer account seeder


  - Create `DeveloperAccountSeeder.php` in database/seeders directory
  - Implement seeder to create/update developer account with email ahmedelnakieb95@gmail.com
  - Set password to "elnakieb" using Hash::make()
  - Set role to "super_admin"
  - Set is_developer to true
  - Set is_active to true
  - Set email_verified_at to current timestamp
  - Update DatabaseSeeder to call DeveloperAccountSeeder first in the chain
  - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5_

- [x] 4. Protect developer account in UserResource


  - [x] 4.1 Hide developer account from user list


    - Override `getEloquentQuery()` method in UserResource
    - Apply `nonDeveloper()` scope to exclude developer account from all queries
    - _Requirements: 2.1, 2.2, 2.3, 2.5_

  - [x] 4.2 Prevent developer account editing


    - Override `mount()` method in EditUser page class
    - Check if record is developer account using `isDeveloper()` method
    - Show danger notification if developer account
    - Redirect to user index page if developer account
    - Hide edit action in table for developer records
    - _Requirements: 3.1, 3.4, 3.5_

  - [x] 4.3 Prevent developer account deletion


    - Hide delete action in table for developer records using conditional visibility
    - Add before hook to bulk delete action to filter out developer accounts
    - _Requirements: 3.2, 3.3_

- [x] 5. Create ActivityLogResource for Filament


  - [x] 5.1 Generate ActivityLogResource


    - Create ActivityLogResource class in app/Filament/Resources
    - Set model to Spatie\Activitylog\Models\Activity
    - Configure navigation icon and label
    - Set navigation group to "System"
    - _Requirements: 6.1, 6.2_



  - [ ] 5.2 Configure activity log table display
    - Add columns for id, log_name, description, subject_type, subject_id
    - Add causer relationship column showing user name
    - Add created_at column with dateTime format
    - Make columns sortable and searchable where appropriate
    - Set default sort to created_at descending (newest first)


    - _Requirements: 6.3, 6.6_

  - [ ] 5.3 Add filters to activity log table
    - Add SelectFilter for log_name


    - Add SelectFilter for subject_type
    - Add date range filter for created_at with from/until date pickers
    - _Requirements: 6.4, 6.5_





  - [ ] 5.4 Disable create and edit operations
    - Override `canCreate()` method to return false
    - Override `canEdit()` method to return false
    - Remove create and edit pages from getPages() array
    - _Requirements: 6.7_



- [ ] 6. Implement clear all logs action with developer authentication
  - [ ] 6.1 Create clear logs header action
    - Add Action to getHeaderActions() in ListActivityLogs page
    - Set label to "Clear All Logs"


    - Set icon to heroicon-o-trash
    - Set color to danger
    - Enable requiresConfirmation
    - _Requirements: 4.1_

  - [x] 6.2 Add authentication form to action


    - Add TextInput for developer_email with email validation
    - Add TextInput for developer_password with password type
    - Set placeholders and labels appropriately
    - Make both fields required


    - _Requirements: 4.2, 4.3_

  - [ ] 6.3 Implement credential validation logic
    - Query User model for email matching input
    - Check is_developer flag is true
    - Verify password using Hash::check()
    - Show danger notification if validation fails
    - Return early without clearing logs if invalid
    - _Requirements: 4.4, 4.6_






  - [ ] 6.4 Implement log clearing functionality
    - Use ActivityLog::truncate() to clear all logs
    - Create new activity log entry recording the clear operation
    - Include authenticated user information in log
    - Show success notification after clearing


    - _Requirements: 4.5, 4.7_

- [ ] 7. Create developer credentials documentation
  - Create DEVELOPER-CREDENTIALS.md file in project root
  - Add security warning at top of file
  - Document developer email (ahmedelnakieb95@gmail.com)


  - Document developer password (elnakieb)
  - Document admin panel URL (http://127.0.0.1:8000/admin)
  - Document activity logs URL (http://127.0.0.1:8000/admin/activity-logs)
  - Add security recommendations section
  - Add important notes about account protection
  - Add .gitignore entry for DEVELOPER-CREDENTIALS.md
  - _Requirements: 5.1, 5.2, 5.3, 5.4, 5.5, 5.6, 5.7_

- [ ] 8. Write tests for developer account protection
  - [ ] 8.1 Create unit tests for User model
    - Test isDeveloper() returns true for developer account
    - Test isDeveloper() returns false for regular accounts
    - Test nonDeveloper scope excludes developer account
    - Test developer scope returns only developer account
    - _Requirements: 1.3, 2.1_

  - [ ] 8.2 Create feature tests for developer protection
    - Test developer account is created by seeder
    - Test developer account is hidden from user list
    - Test developer account cannot be edited via URL
    - Test developer account cannot be deleted
    - Test edit and delete actions are hidden for developer
    - _Requirements: 1.1, 2.1, 3.1, 3.2_

  - [ ] 8.3 Create feature tests for clear logs action
    - Test clear logs action requires valid developer credentials
    - Test clear logs fails with invalid email
    - Test clear logs fails with invalid password
    - Test clear logs fails with non-developer account
    - Test clear logs succeeds with valid credentials
    - Test clear logs operation is logged in activity log
    - _Requirements: 4.3, 4.4, 4.5, 4.7_
