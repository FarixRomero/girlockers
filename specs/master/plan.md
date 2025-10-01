# Implementation Plan: Girl Lockers Dance Learning Platform

**Branch**: `master` | **Date**: 2025-09-30 | **Spec**: [spec.md](./spec.md)
**Input**: Feature specification from `C:\Users\Farix Romero\Documents\proyectos\girlockers\specs\master\spec.md`

## Execution Flow (/plan command scope)
```
✅ 1. Load feature spec from Input path
✅ 2. Fill Technical Context (scan for NEEDS CLARIFICATION)
✅ 3. Fill the Constitution Check section based on constitution
✅ 4. Evaluate Constitution Check section below
✅ 5. Execute Phase 0 → research.md
✅ 6. Execute Phase 1 → contracts, data-model.md, quickstart.md, design-system.md
✅ 7. Re-evaluate Constitution Check section
✅ 8. Plan Phase 2 → Describe task generation approach
✅ 9. STOP - Ready for /tasks command
```

**IMPORTANT**: The /plan command STOPS at step 8. Phases 2-4 are executed by other commands:
- Phase 2: /tasks command creates tasks.md
- Phase 3-4: Implementation execution (manual or via tools)

## Summary

Girl Lockers is a mobile-first dance learning platform where students can register, access free trial lessons, request full access (admin-approved), and interact with video content through comments and likes. Administrators manage all course content (courses → modules → lessons) and approve student access requests. The platform uses Laravel + Livewire as a SPA, with MySQL database, Tailwind CSS styling, Motion.js animations, and supports both server-hosted videos and YouTube embeds. Deployment targets shared hosting (Hostinger).

**Primary Requirement**: Create a complete learning management system with role-based access control, hierarchical content structure, video playback, and social engagement features (comments, likes).

**Technical Approach**: Livewire SPA architecture for reactive UI without complex JavaScript, server-side validation, MySQL for relational data with proper indexing, responsive mobile-first design with Tailwind, and flexible video hosting (local or YouTube).

## Technical Context

**Language/Version**: PHP 8.1+ (Laravel 10.x or 11.x)
**Primary Dependencies**:
- Laravel (web framework)
- Livewire 3.x (reactive SPA components)
- Tailwind CSS 3.x (utility-first styling)
- Motion.js (smooth animations)
- Laravel Breeze (authentication scaffolding)

**Storage**: MySQL 8.0+
**Testing**: PHPUnit (Laravel's built-in testing framework) + Pest (modern PHP testing framework)
**Target Platform**: Shared hosting (Hostinger) - LAMP stack with PHP 8.1+ support
**Project Type**: Web application (Laravel monolith with Livewire frontend)

**Performance Goals**:
- First Contentful Paint (FCP) < 1.5s on 3G
- Video start time < 3s on mobile
- Database queries < 100ms (p95)
- Page transitions < 300ms (Livewire navigation)

**Constraints**:
- Shared hosting limitations (no Node.js server, limited CLI access for deployments)
- Video hosting flexibility (server storage or YouTube embed)
- Mobile-first design (320px minimum width)
- Progressive enhancement (works without JS for core features)

**Scale/Scope**:
- Initial: 100-500 students
- Content: 5-10 courses, 50-100 lessons
- Admin users: 1-3 administrators
- Comments: ~1000-5000 interactions

## Constitution Check
*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

### I. Mobile-First Design ✅ PASS
- Livewire + Tailwind supports mobile-first responsive design
- Touch targets will use Tailwind spacing utilities (min 44px)
- Bottom navigation planned for mobile thumb zones
- Mobile device testing required before deployment

### II. Progressive Enhancement ✅ PASS
- Livewire forms submit server-side with wire:submit
- HTML5 video player as baseline (native controls)
- Standard links work, Livewire enhances with SPA navigation
- Tailwind CSS inlined for critical path rendering

### III. Content-First Architecture ✅ PASS
- Video delivery optimized (local files + YouTube embed option)
- MySQL indexes on foreign keys and access status fields
- Eager loading for courses → modules → lessons to minimize queries
- Course structure denormalized for read optimization

### IV. Performance & Optimization ⚠️ NEEDS ATTENTION
- FCP/LCP targets achievable with Tailwind + minimal Livewire JS
- Video start time depends on hosting setup (needs research for local video optimization)
- Lazy loading for video thumbnails using Livewire's wire:loading
- Database query optimization with indexes and eager loading

**Action**: Phase 0 research will investigate video optimization strategies for shared hosting

### V. Security & Privacy ✅ PASS
- HTTPS enforced (Hostinger provides SSL)
- Laravel Breeze uses bcrypt for password hashing (cost 12 by default)
- Session management via Laravel (24-hour expiry configurable)
- CSRF protection built into Laravel forms
- Role-based middleware for admin routes

### VI. Accessibility & Inclusion ✅ PASS
- Tailwind supports WCAG color contrast (will use contrast-safe palette)
- HTML5 video supports captions/subtitles
- Keyboard navigation via semantic HTML + Livewire focus management
- Screen reader testing planned for registration/login flows
- Tailwind focus utilities for visible focus indicators

### VII. Test-Driven Development ✅ PASS
- PHPUnit/Pest for feature tests (user stories)
- Laravel HTTP tests for endpoint contract testing
- TDD workflow: tests → user approval → implementation
- Critical paths covered: registration, login, video access, admin approval

**Initial Constitution Check: PASS** (1 attention item for Phase 0 research)

## Project Structure

### Documentation (this feature)
```
specs/master/
├── plan.md              # This file (/plan command output)
├── research.md          # Phase 0 output (/plan command)
├── data-model.md        # Phase 1 output (/plan command)
├── quickstart.md        # Phase 1 output (/plan command)
├── contracts/           # Phase 1 output (/plan command)
└── tasks.md             # Phase 2 output (/tasks command - NOT created by /plan)
```

### Source Code (repository root)

Laravel application structure (web application - Laravel monolith):

```
app/
├── Http/
│   ├── Controllers/          # Traditional controllers (if needed)
│   ├── Middleware/           # Role-based auth middleware
│   └── Livewire/             # Livewire components (primary UI layer)
│       ├── Auth/             # Registration, login components
│       ├── Student/          # Student dashboard, lessons, comments
│       └── Admin/            # Admin panel, course management
├── Models/                   # Eloquent models
│   ├── User.php
│   ├── Course.php
│   ├── Module.php
│   ├── Lesson.php
│   ├── Comment.php
│   ├── Like.php
│   └── AccessRequest.php
└── Policies/                 # Authorization policies

database/
├── migrations/               # Schema definitions
└── seeders/                  # Test data

resources/
├── views/
│   ├── layouts/              # Master layout with Livewire scripts
│   ├── livewire/             # Livewire component views
│   └── components/           # Blade components (buttons, cards, etc.)
└── css/
    └── app.css               # Tailwind entry point

tests/
├── Feature/                  # Integration tests (user stories)
│   ├── Auth/
│   ├── Student/
│   └── Admin/
└── Unit/                     # Unit tests (models, policies)

public/
├── videos/                   # Local video storage (if used)
└── images/                   # Course cover images, thumbnails

routes/
├── web.php                   # Livewire routes
└── auth.php                  # Laravel Breeze auth routes

config/
├── livewire.php              # Livewire SPA config
└── filesystems.php           # Video storage config
```

**Structure Decision**: Laravel monolith with Livewire as the primary frontend layer. This avoids separate frontend/backend directories since Livewire components are tightly integrated with Laravel. All UI interactions happen through Livewire components in `app/Http/Livewire/`, rendered by Blade views in `resources/views/livewire/`. This structure is optimized for shared hosting deployment (single codebase, no build step for frontend).

## Phase 0: Outline & Research

### Unknowns Identified
Based on Technical Context and user requirements:

1. **Video optimization for shared hosting**: How to efficiently serve video files from shared hosting without CDN? Should we prioritize YouTube embeds?
2. **Livewire SPA configuration**: Best practices for wire:navigate and full SPA experience in Livewire 3
3. **Motion.js integration with Livewire**: How to coordinate Motion.js animations with Livewire lifecycle hooks
4. **Shared hosting deployment**: Hostinger-specific deployment considerations (symlinks, permissions, .htaccess)
5. **MySQL optimization**: Index strategies for course hierarchy queries and access status checks
6. **File upload handling**: If supporting local video uploads, what are size limits and storage patterns for shared hosting

### Research Tasks

1. **Video Delivery Strategy**
   - Research: Best practices for video delivery on shared hosting without CDN
   - Investigate: YouTube API embed options (iframe vs player API)
   - Decide: Primary recommendation (YouTube vs local) and fallback strategy
   - Output: Video hosting decision with rationale

2. **Livewire SPA Best Practices**
   - Research: Livewire 3 wire:navigate configuration for full SPA experience
   - Investigate: Persistent layouts, route caching, prefetching
   - Decide: SPA navigation strategy and layout structure
   - Output: Livewire architecture decisions

3. **Motion.js + Livewire Integration**
   - Research: Motion.js initialization in Livewire component lifecycle
   - Investigate: Alpine.js integration (Livewire includes Alpine)
   - Decide: Animation strategy (Alpine transitions vs Motion.js)
   - Output: Animation implementation approach

4. **Shared Hosting Deployment**
   - Research: Laravel deployment on Hostinger shared hosting
   - Investigate: File permissions, symlink setup, .htaccess configuration
   - Decide: Deployment workflow (FTP vs Git-based)
   - Output: Deployment checklist

5. **Database Query Optimization**
   - Research: Laravel eager loading patterns for nested relationships
   - Investigate: Index strategies for polymorphic relationships (if used)
   - Decide: Query optimization approach for course hierarchy
   - Output: Database indexing strategy

6. **Authentication & Authorization**
   - Research: Laravel Breeze vs Jetstream for simple auth
   - Investigate: Role-based middleware patterns
   - Decide: Auth scaffolding choice
   - Output: Auth implementation approach

### Research Output Location
All findings will be consolidated in `C:\Users\Farix Romero\Documents\proyectos\girlockers\specs\master\research.md`

**Output**: research.md with all unknowns resolved

---

## Phase 1: Design & Contracts
*Prerequisites: research.md complete ✅*

### Artifacts Generated:

**1. Data Model** (`data-model.md`)
- 7 database tables with full schema definitions
- Entity relationships (User, Course, Module, Lesson, Comment, LessonLike, AccessRequest)
- Indexes for performance (<100ms queries)
- Migration code for all tables
- Eloquent relationship definitions
- Business rules and validation constraints

**2. Design System** (`contracts/design-system.md`)
- Brand colors extracted from logo (Pink #FF7BA9, Purple #3D4464, Cream #FFFBF0)
- Typography system (Montserrat display, Inter body, Pacifico accent)
- Component library (buttons, cards, navigation)
- Landing page section definitions
- Animation principles (Alpine.js + optional Motion.js)
- Tailwind configuration
- Mobile-first responsive patterns

**3. Quickstart Guide** (`quickstart.md`)
- 8 manual test scenarios
- Student registration & trial access flow
- Admin approval workflow
- Lesson interactions (comments, likes)
- Content management (CRUD)
- Mobile responsiveness tests
- Performance validation steps

### Constitution Re-check: ✅ PASS

All Phase 1 designs align with constitutional requirements:
- **Mobile-First**: Design system starts at 320px, touch targets 44px+
- **Progressive Enhancement**: HTML5 video baseline, server-side forms
- **Content-First**: Optimized database queries, caching strategy
- **Performance**: Indexes on hot paths, denormalized likes_count
- **Security**: Role-based auth, CSRF protection, password hashing
- **Accessibility**: WCAG colors, keyboard navigation, focus states
- **TDD**: Quickstart validates all user stories before implementation

---

## Phase 2: Task Planning Approach
*This section describes what the /tasks command will do - DO NOT execute during /plan*

### Task Generation Strategy

**Input Sources**:
1. **data-model.md**: 7 tables → 7 migration tasks, 7 model tasks, 7 factory tasks
2. **design-system.md**: Tailwind config, component library, landing page sections
3. **quickstart.md**: 8 test scenarios → 8 integration test tasks
4. **research.md**: Technology decisions → setup/configuration tasks

**Task Categories**:

1. **Setup (T001-T010)**: ~10 tasks
   - Laravel installation, Breeze setup, Tailwind config
   - Database configuration, Redis setup
   - Directory structure, environment configuration

2. **Database (T011-T030)**: ~20 tasks
   - 7 migration tasks (users, courses, modules, lessons, comments, likes, access_requests)
   - 7 model tasks with relationships and scopes
   - 7 factory tasks for testing
   - Seeders (admin, test data)

3. **Authentication (T031-T040)**: ~10 tasks
   - Breeze Livewire installation
   - Role enum creation
   - Middleware (admin guard)
   - Gates and policies
   - Custom registration/login flows

4. **Design System (T041-T060)**: ~20 tasks
   - Tailwind configuration with brand colors
   - Component library (buttons, cards, nav)
   - Layout templates (app, student, admin, guest)
   - Landing page structure (hero, sections)

5. **Student Features (T061-T100)**: ~40 tasks
   - Course browsing Livewire components
   - Lesson video player (YouTube + local)
   - Comment system (CRUD)
   - Like system with optimistic updates
   - Access request flow
   - Dashboard

6. **Admin Features (T101-T130)**: ~30 tasks
   - Admin dashboard
   - Course management (CRUD)
   - Module management (CRUD)
   - Lesson management (CRUD + video handling)
   - Student management (approve/revoke access)
   - Comment moderation

7. **Testing (T131-T160)**: ~30 tasks
   - Feature tests for 8 quickstart scenarios
   - Unit tests for models, policies
   - Browser tests (Dusk) for critical flows
   - Performance tests

8. **Optimization & Polish (T161-T180)**: ~20 tasks
   - Caching implementation (Redis)
   - Query optimization
   - Asset optimization (Vite build)
   - Accessibility audit
   - Mobile testing
   - Performance tuning

**Ordering Strategy**:
- **TDD Order**: Tests written before implementation
- **Dependency Order**: Database → Auth → UI → Features
- **Parallel Markers [P]**: Tasks in different files can run concurrently

**Estimated Output**: ~180 numbered, ordered tasks in tasks.md

### Task Example Format:

```
T001 [P] Install Laravel 11 with Breeze Livewire stack
T002 [P] Configure Tailwind CSS with Girl Lockers brand colors in tailwind.config.js
T003 Create users migration with role, has_full_access columns in database/migrations/
T004 [P] Create User model with UserRole enum casting in app/Models/User.php
T005 [P] Create UserFactory with role variations in database/factories/UserFactory.php
...
```

**Dependency Graph Example**:
```
T003 (users migration) → T004 (User model) → T031 (Breeze setup)
T006 (courses migration) → T007 (Course model) → T061 (Course browsing component)
```

**Parallel Execution Example**:
```bash
# These tasks can run simultaneously:
- T004 (User model)
- T007 (Course model)
- T010 (Module model)
# All modify different files, no dependencies
```

### Implementation Notes:

- **Phase 2 command** `/tasks` will generate tasks.md from this approach
- **Phase 3-4** will execute tasks sequentially or in parallel as marked
- **Validation**: Each task must pass tests before moving to next
- **Commit strategy**: Commit after each task completion
- **Code review**: All tasks reviewed before merge to main

---

## Phase 3+: Future Implementation
*These phases are beyond the scope of the /plan command*

**Phase 3**: Task execution (/tasks command creates tasks.md)
**Phase 4**: Implementation (execute tasks.md following constitutional principles)
**Phase 5**: Validation (run tests, execute quickstart.md, performance validation)

---

## Complexity Tracking
*No constitutional violations - all approaches align with principles*

No entries - design fully complies with constitution v1.0.0

---

## Progress Tracking
*This checklist is updated during execution flow*

**Phase Status**:
- [x] Phase 0: Research complete (/plan command)
- [x] Phase 1: Design complete (/plan command)
- [x] Phase 2: Task planning complete (/plan command - describe approach only)
- [ ] Phase 3: Tasks generated (/tasks command)
- [ ] Phase 4: Implementation complete
- [ ] Phase 5: Validation passed

**Gate Status**:
- [x] Initial Constitution Check: PASS
- [x] Post-Design Constitution Check: PASS
- [x] All NEEDS CLARIFICATION resolved
- [x] Complexity deviations documented (none)

**Artifacts Created**:
- [x] plan.md (this file)
- [x] research.md (6 research areas)
- [x] data-model.md (7 tables, relationships)
- [x] contracts/design-system.md (brand identity, components)
- [x] quickstart.md (8 test scenarios)
- [ ] CLAUDE.md (agent context - next step)
- [ ] tasks.md (generated by /tasks command)

---

*Based on Constitution v1.0.0 - See `.specify/memory/constitution.md`*

**Plan Complete**: ✅ Ready for `/tasks` command

