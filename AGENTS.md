# AGENTS.md - Geometric Development CMS

## Build/Lint/Test Commands

### Development
- `composer run dev` - Start Laravel dev server + Vite + queue worker + logs (concurrent)
- `npm run dev` - Start Vite dev server only
- `php artisan serve` - Start Laravel dev server only

### Build
- `npm run build` - Build frontend assets for production
- `composer run setup` - Full setup (install deps, migrate, build assets)

### Testing
- `composer run test` - Run all PHPUnit tests with config clear
- `php artisan test` - Run all tests
- `php artisan test --filter=TestName` - Run single test class
- `php artisan test tests/Feature/SpecificTest.php` - Run single test file

### Code Quality
- `composer run pint` - Format PHP code with Laravel Pint
- `php artisan config:clear` - Clear config cache
- `php artisan cache:clear` - Clear application cache

## Architecture & Codebase Structure

### Framework & Stack
- **Laravel 11** with PHP 8.2+
- **Filament 3.2** admin panel
- **MySQL** database (configured for `test` database)
- **Vite + Tailwind CSS 4** for frontend assets
- **Service Layer Pattern** for business logic separation

### Key Directories
- `app/Http/Controllers/Frontend/` - Frontend controllers (Home, Project, Blog, etc.)
- `app/Services/Frontend/` - Business logic services (BaseService, ProjectService, etc.)
- `app/Http/Requests/Frontend/` - Form validation classes
- `resources/views/frontend/` - Blade templates with layouts/partials structure
- `geo-design/` - Static HTML/CSS/JS assets and templates
- `database/migrations/` - Database schema (users, projects, pages, media, etc.)

### Database Schema
- `users` - Enhanced with roles (super_admin, content_manager, etc.)
- `projects` - Real estate projects with media relations
- `pages` - CMS pages with SEO support
- `settings` - Dynamic site configuration
- `media` - Spatie Media Library files
- `project_categories` - Project categorization

### Internal APIs
- Service classes handle all business logic
- Repository pattern ready for data access
- Helper functions in `app/helpers.php` for common utilities
- Settings system for dynamic configuration

## Code Style Guidelines

### PHP/Laravel Conventions
- PSR-4 autoloading with `App\\` namespace
- 4-space indentation (configured in .editorconfig)
- Type hints on all method parameters and return types
- DocBlocks for public methods and complex logic
- Service layer pattern: Controllers → Services → Models

### Naming Conventions
- Controllers: `PascalCase` (e.g., `HomeController`, `ProjectController`)
- Services: `PascalCase` ending with `Service` (e.g., `ProjectService`)
- Models: `PascalCase` singular (e.g., `User`, `Project`)
- Database: snake_case (e.g., `project_categories`)
- Routes: kebab-case (e.g., `projects.show`)

### Error Handling
- Form requests for validation with custom messages
- Try-catch blocks in services for business logic errors
- Laravel's built-in exception handling
- Database transactions for multi-step operations

### Frontend Integration
- Blade templates in `resources/views/frontend/`
- Master layout: `frontend.layouts.app`
- Component-based partials (header, footer)
- Dynamic content from CMS settings system
- SEO optimization with meta tags
