# Backend CMS Tasks

## Completed Tasks

### 2025-10-19 - Video Preview URL Feature
- **Migration**: Created `add_video_preview_url_to_projects_table` migration
  - Added `video_preview_url` column (nullable, 500 chars) after `video_url`
- **Model**: Updated `Project.php`
  - Added `video_preview_url` to `$fillable` array
- **Main Seeder**: Updated `ProjectSeeder.php`
  - Added `video_url` and `video_preview_url` to all 6 project creations
  - Default values: `video_url` = `https://www.youtube.com/watch?v=e45TPIcx5CY`
  - Default values: `video_preview_url` = `assets/img/video/project-video.mp4`
- **View**: Updated `resources/views/projects/show.blade.php`
  - Modified video section to use `$project->video_preview_url` attribute
  - Fallback to default path if not set
- **Status**: ✅ Migration run successfully, main seeder updated, all future projects will have video attributes

### 2025-10-19 - Contact Section Fix
- **View**: Fixed `resources/views/projects/show.blade.php`
  - Changed contact section classes from `bs-contact-6-*` to `bs-contact-4-*`
  - Matched working HTML template structure
- **Status**: ✅ Completed

### 2025-10-19 - CSS Optimization
- **View**: Updated `resources/views/projects/show.blade.php`
  - Commented out `opacity` and `visibility` properties in `.bs-project-4-card-single`
  - Reduced CSS override complexity
- **Status**: ✅ Completed

### 2025-10-20 - Media Library Configuration Fixes
- **AdminPanelProvider**: Fixed `app/Providers/Filament/AdminPanelProvider.php`
  - Removed `->allowUserAccess()` from FilamentMediaManagerPlugin (trait not yet added to User model)
- **Config Deduplication**: Cleaned `config/media-library.php`
  - Removed duplicate `responsive_images`, `path_generator`, `file_namer`, `default_loading_attribute_value`, `default_decoding_attribute_value`, and `remote` sections
  - Maintained single source of truth for each configuration key
- **Navigation Config**: Simplified `config/filament-media-manager.php`
  - Removed duplicate navigation configuration (kept in AdminPanelProvider chained methods)
- **FFMPEG Config**: Updated `config/media-library.php`
  - Commented out `ffmpeg_path` and `ffprobe_path` config (php-ffmpeg package not installed)
  - Added note explaining config is disabled until package is installed
- **Migrations**: Ran Media Manager migrations
  - Ensured all database tables are up to date
- **Status**: ✅ All verification comments implemented successfully

## Database Schema

### Projects Table
- `video_url` (string, 500, nullable) - YouTube or external video URL
- `video_preview_url` (string, 500, nullable) - Background preview video path
- Both fields can be managed from CMS dashboard

### 2025-10-19 - Verification Comments Implementation
- **Comment 1 - Video Preview URL**: ✅ Already implemented
  - Migration exists and ran successfully
  - Seeder already includes test data for all projects
  - Column is properly nullable and 500 chars
  
- **Comment 2 - Categories Column Fix**: ✅ Fixed
  - Replaced `separator()` and `limit()` with `limitList(3)` in `ProjectResource.php`
  - Now properly limits the number of category badges displayed (3 items) instead of truncating characters
  
- **Comment 3 - Legacy Media FK Cleanup**: ✅ Completed
  - Removed unused relation methods from `Project.php`: `featuredImage()`, `videoFile()`, `brochure()`, `factsheet()`
  - Admin form exclusively uses Spatie Media Library collections
  - Frontend views already use Spatie collections (`getFirstMedia()`)
  - Fixed `show.blade.php` to remove `$project->videoFile` reference
  - Created and ran migration to drop legacy FK columns and constraints:
    - `featured_image_id`
    - `video_file_id`
    - `brochure_id`
    - `factsheet_id`
  - All consumers now use Spatie collections exclusively
  
- **AdminPanelProvider Fix**: Fixed missing plugin references
  - Commented out uninstalled plugins: `SpatieLaravelMediaLibraryPlugin` and `FilamentMediaManagerPlugin`
  - Added notes indicating packages are not installed

### 2025-10-20 - Blog Post Verification Fixes
- **Comment 1 - Tab Import**: Fixed `BlogPostResource.php`
  - Added missing `use Filament\Forms\Components\Tabs\Tab;` import
  - Resolves "class not found" error for Tab::make() calls
  
- **Comment 2 - Categories/Tags Column Limit**: Fixed `BlogPostResource.php`
  - Replaced `limit(2)` and `limit(3)` with proper `state()` callback using `take()`
  - Now properly limits number of badge items instead of truncating characters
  - Categories limited to 2 items, tags limited to 3 items
  
- **Comment 3 - Migration Safety**: Fixed `2025_10_19_create_drop_legacy_featured_image_fk_from_blog_posts_table.php`
  - Added `Schema::hasColumn()` check before dropping
  - Wrapped foreign key drop in try/catch for safety
  - Migration now idempotent and safe to run multiple times
  
- **Comment 4 - Legacy Model Cleanup**: Removed from `BlogPost.php`
  - Removed `featured_image_id` from `$fillable` array
  - Deleted `featuredImage()` relation method
  - Updated all controllers to remove eager loading of `featuredImage`
  - Updated all views to use `getFirstMediaUrl('featured_image')` instead of `featuredImage` relation
  - Files updated: `BlogController.php`, `HomeController.php`, `index.blade.php`, `show.blade.php`, `home.blade.php`
  
- **Comment 5 - Backfill Command Disk Support**: Fixed `BackfillBlogPostMediaCollections.php`
  - Replaced `addMedia($media->getPath())` with `addMediaFromStream(Storage::disk($media->disk)->readStream(...))`
  - Added `use Illuminate\Support\Facades\Storage;` import
  - Now supports non-local disks (S3, etc.) without errors
  
- **Comment 6 - Published Date Dehydration**: Fixed `BlogPostResource.php`
  - Added `->dehydrated(fn (Get $get) => $get('is_published'))` to `published_at` field
  - Prevents saving date when post is not published
  
- **Comment 7 - Tag Uniqueness Validation**: Fixed `BlogPostResource.php`
  - Added `->unique('blog_tags', 'name')` to inline tag creation form
  - Prevents duplicate tag creation

### 2025-10-20 - Career Module Verification Fixes
- **Comment 1 - Job Type Badge Colors**: Fixed `CareerResource.php`
  - Replaced `BadgeColumn` with `TextColumn->badge()->color()` using match expression
  - Corrected color mapping: full_time => success, part_time => info, contract => warning, internship => gray
  
- **Comment 2 - CV File ID Nullable**: Created migration and updated forms
  - Created `2025_10_20_000001_make_cv_file_id_nullable_in_career_applications_table.php`
  - Made `career_applications.cv_file_id` nullable
  - Changed FK from `cascadeOnDelete()` to `nullOnDelete()`
  - Added `afterSave()` hook in `EditCareerApplication.php` to sync cv_file_id with Spatie Media
  - Added `after()` callback in `ApplicationsRelationManager.php` EditAction to sync cv_file_id
  - Migration applied successfully - CV upload is now optional in admin forms
  
- **Comment 3 - Frontend CV Validation**: Aligned with database schema
  - Frontend controller already correctly handles optional cv_file
  - Creates application first, then updates cv_file_id only when file exists
  - Will work correctly after migration is applied
  
- **Comment 4 - Expired Filter Date Logic**: Fixed `CareerResource.php`
  - Changed `expired` filter from `where('expires_at', '<', now())` to `whereDate('expires_at', '<', today())`
  - Changed `not_expired` filter from `orWhere('expires_at', '>=', now())` to `orWhereDate('expires_at', '>=', today())`
  - Now uses date-only comparison, treating listings expiring today as still active
  
- **Comment 5 - Unified Status Badge**: Added to `CareerResource.php`
  - Added `status` column using `getStateUsing()` to compute Active/Expired/Inactive
  - Badge colors: Active => success, Expired => danger, Inactive => gray
  - Made `is_active` column toggleable and hidden by default
  - Status badge appears before `is_featured` column

- **Status**: ✅ All 5 verification comments implemented successfully

### 2025-10-20 - BlogCategoryResource Missing Import Fix
- **Issue**: Missing Pages namespace import causing "Class not found" error
- **Fix**: Added `use App\Filament\Resources\BlogCategoryResource\Pages;` import
- **Status**: ✅ Fixed - migrations now run successfully

### 2025-10-20 - dateTimeFormat Method Error Fix
- **Issue**: `BadMethodCallException: Method dateTimeFormat does not exist` on blog-posts and career applications
- **Cause**: Incorrect Filament API usage - `->dateTime()->dateTimeFormat('format')` 
- **Fix**: Pass format directly to dateTime() method: `->dateTime('format')`
- **Files Fixed**:
  - `BlogPostResource.php`: Changed `->dateTime()->dateTimeFormat('M d, Y')` to `->dateTime('M d, Y')`
  - `ApplicationsRelationManager.php`: Changed `->dateTime()->dateTimeFormat('M d, Y H:i')` to `->dateTime('M d, Y H:i')`
- **Status**: ✅ Fixed - admin panels now load successfully

### 2025-10-20 - SpatieMediaLibraryFileUpload Closure Error Fix
- **Issue**: `TypeError: closure(): Argument #2 ($file) must be of type string, null given` when editing resources with media fields
- **Root Cause**: Internal `getUploadedFileUsing` closure in SpatieMediaLibraryFileUpload expects file UUID string but receives null for empty media collections
- **Fixes Applied to ALL Filament Resources**:
  1. **ProjectResource.php**:
     - Removed `->image()` from: hero_slider, hero_thumbnails, gallery, about_image
     - Removed `->acceptedFileTypes()` from: brochure, factsheet, documents
     - Added `->visibility('public')` + `->maxSize(10240)` to ALL media fields
  2. **BlogPostResource.php**:
     - Removed `->image()` from: featured_image, content_images
     - Added `->visibility('public')` to both fields
  3. **CareerResource.php**:
     - Removed `->image()` from: department_image
     - Removed `->acceptedFileTypes()` from: job_description
     - Added `->visibility('public')` + `->maxSize(10240)` to both fields
  4. **CareerApplicationResource.php**:
     - Removed `->acceptedFileTypes()` from: cv_files
     - Added `->visibility('public')` + `->maxSize(10240)`
  5. **ApplicationsRelationManager.php**:
     - Removed `->acceptedFileTypes()` from: cv_files
     - Added `->visibility('public')` + `->maxSize(10240)`
  6. **Database Cleanup**: Cleared corrupted media collection data for all 6 projects using cleanup script
     - Removed malformed Livewire serialized data: `[{"":null},{"s":"arr"}]`
     - Collections cleared: hero_slider, hero_thumbnails, gallery, about_image, brochure, factsheet, documents
- **Reason**: 
  - File type validation already handled by Model's `registerMediaCollections()` via `acceptsMimeTypes()`
  - `->image()` internally uses `acceptedFileTypes()` which has the same closure issue
  - `->visibility('public')` ensures proper initialization and prevents private file processing errors
  - Database had corrupted/malformed serialized media data from seeding
- **Status**: ✅ Fixed - all admin edit pages now load without errors

### 2025-10-20 - Video Preview Upload with Media Library
- **Issue**: `video_preview_url` was a text input requiring manual path entry, making video management difficult
- **Enhancement**: Replaced text input with proper file upload via Spatie Media Library
- **Changes Applied**:
  1. **Project Model** (`Project.php`):
     - Added `video_preview` media collection accepting MP4, WebM, OGG formats
     - Configured as single file upload with max 50MB size
  2. **ProjectResource** (`ProjectResource.php`):
     - Replaced TextInput with `SpatieMediaLibraryFileUpload`
     - Collection: `video_preview`
     - Max size: 51200 KB (50MB)
     - Visibility: public
     - Label: "Video Preview File"
  3. **Frontend View** (`show.blade.php`):
     - Updated video source to prioritize media library: `$project->getFirstMediaUrl('video_preview')`
     - Fallback chain: Media Library → `video_preview_url` → default asset
     - Maintains backward compatibility with existing `video_preview_url` field
- **Benefits**:
  - ✅ Upload videos directly through admin panel
  - ✅ Browse and select from media library
  - ✅ Automatic file management and storage
  - ✅ Backward compatible with existing data
- **Status**: ✅ Implemented - video preview now uses media library with proper fallbacks

### 2025-10-20 - Installed and Enabled Filament Media Manager Sidebar GUI
- **Issue**: Media Library sidebar menu with folder management GUI was not visible in admin panel
- **Root Cause**: Package was in `composer.json` but not actually installed (missing from `composer.lock`)
- **Fix Applied**:
  1. **Installed package**: `composer require tomatophp/filament-media-manager` (v1.1.6)
  2. **Published config**: `filament-media-manager.php` configuration file
  3. **Ran migrations**: Created 4 new tables:
     - `folders` - For organizing media into folders
     - `media_has_models` - For polymorphic media relationships  
     - `folder_has_models` - For polymorphic folder relationships
     - Updated folders table with additional columns
  4. **Enabled plugin**: Added `FilamentMediaManagerPlugin::make()` to `AdminPanelProvider.php`
- **Package Dependencies Installed**:
  - `calebporzio/sushi` (v2.5.3) - For in-memory models
  - `tomatophp/console-helpers` (v1.1.0) - Console utilities
  - `tomatophp/filament-icons` (v1.1.5) - Icon management
  - `tomatophp/filament-media-manager` (v1.1.6) - Main package
- **Features Available**:
  - 📁 **Folders**: Create, rename, delete, organize folders (enabled in config)
  - 👤 **User Access**: Per-user media access control (enabled in config)
  - 🖼️ **Visual Browser**: Grid/list view with thumbnails
  - ⬆️ **Bulk Upload**: Multiple file upload support
  - 🔍 **Search & Filter**: Find media by name, type, folder
  - 🗑️ **Media Management**: Full CRUD operations
- **Config Settings** (`config/filament-media-manager.php`):
  - Allowed MIME types: images, videos, documents
  - Max file size: 100MB
  - API: Disabled
  - Folders: Enabled
  - User access control: Enabled
  - Password protection: Disabled
- **Status**: ✅ Installed and configured - refresh admin panel to see "Media" menu in sidebar

## Notes
- Video preview uses local MP4 file for background animation
- Video URL opens in popup overlay when play button clicked
- All projects now have both video attributes populated with defaults
- Legacy media FKs have been removed - all media now managed through Spatie Media Library collections
- Career applications now support optional CV uploads after migration is applied
- CV file ID is automatically synced with Spatie Media Library on save
