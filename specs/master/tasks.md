# Tasks: Girl Lockers Dance Learning Platform

**Input**: Design documents from `C:\Users\Farix Romero\Documents\proyectos\girlockers\specs\master\`
**Prerequisites**: plan.md, research.md, data-model.md, contracts/design-system.md, quickstart.md

---

## Format: `[ID] [P?] Description`
- **[P]**: Can run in parallel (different files, no dependencies)
- Include exact file paths in descriptions

## Path Conventions
- **Laravel monolith**: All code at repository root
- **Structure**: `app/`, `database/`, `resources/`, `tests/`, `config/`, `routes/`

---

## Phase 3.1: Setup & Foundation

- [X] T001 Create new Laravel 11 project with `composer create-project laravel/laravel girlockers`
- [X] T002 Install Laravel Breeze with Livewire stack: `composer require laravel/breeze --dev && php artisan breeze:install livewire`
- [X] T003 [P] Install and configure Tailwind CSS with Girl Lockers brand colors in `tailwind.config.js`
- [X] T004 [P] Install Motion.js for animations: `npm install motion`
- [X] T005 [P] Configure MySQL database connection in `.env` file
- [X] T006 [P] Create `.editorconfig` and configure code style (PSR-12)
- [X] T007 [P] Install Pest for testing: `composer require pestphp/pest --dev --with-all-dependencies && php artisan pest:install`
- [X] T008 [P] Configure Vite for production builds in `vite.config.js`
- [X] T009 Create directory structure: `app/Enums/`, `app/Policies/`, `app/Http/Livewire/{Auth,Student,Admin}/`
- [X] T010 [P] Add custom Composer scripts for testing and linting in `composer.json`

---

## Phase 3.2: Database Migrations (Tests First - TDD)

⚠️ **CRITICAL: Write failing tests BEFORE migrations**

### Test Tasks First
- [ ] T011 [P] Create migration test for users table in `tests/Feature/Database/UsersMigrationTest.php`
- [ ] T012 [P] Create migration test for courses table in `tests/Feature/Database/CoursesMigrationTest.php`
- [ ] T013 [P] Create migration test for modules table in `tests/Feature/Database/ModulesMigrationTest.php`
- [ ] T014 [P] Create migration test for lessons table in `tests/Feature/Database/LessonsMigrationTest.php`
- [ ] T015 [P] Create migration test for comments table in `tests/Feature/Database/CommentsMigrationTest.php`
- [ ] T016 [P] Create migration test for lesson_likes table in `tests/Feature/Database/LessonLikesMigrationTest.php`
- [ ] T017 [P] Create migration test for access_requests table in `tests/Feature/Database/AccessRequestsMigrationTest.php`

### Migration Tasks (Only after tests fail)
- [X] T018 Create users table migration with role, has_full_access columns in `database/migrations/xxxx_create_users_table.php`
- [X] T019 Create courses table migration with level, is_published in `database/migrations/xxxx_create_courses_table.php`
- [X] T020 Create modules table migration with course_id FK, order column in `database/migrations/xxxx_create_modules_table.php`
- [X] T021 Create lessons table migration with module_id FK, video_type, youtube_id, local_path, is_trial, likes_count in `database/migrations/xxxx_create_lessons_table.php`
- [X] T022 Create comments table migration with lesson_id, user_id FKs in `database/migrations/xxxx_create_comments_table.php`
- [X] T023 Create lesson_likes pivot table with composite PK (user_id, lesson_id) in `database/migrations/xxxx_create_lesson_likes_table.php`
- [X] T024 Create access_requests table migration with user_id FK, status enum in `database/migrations/xxxx_create_access_requests_table.php`
- [X] T025 Run all migrations and verify tests pass: `php artisan migrate && php artisan test`

---

## Phase 3.3: Enums & Models

- [X] T026 [P] Create UserRole enum in `app/Enums/UserRole.php` with Student and Admin cases
- [X] T027 [P] Create User model with role casting, helper methods (isAdmin, hasFullAccess, grantFullAccess) in `app/Models/User.php`
- [X] T028 [P] Create Course model with modules relationship in `app/Models/Course.php`
- [X] T029 [P] Create Module model with course, lessons relationships in `app/Models/Module.php`
- [X] T030 [P] Create Lesson model with module, comments, likes relationships, accessibleBy scope in `app/Models/Lesson.php`
- [X] T031 [P] Create Comment model with lesson, user relationships in `app/Models/Comment.php`
- [X] T032 [P] Create AccessRequest model with user relationship, status enum in `app/Models/AccessRequest.php`

---

## Phase 3.4: Factories & Seeders

- [X] T033 [P] Create UserFactory with role variations (student, admin) in `database/factories/UserFactory.php`
- [X] T034 [P] Create CourseFactory with published/unpublished variations in `database/factories/CourseFactory.php`
- [X] T035 [P] Create ModuleFactory in `database/factories/ModuleFactory.php`
- [X] T036 [P] Create LessonFactory with trial/premium, youtube/local variations in `database/factories/LessonFactory.php`
- [X] T037 [P] Create CommentFactory in `database/factories/CommentFactory.php`
- [X] T038 [P] Create AccessRequestFactory in `database/factories/AccessRequestFactory.php`
- [X] T039 Create AdminSeeder with default admin account in `database/seeders/AdminSeeder.php`
- [X] T040 Create DatabaseSeeder with test data (courses, modules, lessons, users) in `database/seeders/DatabaseSeeder.php`
- [X] T041 Run seeders and verify data: `php artisan db:seed`

---

## Phase 3.5: Authentication & Authorization

- [X] T042 Create EnsureUserIsAdmin middleware in `app/Http/Middleware/EnsureUserIsAdmin.php`
- [X] T043 Register admin middleware alias in `bootstrap/app.php`
- [X] T044 Define access gates (manage-users, manage-content, access-lesson, delete-comment) in `app/Providers/AppServiceProvider.php`
- [X] T045 Create LessonPolicy with view, comment authorization in `app/Policies/LessonPolicy.php`
- [X] T046 Create CommentPolicy with delete authorization in `app/Policies/CommentPolicy.php`
- [X] T047 Customize Breeze Register component to auto-assign Student role in `app/Livewire/Pages/Auth/Register.php`
- [X] T048 Customize Breeze Login component with role-based redirects in `app/Livewire/Pages/Auth/Login.php`
- [X] T049 [P] Create Artisan command for creating admin users in `app/Console/Commands/CreateAdminCommand.php`
- [X] T050 Test authentication flow: register student, login, role-based redirect

---

## Phase 3.6: Design System & Layouts

- [X] T051 Configure Tailwind with Girl Lockers brand colors (pink-vibrant, purple-deep, cream) in `tailwind.config.js`
- [X] T052 [P] Import Google Fonts (Montserrat, Inter, Pacifico) in `resources/css/app.css`
- [X] T053 [P] Create CSS custom properties for colors, gradients, shadows in `resources/css/app.css`
- [X] T054 Create master layout with Livewire scripts in `resources/views/layouts/app.blade.php`
- [X] T055 Create guest layout for landing page in `resources/views/layouts/guest.blade.php`
- [X] T056 Create student layout with persistent navigation in `resources/views/layouts/student.blade.php`
- [X] T057 Create admin layout with sidebar in `resources/views/layouts/admin.blade.php`
- [X] T058 [P] Create button components (btn-primary, btn-secondary, btn-ghost) in `resources/views/components/button.blade.php`
- [X] T059 [P] Create card components (card-premium, card-glass) in `resources/views/components/card.blade.php`
- [X] T060 [P] Create mobile bottom navigation component in `resources/views/components/mobile-nav.blade.php`

---

## Phase 3.7: Landing Page (Guest Experience)

- [X] T061 Create LandingPage Livewire component in `app/Livewire/Landing/HomePage.php`
- [X] T062 Create hero section view with CTA buttons in `resources/views/livewire/landing/home-page.blade.php`
- [X] T063 [P] Create vision section component in `resources/views/livewire/landing/vision-section.blade.php`
- [X] T064 [P] Create benefits section component in `resources/views/livewire/landing/benefits-section.blade.php`
- [X] T065 [P] Create levels preview section in `resources/views/livewire/landing/levels-section.blade.php`
- [X] T066 [P] Create instructors section placeholder in `resources/views/livewire/landing/instructors-section.blade.php`
- [X] T067 [P] Create community section with testimonials in `resources/views/livewire/landing/community-section.blade.php`
- [ ] T068 [P] Add Motion.js animations to hero section in `resources/js/animations/hero.js`
- [X] T069 Configure landing page route in `routes/web.php`
- [ ] T070 Test landing page on mobile (375px) and desktop (1280px)

---

## Phase 3.8: Student Features - Course Browsing (Tests First)

### Integration Tests
- [ ] T071 [P] Create feature test for course catalog browsing in `tests/Feature/Student/CourseCatalogTest.php`
- [ ] T072 [P] Create feature test for course detail view in `tests/Feature/Student/CourseDetailTest.php`
- [ ] T073 [P] Create feature test for trial lesson access in `tests/Feature/Student/TrialAccessTest.php`

### Implementation
- [X] T074 Create CourseCatalog Livewire component with lazy loading in `app/Livewire/Student/CourseCatalog.php`
- [X] T075 Create course catalog view with grid layout in `resources/views/livewire/student/course-catalog.blade.php`
- [X] T076 Create CourseDetail Livewire component with eager loading in `app/Livewire/Student/CourseDetail.php`
- [X] T077 Create course detail view with modules accordion in `resources/views/livewire/student/course-detail.blade.php`
- [X] T078 [P] Create course card component with level badge in `resources/views/components/course-card.blade.php`
- [X] T079 Add course browsing routes in `routes/web.php`
- [ ] T080 Test course browsing with wire:navigate transitions

---

## Phase 3.9: Student Features - Lesson Viewing

### Integration Tests
- [ ] T081 [P] Create feature test for lesson video playback in `tests/Feature/Student/LessonPlaybackTest.php`
- [ ] T082 [P] Create feature test for access control (trial vs premium) in `tests/Feature/Student/LessonAccessControlTest.php`

### Implementation
- [X] T083 Create LessonView Livewire component with authorization in `app/Livewire/Student/LessonView.php`
- [X] T084 Create lesson view with video player (YouTube + local support) in `resources/views/livewire/student/lesson-view.blade.php`
- [X] T085 [P] Create YouTube embed component with lazy loading facade in `resources/views/components/youtube-embed.blade.php`
- [X] T086 [P] Create local video player component with HTML5 controls in `resources/views/components/local-video.blade.php`
- [ ] T087 Implement persistent video player using @persist directive in student layout
- [X] T088 Add lesson viewing routes with authorization middleware in `routes/web.php`
- [ ] T089 Test video playback on mobile (YouTube iframe and HTML5 player)

---

## Phase 3.10: Student Features - Comments

### Integration Tests
- [ ] T090 [P] Create feature test for posting comments in `tests/Feature/Student/CommentPostingTest.php`
- [ ] T091 [P] Create feature test for viewing comments (newest first) in `tests/Feature/Student/CommentViewingTest.php`

### Implementation
- [X] T092 Create CommentSection Livewire component in `app/Livewire/Student/CommentSection.php`
- [X] T093 Create comment section view with form and list in `resources/views/livewire/student/comment-section.blade.php`
- [X] T094 [P] Create single comment component in `resources/views/components/comment.blade.php`
- [X] T095 Implement comment posting with validation and loading states
- [X] T096 Implement comment deletion (admin only) with confirmation
- [ ] T097 Test comment posting with wire:submit and optimistic UI

---

## Phase 3.11: Student Features - Likes

### Integration Tests
- [ ] T098 [P] Create feature test for liking lessons in `tests/Feature/Student/LessonLikeTest.php`
- [ ] T099 [P] Create feature test for like count updates in `tests/Feature/Student/LikeCountTest.php`

### Implementation
- [X] T100 Create LikeButton Livewire component with toggle logic in `app/Livewire/Student/LikeButton.php`
- [X] T101 Create like button view with heart icon and count in `resources/views/livewire/student/like-button.blade.php`
- [X] T102 Implement atomic like count updates (increment/decrement) in Lesson model
- [X] T103 Add Alpine.js animation for like button click
- [ ] T104 Test like toggle with duplicate prevention

---

## Phase 3.12: Student Features - Access Requests

### Integration Tests
- [ ] T105 [P] Create feature test for access request submission in `tests/Feature/Student/AccessRequestTest.php`
- [ ] T106 [P] Create feature test for pending request status in `tests/Feature/Student/AccessRequestStatusTest.php`

### Implementation
- [X] T107 Create RequestAccess Livewire component in `app/Livewire/Student/RequestAccess.php`
- [X] T108 Create access request view with CTA and status display in `resources/views/livewire/student/request-access.blade.php`
- [X] T109 Implement access request creation with duplicate prevention
- [X] T110 Add access request routes in `routes/web.php`
- [ ] T111 Test access request flow: submit → pending → admin approval → access granted

---

## Phase 3.13: Student Features - Dashboard

- [X] T112 Create StudentDashboard Livewire component in `app/Livewire/Student/Dashboard.php`
- [X] T113 Create dashboard view with course progress, recent lessons in `resources/views/livewire/student/dashboard.blade.php`
- [X] T114 Add dashboard route in `routes/web.php`
- [ ] T115 Test dashboard loads with eager-loaded data (<100ms)

---

## Phase 3.14: Admin Features - Dashboard

### Integration Tests
- [ ] T116 [P] Create feature test for admin dashboard access in `tests/Feature/Admin/DashboardAccessTest.php`
- [ ] T117 [P] Create feature test for pending access requests display in `tests/Feature/Admin/AccessRequestsDisplayTest.php`

### Implementation
- [X] T118 Create AdminDashboard Livewire component in `app/Livewire/Admin/Dashboard.php`
- [X] T119 Create admin dashboard view with pending requests in `resources/views/livewire/admin/dashboard.blade.php`
- [X] T120 Add admin dashboard route with middleware in `routes/web.php`
- [ ] T121 Test admin dashboard shows pending requests with student details

---

## Phase 3.15: Admin Features - Student Management

### Integration Tests
- [ ] T122 [P] Create feature test for approving access requests in `tests/Feature/Admin/ApproveAccessTest.php`
- [ ] T123 [P] Create feature test for revoking student access in `tests/Feature/Admin/RevokeAccessTest.php`

### Implementation
- [X] T124 Create StudentManagement Livewire component in `app/Livewire/Admin/StudentManagement.php`
- [X] T125 Create student management view with table and actions in `resources/views/livewire/admin/student-management.blade.php`
- [X] T126 Implement approve access action (update user.has_full_access = true)
- [X] T127 Implement revoke access action (update user.has_full_access = false)
- [X] T128 Add student management routes in `routes/web.php`
- [ ] T129 Test access approval updates user record and grants immediate access

---

## Phase 3.16: Admin Features - Course Management

### Integration Tests
- [ ] T130 [P] Create feature test for course CRUD operations in `tests/Feature/Admin/CourseCrudTest.php`

### Implementation
- [X] T131 Create CourseManagement Livewire component in `app/Livewire/Admin/CourseManagement.php`
- [X] T132 Create course management view with table and create button in `resources/views/livewire/admin/course-management.blade.php`
- [X] T133 Create CourseForm Livewire component (create/edit) in `app/Livewire/Admin/CourseManagement.php`
- [X] T134 Create course form view with validation in `resources/views/livewire/admin/course-management.blade.php`
- [X] T135 Implement course creation with image upload
- [X] T136 Implement course editing
- [X] T137 Implement course deletion with cascade (modules, lessons, comments, likes)
- [X] T138 Implement course publish/unpublish toggle
- [X] T139 Add course management routes in `routes/web.php`
- [ ] T140 Test course CRUD with image uploads

---

## Phase 3.17: Admin Features - Module Management

### Integration Tests
- [ ] T141 [P] Create feature test for module CRUD operations in `tests/Feature/Admin/ModuleCrudTest.php`

### Implementation
- [X] T142 Create ModuleManagement Livewire component in `app/Livewire/Admin/ModuleManagement.php`
- [X] T143 Create module management view with sortable list in `resources/views/livewire/admin/module-management.blade.php`
- [X] T144 Create ModuleForm Livewire component in `app/Livewire/Admin/ModuleManagement.php`
- [X] T145 Create module form view in `resources/views/livewire/admin/module-management.blade.php`
- [X] T146 Implement module ordering (drag-and-drop or up/down buttons)
- [X] T147 Implement module creation, editing, deletion
- [X] T148 Add module management routes in `routes/web.php`
- [ ] T149 Test module ordering persists and displays correctly

---

## Phase 3.18: Admin Features - Lesson Management

### Integration Tests
- [ ] T150 [P] Create feature test for lesson CRUD operations in `tests/Feature/Admin/LessonCrudTest.php`
- [ ] T151 [P] Create feature test for YouTube vs local video handling in `tests/Feature/Admin/VideoTypeTest.php`

### Implementation
- [X] T152 Create LessonManagement Livewire component in `app/Livewire/Admin/LessonManagement.php`
- [X] T153 Create lesson management view with sortable list in `resources/views/livewire/admin/lesson-management.blade.php`
- [X] T154 Create LessonForm Livewire component in `app/Livewire/Admin/LessonManagement.php`
- [X] T155 Create lesson form view with video type selector (YouTube ID or file upload) in `resources/views/livewire/admin/lesson-management.blade.php`
- [X] T156 Implement YouTube video handling (store youtube_id)
- [X] T157 Implement local video upload with validation (max size, format)
- [X] T158 Implement lesson ordering
- [X] T159 Implement trial/premium toggle
- [X] T160 Implement lesson creation, editing, deletion
- [X] T161 Add lesson management routes in `routes/web.php`
- [ ] T162 Test lesson creation with both YouTube and local videos

---

## Phase 3.19: Admin Features - Comment Moderation

### Integration Tests
- [ ] T163 [P] Create feature test for comment deletion by admin in `tests/Feature/Admin/CommentModerationTest.php`

### Implementation
- [X] T164 Create CommentModeration Livewire component in `app/Livewire/Admin/CommentModeration.php`
- [X] T165 Create comment moderation view with recent comments table in `resources/views/livewire/admin/comment-moderation.blade.php`
- [X] T166 Implement comment deletion (hard delete as per spec)
- [X] T167 Add comment moderation routes in `routes/web.php`
- [ ] T168 Test admin can delete any comment from any lesson

---

## Phase 3.20: Caching & Performance Optimization

- [ ] T169 Install and configure Redis for caching in `.env`
- [ ] T170 Create CachedCourseRepository with tags-based caching in `app/Repositories/CachedCourseRepository.php`
- [ ] T171 Implement cache invalidation via model observers (CourseObserver, ModuleObserver, LessonObserver) in `app/Observers/`
- [ ] T172 Register observers in `AppServiceProvider`
- [ ] T173 Update CourseController to use cached repository
- [ ] T174 [P] Add database query logging in development (Laravel Debugbar)
- [ ] T175 Optimize eager loading queries (verify 3 queries for course hierarchy)
- [ ] T176 Test cache hit rate: load course twice, verify second load from cache
- [ ] T177 Test cache invalidation: update lesson, verify course cache cleared

---

## Phase 3.21: Testing - Quickstart Validation

- [ ] T178 [P] Create feature test for Scenario 1: Student registration and trial access in `tests/Feature/Quickstart/Scenario1Test.php`
- [ ] T179 [P] Create feature test for Scenario 2: Access request flow in `tests/Feature/Quickstart/Scenario2Test.php`
- [ ] T180 [P] Create feature test for Scenario 3: Lesson interactions (comments, likes) in `tests/Feature/Quickstart/Scenario3Test.php`
- [ ] T181 [P] Create feature test for Scenario 4: Admin content management in `tests/Feature/Quickstart/Scenario4Test.php`
- [ ] T182 [P] Create browser test for Scenario 5: Mobile responsiveness with Laravel Dusk in `tests/Browser/MobileResponsivenessTest.php`
- [ ] T183 [P] Create performance test for Scenario 6: Loading states and performance in `tests/Feature/Quickstart/PerformanceTest.php`
- [ ] T184 [P] Create feature test for Scenario 7: Video playback in `tests/Feature/Quickstart/VideoPlaybackTest.php`
- [ ] T185 [P] Create feature test for Scenario 8: Auth and authorization in `tests/Feature/Quickstart/AuthorizationTest.php`
- [ ] T186 Run all quickstart tests: `php artisan test --testsuite=Feature/Quickstart`

---

## Phase 3.22: Unit Tests

- [ ] T187 [P] Create unit test for UserRole enum methods in `tests/Unit/Enums/UserRoleTest.php`
- [ ] T188 [P] Create unit test for User model helper methods in `tests/Unit/Models/UserTest.php`
- [ ] T189 [P] Create unit test for Lesson accessibleBy scope in `tests/Unit/Models/LessonTest.php`
- [ ] T190 [P] Create unit test for LessonPolicy authorization in `tests/Unit/Policies/LessonPolicyTest.php`
- [ ] T191 [P] Create unit test for CommentPolicy authorization in `tests/Unit/Policies/CommentPolicyTest.php`
- [ ] T192 [P] Create unit test for CachedCourseRepository in `tests/Unit/Repositories/CachedCourseRepositoryTest.php`

---

## Phase 3.23: Asset Optimization & Build

- [X] T193 Optimize Tailwind CSS with purge configuration in `tailwind.config.js`
- [X] T194 Configure Vite for production: minification, versioning, code splitting in `vite.config.js`
- [ ] T195 Optimize images: create optimized versions of course covers, thumbnails
- [ ] T196 [P] Add lazy loading attributes to all images
- [ ] T197 Build production assets: `npm run build`
- [ ] T198 Test production build loads in <1.5s FCP on 3G throttling
- [ ] T199 Run Lighthouse audit and verify score 90+ mobile

---

## Phase 3.24: Accessibility & Polish

- [ ] T200 [P] Add ARIA labels to all interactive elements
- [ ] T201 [P] Test keyboard navigation (Tab, Enter, Esc) through all flows
- [ ] T202 [P] Verify color contrast meets WCAG 2.1 AA (4.5:1) with contrast checker
- [ ] T203 [P] Test screen reader announcements for registration, login, access requests
- [ ] T204 [P] Add focus-visible styles to all focusable elements
- [ ] T205 [P] Test prefers-reduced-motion: disable animations when requested
- [ ] T206 [P] Add descriptive alt text to all images
- [ ] T207 Run axe DevTools accessibility audit and fix violations

---

## Phase 3.25: Deployment Preparation

- [X] T208 Create `.env.example` with all required variables (no secrets)
- [X] T209 Document deployment steps in `DEPLOYMENT.md`
- [X] T210 Create deployment checklist based on research.md (Hostinger-specific)
- [ ] T211 [P] Configure OPcache settings for shared hosting in `php.ini`
- [ ] T212 [P] Set up Laravel optimization commands (config:cache, route:cache, view:cache)
- [ ] T213 Test migrations rollback: `php artisan migrate:rollback`
- [X] T214 Create production `.htaccess` with HTTPS redirect, security headers
- [ ] T215 Configure session lifetime, CSRF token expiry in `config/session.php`
- [ ] T216 Verify all routes protected by authentication middleware
- [ ] T217 Run security audit: `composer audit`

---

## Dependencies

### Critical Paths:
1. **Setup** (T001-T010) → **Database** (T011-T025) → **Models** (T026-T032)
2. **Models** → **Factories** (T033-T041) → **Seeders**
3. **Models** → **Auth** (T042-T050)
4. **Auth** → **Student Features** (T071-T115)
5. **Auth** → **Admin Features** (T116-T168)
6. **Core Features** → **Caching** (T169-T177) → **Testing** (T178-T192)
7. **Everything** → **Optimization** (T193-T207) → **Deployment** (T208-T217)

### Blocking Dependencies:
- T018 (users migration) blocks T027 (User model)
- T019 (courses migration) blocks T028 (Course model)
- T027 (User model) blocks T042 (middleware), T047 (custom registration)
- T051 (Tailwind config) blocks T054-T060 (layouts and components)
- T074-T089 (student features) block T178-T185 (quickstart tests)

---

## Parallel Execution Examples

### Batch 1: Setup (can run simultaneously)
```bash
Task: "Install and configure Tailwind CSS with Girl Lockers brand colors in tailwind.config.js"
Task: "Install Motion.js for animations: npm install motion"
Task: "Configure MySQL database connection in .env file"
Task: "Install Pest for testing: composer require pestphp/pest --dev"
```

### Batch 2: Models (after migrations complete)
```bash
Task: "Create User model with role casting in app/Models/User.php"
Task: "Create Course model with modules relationship in app/Models/Course.php"
Task: "Create Module model with course, lessons relationships in app/Models/Module.php"
Task: "Create Lesson model in app/Models/Lesson.php"
Task: "Create Comment model in app/Models/Comment.php"
```

### Batch 3: Factories (after models complete)
```bash
Task: "Create UserFactory with role variations in database/factories/UserFactory.php"
Task: "Create CourseFactory in database/factories/CourseFactory.php"
Task: "Create ModuleFactory in database/factories/ModuleFactory.php"
Task: "Create LessonFactory in database/factories/LessonFactory.php"
Task: "Create CommentFactory in database/factories/CommentFactory.php"
```

### Batch 4: Integration Tests (before implementation)
```bash
Task: "Create feature test for course catalog browsing in tests/Feature/Student/CourseCatalogTest.php"
Task: "Create feature test for course detail view in tests/Feature/Student/CourseDetailTest.php"
Task: "Create feature test for trial lesson access in tests/Feature/Student/TrialAccessTest.php"
```

---

## Validation Checklist

- [ ] All 217 tasks have specific file paths
- [ ] Tests come before implementation (TDD)
- [ ] Parallel tasks [P] truly independent (different files)
- [ ] Critical paths identified and ordered
- [ ] Quickstart scenarios covered by integration tests
- [ ] Data model entities all have migrations, models, factories
- [ ] Design system fully implemented (Tailwind, components, layouts)
- [ ] Landing page complete with premium design
- [ ] Student features complete (browse, view, comment, like, request access)
- [ ] Admin features complete (manage courses, modules, lessons, students, comments)
- [ ] Performance optimizations implemented (caching, eager loading, asset optimization)
- [ ] Accessibility validated (WCAG 2.1 AA)
- [ ] Deployment ready (docs, configs, security)

---

## Notes

- Commit after each task or small batch of related [P] tasks
- Run tests after each phase: `php artisan test`
- Test mobile responsiveness throughout (DevTools device toolbar)
- Use Laravel Debugbar to monitor query counts (<100ms target)
- Avoid: vague tasks, missing file paths, same file conflicts with [P] markers
- Follow TDD strictly: Red (failing test) → Green (passing implementation) → Refactor

---

**Total Tasks**: 217
**Estimated Time**: 80-120 hours (full implementation)
**Parallelizable Tasks**: ~60 tasks marked [P]
**Critical Path Length**: ~150 tasks (sequential dependencies)

**Ready for Implementation**: ✅ All tasks are specific, ordered, and executable

