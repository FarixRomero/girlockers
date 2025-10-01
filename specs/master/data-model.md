# Data Model: Girl Lockers Dance Learning Platform

**Date**: 2025-09-30
**Database**: MySQL 8.0+
**ORM**: Laravel Eloquent

---

## Entity Relationship Diagram

```
┌──────────────┐       ┌──────────────┐       ┌──────────────┐       ┌──────────────┐
│    User      │       │   Course     │       │   Module     │       │   Lesson     │
├──────────────┤       ├──────────────┤       ├──────────────┤       ├──────────────┤
│ id           │       │ id           │◄──────│ course_id    │◄──────│ module_id    │
│ name         │       │ title        │       │ name         │       │ title        │
│ email        │       │ description  │       │ order        │       │ description  │
│ password     │       │ level        │       │ created_at   │       │ video_type   │
│ role         │       │ image        │       │ updated_at   │       │ youtube_id   │
│ has_full...  │       │ is_published │       └──────────────┘       │ local_path   │
│ access_gr... │       │ created_at   │                              │ thumbnail    │
│ created_at   │       │ updated_at   │                              │ is_trial     │
│ updated_at   │       └──────────────┘                              │ order        │
└──────┬───────┘                                                     │ likes_count  │
       │                                                              │ created_at   │
       │                                                              │ updated_at   │
       │                                                              └──────┬───────┘
       │                                                                     │
       │       ┌──────────────┐                                            │
       └──────►│ AccessRequest│                                            │
       │       ├──────────────┤                                            │
       │       │ id           │                                            │
       │       │ user_id      │                                            │
       │       │ status       │                                            │
       │       │ created_at   │                                            │
       │       │ updated_at   │                                            │
       │       └──────────────┘                                            │
       │                                                                    │
       │       ┌──────────────┐                                            │
       └──────►│   Comment    │◄───────────────────────────────────────────┘
       │       ├──────────────┤
       │       │ id           │
       │       │ lesson_id    │
       │       │ user_id      │
       │       │ content      │
       │       │ created_at   │
       │       │ updated_at   │
       │       └──────────────┘
       │
       │       ┌──────────────┐
       └──────►│ LessonLike   │◄───────────────────────────────────────────┐
               ├──────────────┤                                            │
               │ user_id      │                                            │
               │ lesson_id    │────────────────────────────────────────────┘
               │ created_at   │
               └──────────────┘
```

---

## 1. Users Table

**Purpose**: Store user accounts with role-based access control

**Table**: `users`

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| `name` | VARCHAR(255) | NOT NULL | User's full name |
| `email` | VARCHAR(255) | NOT NULL, UNIQUE | Email address (login) |
| `email_verified_at` | TIMESTAMP | NULLABLE | Email verification timestamp |
| `password` | VARCHAR(255) | NOT NULL | Hashed password (bcrypt) |
| `role` | VARCHAR(50) | NOT NULL, DEFAULT 'student' | User role (enum) |
| `has_full_access` | BOOLEAN | NOT NULL, DEFAULT FALSE | Admin approval flag |
| `access_granted_at` | TIMESTAMP | NULLABLE | Timestamp of access grant |
| `remember_token` | VARCHAR(100) | NULLABLE | Remember me token |
| `created_at` | TIMESTAMP | NOT NULL | Registration timestamp |
| `updated_at` | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
```sql
CREATE INDEX idx_email ON users(email);
CREATE INDEX idx_role ON users(role);
CREATE INDEX idx_has_full_access ON users(has_full_access);
```

**Validation Rules**:
- `email`: Valid email format, unique
- `password`: Minimum 8 characters (Laravel default)
- `role`: Must be 'student' or 'admin'
- `name`: Required, max 255 characters

**Business Rules**:
- New registrations auto-assigned `role = 'student'`
- New students start with `has_full_access = false`
- Only admins can modify `has_full_access` flag
- Admin accounts created manually (seeder/command)

**Migration**:
```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->string('role', 50)->default('student');
    $table->boolean('has_full_access')->default(false);
    $table->timestamp('access_granted_at')->nullable();
    $table->rememberToken();
    $table->timestamps();

    $table->index('role');
    $table->index('has_full_access');
});
```

---

## 2. Courses Table

**Purpose**: Top-level learning units representing complete dance courses

**Table**: `courses`

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| `title` | VARCHAR(255) | NOT NULL | Course name |
| `description` | TEXT | NOT NULL | Course overview |
| `level` | ENUM | NOT NULL | Difficulty level |
| `image` | VARCHAR(500) | NULLABLE | Cover image path/URL |
| `is_published` | BOOLEAN | NOT NULL, DEFAULT FALSE | Visibility flag |
| `created_at` | TIMESTAMP | NOT NULL | Creation timestamp |
| `updated_at` | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
```sql
CREATE INDEX idx_is_published ON courses(is_published);
CREATE INDEX idx_published_level ON courses(is_published, level);
```

**Validation Rules**:
- `title`: Required, max 255 characters, unique
- `description`: Required, max 5000 characters
- `level`: Must be one of: 'beginner', 'intermediate', 'advanced'
- `image`: Optional, valid URL or file path

**Business Rules**:
- Only published courses visible to students (`is_published = true`)
- Admins see all courses regardless of publish status
- Deleting a course cascades to modules → lessons → comments/likes

**Migration**:
```php
Schema::create('courses', function (Blueprint $table) {
    $table->id();
    $table->string('title')->unique();
    $table->text('description');
    $table->enum('level', ['beginner', 'intermediate', 'advanced']);
    $table->string('image', 500)->nullable();
    $table->boolean('is_published')->default(false);
    $table->timestamps();

    $table->index('is_published');
    $table->index(['is_published', 'level']);
});
```

---

## 3. Modules Table

**Purpose**: Organize lessons within courses in sequential order

**Table**: `modules`

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| `course_id` | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Parent course |
| `name` | VARCHAR(255) | NOT NULL | Module title |
| `order` | INTEGER | NOT NULL, DEFAULT 0 | Display order |
| `created_at` | TIMESTAMP | NOT NULL | Creation timestamp |
| `updated_at` | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
```sql
CREATE INDEX idx_course_order ON modules(course_id, order);
```

**Validation Rules**:
- `name`: Required, max 255 characters
- `order`: Integer, >= 0
- `course_id`: Must reference existing course

**Business Rules**:
- Modules within a course must have unique order values
- Order determines display sequence (ASC)
- Deleting a module cascades to lessons → comments/likes

**Migration**:
```php
Schema::create('modules', function (Blueprint $table) {
    $table->id();
    $table->foreignId('course_id')->constrained()->onDelete('cascade');
    $table->string('name');
    $table->integer('order')->default(0);
    $table->timestamps();

    $table->index(['course_id', 'order']);
});
```

---

## 4. Lessons Table

**Purpose**: Individual video lessons with access control

**Table**: `lessons`

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| `module_id` | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Parent module |
| `title` | VARCHAR(255) | NOT NULL | Lesson title |
| `description` | TEXT | NULLABLE | Lesson overview |
| `video_type` | ENUM | NOT NULL | Video source type |
| `youtube_id` | VARCHAR(20) | NULLABLE | YouTube video ID |
| `local_path` | VARCHAR(500) | NULLABLE | Local video file path |
| `thumbnail` | VARCHAR(500) | NULLABLE | Thumbnail image |
| `is_trial` | BOOLEAN | NOT NULL, DEFAULT FALSE | Free access flag |
| `order` | INTEGER | NOT NULL, DEFAULT 0 | Display order |
| `likes_count` | INTEGER | NOT NULL, DEFAULT 0 | Denormalized like count |
| `created_at` | TIMESTAMP | NOT NULL | Creation timestamp |
| `updated_at` | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
```sql
CREATE INDEX idx_module_order ON lessons(module_id, order);
CREATE INDEX idx_module_trial ON lessons(module_id, is_trial);
CREATE INDEX idx_is_trial ON lessons(is_trial);
```

**Validation Rules**:
- `title`: Required, max 255 characters
- `video_type`: Must be 'youtube' or 'local'
- `youtube_id`: Required if `video_type = 'youtube'`, max 20 chars
- `local_path`: Required if `video_type = 'local'`, valid path
- `order`: Integer, >= 0
- **Constraint**: Exactly one of `youtube_id` or `local_path` populated

**Business Rules**:
- Trial lessons (`is_trial = true`) accessible to all registered users
- Non-trial lessons require `has_full_access = true`
- Admins can access all lessons regardless of trial status
- Deleting a lesson cascades to comments and likes
- `likes_count` updated atomically (no manual recalculation)

**Migration**:
```php
Schema::create('lessons', function (Blueprint $table) {
    $table->id();
    $table->foreignId('module_id')->constrained()->onDelete('cascade');
    $table->string('title');
    $table->text('description')->nullable();
    $table->enum('video_type', ['youtube', 'local']);
    $table->string('youtube_id', 20)->nullable();
    $table->string('local_path', 500)->nullable();
    $table->string('thumbnail', 500)->nullable();
    $table->boolean('is_trial')->default(false);
    $table->integer('order')->default(0);
    $table->integer('likes_count')->default(0);
    $table->timestamps();

    $table->index(['module_id', 'order']);
    $table->index(['module_id', 'is_trial']);
    $table->index('is_trial');

    // Constraint: exactly one video source
    $table->check(
        '(video_type = "youtube" AND youtube_id IS NOT NULL AND local_path IS NULL) OR ' .
        '(video_type = "local" AND local_path IS NOT NULL AND youtube_id IS NULL)'
    );
});
```

---

## 5. Comments Table

**Purpose**: Student feedback and discussion on lessons

**Table**: `comments`

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| `lesson_id` | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Parent lesson |
| `user_id` | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Comment author |
| `content` | TEXT | NOT NULL | Comment text |
| `created_at` | TIMESTAMP | NOT NULL | Creation timestamp |
| `updated_at` | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
```sql
CREATE INDEX idx_lesson_created ON comments(lesson_id, created_at DESC);
CREATE INDEX idx_user_id ON comments(user_id);
```

**Validation Rules**:
- `content`: Required, min 1 character, max 5000 characters
- `lesson_id`: Must reference existing lesson
- `user_id`: Must reference existing user

**Business Rules**:
- Only students with access to lesson can comment
- Comments displayed newest-first (`ORDER BY created_at DESC`)
- Admins can delete any comment (hard delete)
- Users cannot edit comments after posting
- Deleting user deletes their comments (cascade)

**Migration**:
```php
Schema::create('comments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->text('content');
    $table->timestamps();

    $table->index(['lesson_id', 'created_at']);
    $table->index('user_id');
});
```

---

## 6. Lesson Likes (Pivot Table)

**Purpose**: Track which users liked which lessons

**Table**: `lesson_likes`

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `user_id` | BIGINT UNSIGNED | PRIMARY KEY (composite), FOREIGN KEY | User who liked |
| `lesson_id` | BIGINT UNSIGNED | PRIMARY KEY (composite), FOREIGN KEY | Lesson liked |
| `created_at` | TIMESTAMP | NOT NULL | Like timestamp |

**Indexes**:
```sql
PRIMARY KEY (user_id, lesson_id)
CREATE INDEX idx_lesson_id ON lesson_likes(lesson_id);
```

**Validation Rules**:
- Composite primary key prevents duplicate likes
- Both foreign keys must reference existing records

**Business Rules**:
- One like per user per lesson (enforced by PK)
- Only students with access to lesson can like
- Toggling like updates `lessons.likes_count` atomically
- Deleting user removes their likes (cascade)
- Deleting lesson removes all likes (cascade)

**Migration**:
```php
Schema::create('lesson_likes', function (Blueprint $table) {
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
    $table->timestamp('created_at')->useCurrent();

    $table->primary(['user_id', 'lesson_id']);
    $table->index('lesson_id');
});
```

---

## 7. Access Requests Table

**Purpose**: Track student requests for full platform access

**Table**: `access_requests`

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| `user_id` | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Requesting student |
| `status` | ENUM | NOT NULL, DEFAULT 'pending' | Request status |
| `admin_notes` | TEXT | NULLABLE | Admin comments |
| `processed_at` | TIMESTAMP | NULLABLE | Decision timestamp |
| `created_at` | TIMESTAMP | NOT NULL | Request timestamp |
| `updated_at` | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
```sql
CREATE INDEX idx_user_status ON access_requests(user_id, status);
CREATE INDEX idx_status_created ON access_requests(status, created_at DESC);
```

**Validation Rules**:
- `status`: Must be 'pending', 'approved', or 'rejected'
- `user_id`: Must reference existing user with `role = 'student'`

**Business Rules**:
- Students can have multiple requests (e.g., if rejected and re-apply)
- Latest request per user determines current status
- Admin approves by updating `status = 'approved'` AND setting `users.has_full_access = true`
- Admins see pending requests in descending creation order
- Notifications generated when status changes

**Migration**:
```php
Schema::create('access_requests', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
    $table->text('admin_notes')->nullable();
    $table->timestamp('processed_at')->nullable();
    $table->timestamps();

    $table->index(['user_id', 'status']);
    $table->index(['status', 'created_at']);
});
```

---

## Relationships

### Eloquent Relationships

**User Model**:
```php
class User extends Authenticatable
{
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likedLessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_likes')->withTimestamps();
    }

    public function accessRequests()
    {
        return $this->hasMany(AccessRequest::class);
    }
}
```

**Course Model**:
```php
class Course extends Model
{
    public function modules()
    {
        return $this->hasMany(Module::class)->orderBy('order');
    }

    public function lessons()
    {
        return $this->hasManyThrough(Lesson::class, Module::class);
    }
}
```

**Module Model**:
```php
class Module extends Model
{
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }
}
```

**Lesson Model**:
```php
class Lesson extends Model
{
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'lesson_likes')->withTimestamps();
    }
}
```

**Comment Model**:
```php
class Comment extends Model
{
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

**AccessRequest Model**:
```php
class AccessRequest extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

---

## State Transitions

### Access Request Lifecycle

```
┌──────────┐
│ pending  │ (Initial state: Student submits request)
└────┬─────┘
     │
     ├─────► approved  (Admin grants access + sets user.has_full_access = true)
     │
     └─────► rejected  (Admin denies request)
```

**State Constraints**:
- Only `pending` requests can transition to `approved` or `rejected`
- `approved`/`rejected` requests are immutable (new request if needed)
- Approval triggers: `users.has_full_access = true` + `users.access_granted_at = NOW()`

---

## Data Integrity Rules

1. **Referential Integrity**:
   - All foreign keys use `ON DELETE CASCADE`
   - Deleting course → deletes modules → deletes lessons → deletes comments/likes

2. **Unique Constraints**:
   - `users.email`: Unique
   - `courses.title`: Unique
   - `(user_id, lesson_id)` in `lesson_likes`: Unique (composite PK)

3. **Check Constraints**:
   - `lessons.video_type`: Exactly one of `youtube_id` or `local_path` populated
   - `users.role`: Must be 'student' or 'admin'
   - `courses.level`: Must be 'beginner', 'intermediate', or 'advanced'

4. **Denormalized Fields**:
   - `lessons.likes_count`: Updated atomically via `increment()`/`decrement()`
   - Never manually calculated (risk of race conditions)

---

## Performance Considerations

1. **Indexes**:
   - Foreign keys indexed (auto in MySQL for FK constraints)
   - Composite indexes on `(course_id, order)` and `(module_id, order)` for sorted queries
   - `is_trial` indexed for access control filtering

2. **Eager Loading**:
   - Always use `with()` for relationships (prevent N+1)
   - Example: `Course::with('modules.lessons')->get();` (3 queries instead of 100+)

3. **Caching**:
   - Course structure cached for 1-2 hours (read-heavy)
   - Cache invalidation via model observers on save/delete

4. **Denormalization**:
   - `likes_count` avoids `COUNT()` queries on every lesson display
   - Update atomically: `$lesson->increment('likes_count');`

---

## Data Migration Strategy

**Order of Migration Execution**:

1. `users` (independent)
2. `courses` (independent)
3. `modules` (depends on courses)
4. `lessons` (depends on modules)
5. `comments` (depends on users, lessons)
6. `lesson_likes` (depends on users, lessons)
7. `access_requests` (depends on users)

**Rollback Strategy**:
- All migrations use `onDelete('cascade')` for FK constraints
- Drop tables in reverse order to avoid FK violations

---

## Seed Data Requirements

**Minimum Seed Data for Testing**:

1. **Admin User**: 1 admin account
2. **Student Users**: 5-10 student accounts (mix of full/trial access)
3. **Courses**: 2-3 courses (beginner/intermediate levels)
4. **Modules**: 2-3 modules per course
5. **Lessons**: 5-10 lessons per module (mix of trial/premium)
6. **Comments**: 10-20 comments across various lessons
7. **Likes**: 20-50 likes distributed across lessons
8. **Access Requests**: 2-3 pending requests

**Factory Definitions**: Required for all models to support testing

---

**Data Model Complete**: Ready for contract generation and implementation

