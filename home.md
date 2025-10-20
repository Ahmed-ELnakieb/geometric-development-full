# 🏠 Home Page Complete Documentation
**File:** `resources/views/home.blade.php`  
**Controller:** `App\Http\Controllers\Frontend\HomeController`  
**Last Updated:** October 20, 2025

---

## 📋 Table of Contents
1. [Overview](#overview)
2. [Data Flow & Architecture](#data-flow--architecture)
3. [Page Sections Analysis](#page-sections-analysis)
4. [Management Guide](#management-guide)
5. [Recommendations](#recommendations)

---

## Overview

The `home.blade.php` view serves as the main landing page for Geometric Development's real estate website. This comprehensive documentation outlines every section, distinguishes between static (hardcoded) and dynamic (database-driven) content, and provides actionable guidance for content management.

### Key Information
- **Layout:** Extends `layouts.app`
- **Route:** `/` (root URL)
- **Total Lines:** 804
- **Sections:** 9 major content sections
- **Dynamic Sections:** 2 (Residential Properties, Blog Posts)
- **Static Sections:** 7 (requires code changes)

---

## Data Flow & Architecture

### Controller: `HomeController@index()`
**Location:** `app/Http/Controllers/Frontend/HomeController.php`

```php
public function index()
{
    // Fetch 6 featured published projects
    $projects = Project::published()
        ->featured()
        ->ordered()
        ->take(6)
        ->with('categories')
        ->get();
    
    // Fetch 3 recent published blog posts
    $blogPosts = BlogPost::published()
        ->recent()
        ->take(3)
        ->with('author', 'categories')
        ->get();
    
    // Fetch 3 featured active careers (UNUSED in view)
    $careers = Career::active()
        ->notExpired()
        ->featured()
        ->take(3)
        ->get();
    
    return view('home', compact('projects', 'blogPosts', 'careers'));
}
```

### Data Models & Database Tables

| Model | Table | Used In Section | Purpose |
|-------|-------|-----------------|---------|
| `Project` | `projects` | Residential Properties (Section 6) | Display featured projects with images, titles, locations |
| `BlogPost` | `blog_posts` | Blog Section (Section 9) | Display recent blog posts with featured images |
| `Career` | `careers` | ❌ Not Used | Passed to view but not rendered |
| `Media` (Spatie) | `media` | Sections 6 & 9 | Store and retrieve images for projects and blog posts |

### Model Scopes Used

**Project Model:**
- `published()` - Only published projects
- `featured()` - Only featured projects
- `ordered()` - Sorted by display_order field

**BlogPost Model:**
- `published()` - Only published posts
- `recent()` - Sorted by published_at date (newest first)

---

## Page Sections Analysis

### 1. Hero Section (Lines 9-56)
**Purpose**: Main banner with company branding and call-to-action.

#### Static Content
- Background image: `assets/img/hero/h5-bg-img-1.png`
- Company title: "Leading Community Developer in MUROJ"
- Subtitle: "Inspiration of MUROJ in EGYPT"
- Rotating text options: "Luxury Living", "Invest Smart", "Buy Quality", "Dream Home"
- Button text: "IN GEOMETRIC"
- Hero images: `assets/img/hero/h5-img-1.png`, `assets/img/hero/h5-img-3.png`
- Icon: `flaticon-next-1`

#### Dynamic Content
- **Call-to-action button**: Links to `route('projects.index')` - redirects to projects listing page
- **Management**: No dynamic management - all content is hardcoded in the view file. Changes require code modifications.

### 2. About Section (Lines 58-139)
**Purpose**: Company introduction and key features.

#### Static Content
- Section title: "Your trusted partner in finding properties and investment opportunities in Egypt's most desirable locations."
- Description text: "Discover your perfect property with Geometric Development. We specialize in premium real estate sales across Egypt's most sought-after destinations, including exclusive residential communities in Muroj. Our expert team guides you through every step of your property buying journey."
- Feature list:
  - Prime Locations
  - Luxury Amenities
  - Modern Design
  - Smart Homes
  - Eco-Friendly
  - Premium Finishes
  - Investment Opportunities
  - Customizable Options
- Background images: `assets/img/about/a5-bg-shape.png`, `assets/img/about/a5-bg-shape-2.png`
- Content images: `assets/img/about/a5-img-1.png`, `assets/img/about/a5-img-2.png`

#### Dynamic Content
- **"Know About Us" button**: Links to `route('page.show', 'about')` - redirects to about page
- **Management**: No dynamic management - all content is hardcoded. Features list and description text require code changes to modify.

### 3. Counter Section (Lines 141-199)
**Purpose**: Statistics display showing company achievements.

#### Static Content
- Counter items:
  - Properties Sold: 2.5k+ (hardcoded value "2.5")
  - Years in Real Estate: 15+ (hardcoded value "15")
  - Happy Homeowners: 98% (hardcoded value "98")
  - Market Leadership: 1st (hardcoded value "1")
- Description texts for each counter are hardcoded

#### Dynamic Content
- None - all values are hardcoded in the view
- **Management**: No dynamic management - counters are static values. Changes require code modifications. Could be enhanced to use Settings model for dynamic values.

### 4. Video Section (Lines 201-221)
**Purpose**: Promotional video display.

#### Static Content
- Video file: `assets/img/video/v2-video-1.mp4`
- YouTube link: "https://www.youtube.com/watch?v=e45TPIcx5CY"
- Play button icon

#### Dynamic Content
- None
- **Management**: Video URL and file are hardcoded. Changes require code modifications. Could be managed via Settings or Media library.

### 5. Services/Projects Section (Lines 222-464)
**Purpose**: Showcase of different projects/services (labeled as services but displays projects).

#### Static Content
- Section title: "Geometric Development Premium Real Estate Services"
- Tab labels: "Muroj Villa", "Mina Marina", "Rich Hills", "Ras Al Khaimah"
- Static project details for each tab:
  - Dates: "jan 02, 2025" to "aug 02, 2025" (repeated for all)
  - Locations: "136 North Coast, Egypt", "New Cairo, Egypt", "North Coast, Egypt", "Hurghada, Egypt"
  - Images: `assets/img/projects/p1-img-1.png` through `p1-img-4.png`
  - Background image: `assets/img/projects/p1-bg-img-1.png`
- Social media share links (non-functional "#")

#### Dynamic Content
- **Project links**: Each tab links to `route('projects.index')` - redirects to projects listing
- **Management**: All project data is hardcoded. No database integration. Changes require code modifications. Should be converted to use Project model data.

### 6. Residential Properties Section (Lines 467-594)
**Purpose**: Display featured residential projects.

#### Static Content
- Section title: "RESIDENTIAL PROPERTIES"
- "View All Projects" button text
- Layout structure and CSS classes

#### Dynamic Content
- **Projects data**: Uses `$projects` collection (up to 6 items)
  - Project title: `$project->title`
  - Project location: `$project->location`
  - Project image: `$project->getMedia('gallery')->first()?->getUrl()` with fallback to `assets/img/random/random (10).png`
  - Project link: `route('projects.show', $project->slug)`
- **"View All Projects" button**: Links to `route('projects.index')`
- **Data Source**: `Project` model with scopes: `published()`, `featured()`, `ordered()`, `take(6)`
- **Management**: Fully dynamic via Project model. Managed through Filament admin panel with CRUD operations on `projects` table.

### 7. Showcase Section (Lines 596-648)
**Purpose**: Featured project showcase carousel.

#### Static Content
- Showcase items:
  - Muroj: "Luxury waterfront living with muroj views"
  - Rich Hills: "World-class Rich Hills with premium amenities"
- Images: `assets/img/showcase/sh1-img-1.png`, `assets/img/showcase/sh1-img-2.png`
- Navigation arrows: `assets/img/illus/left-arrow.png`, `assets/img/illus/right-arrow.png`
- Links point to "project-details.html" (static HTML, not dynamic routes)

#### Dynamic Content
- None - all content is hardcoded
- **Management**: No dynamic management. Changes require code modifications. Should be integrated with Project model.

### 8. Gallery Section (Lines 650-748)
**Purpose**: Instagram gallery display.

#### Static Content
- Section title: "Stay Inspired with Instagram"
- All gallery images: `assets/img/gallery/g2-img-1.png` through `g2-img-7.png`
- "Follow Us" button (links to "#")
- Instagram icon

#### Dynamic Content
- None
- **Management**: Static image assets. Could be enhanced to integrate with Instagram API or use Media library for dynamic gallery management.

### 9. Blog Section (Lines 750-802)
**Purpose**: Recent blog posts display.

#### Static Content
- Section title: "news & ideas"
- "View All Blogs" button text
- Icon: `assets/img/hero/h3-start.png`

#### Dynamic Content
- **Blog posts data**: Uses `$blogPosts` collection (up to 3 items)
  - Post title: `$post->title`
  - Post excerpt: `$post->excerpt`
  - Publication date: `$post->published_at->format('d M Y')`
  - Featured image: `$post->getFirstMediaUrl('featured_image')` with fallback to `assets/img/random/random (10).png`
  - Post link: `route('blog.show', $post->slug)`
- **"View All Blogs" button**: Links to `route('blog.index')`
- **Data Source**: `BlogPost` model with scopes: `published()`, `recent()`, `take(3)`
- **Management**: Fully dynamic via BlogPost model. Managed through Filament admin panel with CRUD operations on `blog_posts` table.

---

## Management Guide

### 🎯 Dynamic Content (Can be managed via admin panel)

#### Section 6: Residential Properties
**Admin URL:** `http://127.0.0.1:8000/admin/projects`

**How to Manage:**
1. Navigate to admin panel → **Projects**
2. Edit existing project or create new one
3. Set **"Featured"** toggle to `Yes`
4. Set **"Published"** toggle to `Yes`
5. Adjust **"Display Order"** for position (lower numbers appear first)
6. Upload gallery images to **"Gallery" collection**
7. Up to 6 projects will display on homepage

**Required Fields:**
- Title
- Location
- Slug
- Gallery Images (at least 1)
- Status: Published
- Featured: Yes

#### Section 9: Blog Posts
**Admin URL:** `http://127.0.0.1:8000/admin/blog-posts`

**How to Manage:**
1. Navigate to admin panel → **Blog Posts**
2. Edit existing post or create new one
3. Set **"Published"** toggle to `Yes`
4. Set **"Published At"** date
5. Upload featured image to **"Featured Image" collection**
6. Add excerpt (displays on homepage)
7. Up to 3 most recent posts will display

**Required Fields:**
- Title
- Excerpt
- Published At date
- Featured Image
- Status: Published

### ⚠️ Static Content (Requires code changes)

#### Sections Requiring Code Modifications:

| Section | Lines | What to Edit | File Location |
|---------|-------|--------------|---------------|
| Hero | 9-56 | Titles, subtitles, rotating text, images | `resources/views/home.blade.php` |
| About | 58-139 | Description, feature list, images | `resources/views/home.blade.php` |
| Counters | 141-199 | Counter values, descriptions | `resources/views/home.blade.php` |
| Video | 201-221 | Video file, YouTube URL | `resources/views/home.blade.php` |
| Services Tabs | 222-464 | Tab names, dates, locations, images | `resources/views/home.blade.php` |
| Showcase | 596-648 | Project names, descriptions, images | `resources/views/home.blade.php` |
| Gallery | 650-748 | Instagram images, links | `resources/views/home.blade.php` |

#### How to Edit Static Content:
1. Open `resources/views/home.blade.php` in code editor
2. Locate the line numbers for desired section
3. Modify text, image paths, or URLs
4. Save file
5. Clear cache: `php artisan optimize:clear`
6. Refresh browser to see changes

---

## Recommendations

### 🚀 High Priority Enhancements

#### 1. **Convert Counters to Dynamic Settings**
**Current State:** Hardcoded values  
**Recommended Solution:** Use Settings model

```php
// In view, replace hardcoded values with:
{{ setting('counters.properties_sold') }}
{{ setting('counters.years_experience') }}
{{ setting('counters.happy_clients') }}
```

**Benefits:**
- Admin can update without code changes
- Easy to maintain
- No developer needed for updates

#### 2. **Make Services Tabs Dynamic**
**Current State:** Hardcoded project tabs  
**Recommended Solution:** Use Project model with categories/tags

```php
// In controller:
$serviceProjects = Project::published()
    ->whereHas('categories', function($q) {
        $q->where('slug', 'services');
    })
    ->take(4)
    ->get();
```

**Benefits:**
- Consistent with other dynamic sections
- Admin-manageable
- Automatic updates

#### 3. **Integrate Instagram API for Gallery**
**Current State:** Static image files  
**Recommended Solution:** Instagram Basic Display API or third-party package

**Benefits:**
- Auto-updates with new posts
- No manual image management
- Always current content

#### 4. **Add Video Management via Settings**
**Current State:** Hardcoded video URLs  
**Recommended Solution:** Settings or dedicated Video model

**Benefits:**
- Easy video swapping
- Support for multiple videos
- No code deployment needed

### 📊 Content Status Summary

| Section | Type | Management Method | Priority |
|---------|------|-------------------|----------|
| Hero | ⚠️ Static | Code changes | 🔴 High - Consider Settings |
| About | ⚠️ Static | Code changes | 🟡 Medium - Consider Page model |
| Counters | ⚠️ Static | Code changes | 🔴 High - Use Settings |
| Video | ⚠️ Static | Code changes | 🟡 Medium - Use Settings |
| Services Tabs | ⚠️ Static | Code changes | 🔴 High - Use Project model |
| Residential Properties | ✅ Dynamic | Admin Panel | ✅ Working |
| Showcase | ⚠️ Static | Code changes | 🟡 Medium - Use Project model |
| Gallery | ⚠️ Static | Code changes | 🟢 Low - Instagram API optional |
| Blog Posts | ✅ Dynamic | Admin Panel | ✅ Working |

### 🔧 Quick Fixes for Common Tasks

#### Change Hero Title:
```blade
<!-- Line 20 in home.blade.php -->
<h1>YOUR NEW TITLE HERE</h1>
```

#### Update Counter Value:
```blade
<!-- Lines 153, 165, 177, 189 in home.blade.php -->
<span class="counter wa-counter">NEW_VALUE</span>
```

#### Change Video:
```blade
<!-- Line 206 in home.blade.php -->
<video src="{{ asset('assets/img/video/YOUR_VIDEO.mp4') }}" ...>
```

---

## Notes

### ⚠️ Important Considerations

1. **Unused Data:** The `$careers` variable is passed to the view but never used. Consider removing from controller or implementing a careers section.

2. **Broken Links:** The showcase section links to `project-details.html` (static HTML) instead of dynamic routes. Should use `route('projects.show', $slug)`.

3. **Fallback Images:** Dynamic sections use `assets/img/random/random (10).png` as fallback when no images uploaded. Ensure this file exists.

4. **Image Optimization:** Consider implementing lazy loading and WebP format for better performance.

5. **SEO:** Add meta tags for title, description, and og:image in layout for better social sharing.

### 📱 Responsive Considerations

- All sections are responsive
- Hero adjusts layout for mobile
- Projects grid becomes single column on small screens
- Blog cards stack vertically on mobile

---

## Related Files

### Views
- `resources/views/layouts/app.blade.php` - Main layout
- `resources/views/projects/index.blade.php` - Projects listing
- `resources/views/projects/show.blade.php` - Single project
- `resources/views/blog/index.blade.php` - Blog listing
- `resources/views/blog/show.blade.php` - Single blog post

### Controllers
- `app/Http/Controllers/Frontend/HomeController.php` - Homepage controller
- `app/Http/Controllers/Frontend/ProjectController.php` - Projects controller
- `app/Http/Controllers/Frontend/BlogController.php` - Blog controller

### Models
- `app/Models/Project.php` - Project model with scopes
- `app/Models/BlogPost.php` - Blog post model with scopes
- `app/Models/Career.php` - Career model (unused in view)

### Admin Resources
- `app/Filament/Resources/ProjectResource.php` - Project management
- `app/Filament/Resources/BlogPostResource.php` - Blog management
- `app/Filament/Resources/MediaLibraryResource.php` - Media management

---

**Last Updated:** October 20, 2025  
**Documented By:** Development Team  
**Version:** 1.0