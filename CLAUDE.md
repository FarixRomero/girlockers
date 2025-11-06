# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**GirlsLockers** is a dance learning platform for the female Locking community, founded by @tati.cerna from Peru. The platform provides classes, community features, competitions, and video content for women to learn, practice, and shine in the art of Locking dance.

- **Framework**: Laravel 12.x
- **UI Stack**: Livewire 3.x + Alpine.js + Tailwind CSS 4.x
- **Database**: MySQL 8.0+
- **PHP Version**: 8.2+
- **Node.js**: 18+

## Architecture

### Stack & Patterns

- **Full-Stack Approach**: Livewire components handle both frontend and backend logic
- **No API Layer**: Uses Livewire's reactive components instead of REST/GraphQL endpoints (except admin lesson management)
- **Blade + Volt**: Templates use both traditional Blade and Livewire Volt (functional components)
- **File-based Routing**: Routes defined in `routes/web.php` pointing to Livewire components

### Project Structure

```
app/
├── Livewire/
│   ├── Admin/          # Admin dashboard components (course/lesson/user management)
│   ├── Student/        # Student-facing components (catalog, video player, history)
│   └── Landing/        # Public landing page
├── Models/             # Eloquent models (User, Course, Module, Lesson, Instructor, Tag, Comment)
├── Policies/           # Authorization policies for lesson access control
├── Http/Controllers/
│   ├── Api/            # REST API for admin lesson CRUD operations
│   └── VideoStreamController.php  # Video streaming with range requests
resources/
├── views/
│   ├── livewire/       # Livewire component views
│   ├── components/     # Reusable Blade components
│   └── layouts/        # Base layouts (app, guest, admin)
database/
├── migrations/         # Database schema migrations
├── factories/          # Model factories for testing
└── seeders/           # Database seeders
```

### Content Hierarchy

The platform organizes dance content in a hierarchical structure:

**Course → Module → Lesson**

- **Course**: Top-level container (e.g., "Hip Hop Básico", "Heels Principiantes")
  - Has title, description, level (Principiante/Intermedio/Avanzado), instructor, image
  - Can be published/unpublished

- **Module**: Sections within a course (e.g., "Fundamentos", "Coreografía Básica")
  - Ordered sequence of related content
  - Has title, description, order number

- **Lesson**: Individual video classes
  - Video sources: YouTube, Bunny.net CDN, or local storage
  - Can be marked as "trial" (free preview) or premium (requires full access)
  - Supports likes, comments, and view tracking
  - Has instructor, tags, thumbnail, duration, order

### Access Control System

**Two-tier user system:**

1. **Role-based**: `student` or `admin` (UserRole enum)
2. **Access-based**: `has_full_access` boolean flag

**Access Rules:**
- All authenticated users can browse ALL lessons (catalog visibility)
- Trial lessons (`is_trial = true`): Playable by all authenticated users
- Premium lessons (`is_trial = false`): Only playable by users with `has_full_access = true`
- Admins bypass all restrictions
- Lock icon shown on premium lessons for users without access
- Students can request access via `AccessRequest` model (status: pending/approved/rejected)

**Implementation:**
- `Lesson::isAccessibleBy(User $user)` - Check if user can play lesson
- `LessonPolicy` - Authorization gates for lesson actions
- Access granted/revoked by admins via Student Management panel

### Video Handling

**Three video types supported:**

1. **YouTube** (`video_type = 'youtube'`)
   - Store `youtube_id` field
   - Embed using YouTube iframe API

2. **Bunny.net CDN** (`video_type = 'bunny'`)
   - Store `bunny_video_id` field
   - Stream via Bunny.net video player
   - Upload handled by `BunnyUploadController`

3. **Local Storage** (`video_type = 'local'`)
   - Store in `storage/app/public/lessons/videos/`
   - Stream via `VideoStreamController` with HTTP range support
   - Field: `video_path`

**Thumbnails:** Stored in `storage/app/public/lessons/thumbnails/`

## Development Commands

### Quick Start
```bash
# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate
php artisan db:seed  # Optional: sample data

# Create storage symlink
php artisan storage:link

# Start development (concurrent servers + queue + logs + vite)
composer dev
```

### Individual Services
```bash
# Application server
php artisan serve                      # http://localhost:8000

# Frontend asset compilation
npm run dev                            # Development with HMR
npm run build                          # Production build

# Queue worker (for notifications)
php artisan queue:listen --tries=1

# Real-time logs
php artisan pail --timeout=0
```

### Testing
```bash
# Run all tests
composer test
# OR
php artisan test

# Run specific test file
php artisan test tests/Feature/LessonAccessTest.php

# Run tests with coverage
php artisan test --coverage
```

### Code Quality
```bash
# Format code with Laravel Pint
./vendor/bin/pint

# Check formatting without changes
./vendor/bin/pint --test
```

### Database Operations
```bash
# Fresh migration with seeding
php artisan migrate:fresh --seed

# Rollback last migration
php artisan migrate:rollback

# Check migration status
php artisan migrate:status

# Create new migration
php artisan make:migration create_table_name

# Create model with migration
php artisan make:model ModelName -m
```

### Cache Management
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Production optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Creating Admin User
```bash
php artisan tinker

# In Tinker:
$user = new App\Models\User();
$user->name = 'Admin Name';
$user->email = 'admin@girlslockers.com';
$user->password = bcrypt('secure-password');
$user->role = 'admin';
$user->has_full_access = true;
$user->save();
```

## Key Technical Details

### Livewire Components

**Component Types:**
- **Full-page components**: Handle routing, layout, data loading
  - Example: `LessonView.php` - Video player page with comments, likes, history
- **Nested components**: Interactive widgets within pages
  - Example: `CommentSection.php`, `LikeButton.php`, `GlobalSearch.php`
- **Forms**: Reusable form components
  - Example: `LoginForm.php`

**Common Patterns:**
```php
// Real-time validation
use Livewire\Attributes\Validate;
#[Validate('required|min:3')]
public string $name = '';

// Event listeners
use Livewire\Attributes\On;
#[On('lesson-updated')]
public function refreshLesson() { ... }

// Query string binding
use Livewire\Attributes\Url;
#[Url]
public string $search = '';
```

### Authorization

**Gates & Policies:**
- `LessonPolicy` handles `view`, `update`, `delete` actions
- Middleware: `auth`, `admin` (custom middleware at `app/Http/Middleware/EnsureUserIsAdmin.php`)
- Use in Livewire: `$this->authorize('view', $lesson)`
- Use in Blade: `@can('update', $lesson)`

### Database Relationships

**Important relationships to understand:**
```php
User->likedLessons()          // Many-to-many via lesson_likes
User->viewedLessons()         // Many-to-many via lesson_views (with timestamp)
User->comments()              // One-to-many
User->accessRequests()        // One-to-many

Course->modules()             // One-to-many (ordered by 'order')
Module->lessons()             // One-to-many (ordered by 'order')
Lesson->instructor()          // Many-to-one
Lesson->tags()                // Many-to-many via lesson_tag
Lesson->comments()            // One-to-many
Lesson->likes()               // Many-to-many with users
```

### File Uploads

**Storage locations:**
- Course images: `storage/app/public/courses/`
- Lesson videos: `storage/app/public/lessons/videos/`
- Lesson thumbnails: `storage/app/public/lessons/thumbnails/`

**Access in Blade:**
```blade
<img src="{{ asset('storage/' . $course->image) }}">
```

**URL Attributes:**
Models have computed attributes for URLs:
- `$course->image_url`
- `$lesson->thumbnail_url`

### Notifications

Custom notification system (not Laravel's default):
- Model: `Notification` with `user_id`, `type`, `title`, `message`, `read_at`
- Scopes: `unread()`, `ofType($type)`
- Types: 'info', 'success', 'warning', 'error'
- Displayed in: `Student\Notifications.php` component

### Video Streaming

- `VideoStreamController@stream` handles byte-range requests for local videos
- Supports seeking/scrubbing in video player
- Access control enforced before streaming

## Testing Strategy

**Test Types:**
- Feature tests: End-to-end user flows
- Unit tests: Model methods, business logic

**Important test coverage:**
- Lesson access control (trial vs premium)
- User authorization (admin vs student)
- Video streaming with range requests
- Comment moderation
- Like/unlike functionality

## Deployment Notes

- **No compilation needed after changes**: Livewire is reactive, PHP changes reflect immediately in development
- **Asset building**: Only needed when changing JS/CSS (`npm run build`)
- **Shared hosting compatible**: Designed for deployment on Hostinger/cPanel
- See `DEPLOYMENT.md` for detailed production deployment steps

## Important Context

**Business Rules:**
- All lessons visible in catalog to authenticated users (discovery)
- Playback gated by `is_trial` flag and `has_full_access` permission
- Comments visible to all, moderated by admins
- Likes require authentication
- View history tracked per user for watch history feature
- Lesson order maintained via `order` column (reorderable by admins)

**Dance Platform Specific:**
When working with lesson content, remember this is for:
- Dance styles: Locking, Hip Hop, Heels, House, Dancehall, etc.
- Target audience: Women in the dance community
- Content types: Technique tutorials, choreography, warm-ups, combos
- Metadata: Tags for moves, difficulty levels, instructor profiles
