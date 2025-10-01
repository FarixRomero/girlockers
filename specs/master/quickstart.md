# Quickstart Guide: Girl Lockers Platform

**Purpose**: Validate core user journeys manually before automated testing
**Target**: Post-Phase 1 validation, Pre-implementation smoke test

---

## Prerequisites

- Laravel application installed
- Database migrated and seeded
- At least 1 admin user and 2 student users
- At least 1 published course with trial and premium lessons
- Development server running (`php artisan serve`)

---

## Test Scenario 1: Student Registration & Trial Access

**Goal**: Verify students can register and immediately access trial lessons

### Steps:

1. **Navigate to homepage**
   - URL: `http://localhost:8000`
   - ✅ Landing page loads with hero section
   - ✅ "Comienza Gratis" CTA button visible

2. **Register new student account**
   - Click "Comienza Gratis" or "Register"
   - Fill form:
     - Name: "María García"
     - Email: "maria@test.com"
     - Password: "password123"
   - Submit form
   - ✅ Redirected to `/dashboard`
   - ✅ Welcome message displayed

3. **Browse courses**
   - Navigate to `/courses`
   - ✅ Published courses visible
   - ✅ Course cards show level badges (Beginner/Intermediate/Advanced)

4. **Access trial lesson**
   - Click on any course
   - Click on a lesson marked "GRATIS" or with trial badge
   - ✅ Video player loads
   - ✅ Video plays successfully
   - ✅ Comment section visible

5. **Attempt premium lesson**
   - Navigate back to course
   - Click on a lesson WITHOUT trial badge
   - ✅ Access denied message displayed
   - ✅ "Solicitar Acceso Completo" button visible

---

## Test Scenario 2: Access Request Flow

**Goal**: Verify students can request access and admins can approve

### Steps:

1. **Request full access (as student)**
   - Click "Solicitar Acceso Completo" button
   - ✅ Confirmation message: "Solicitud enviada al administrador"
   - ✅ Button changes to "Solicitud Pendiente" (disabled)

2. **Admin receives notification**
   - Log out from student account
   - Log in as admin: `admin@girlockers.com` / `password`
   - Navigate to `/admin/dashboard`
   - ✅ Pending access requests visible
   - ✅ María García's request shows with timestamp

3. **Admin approves access**
   - Click "Aprobar" on María's request
   - ✅ Success message: "Acceso completo otorgado"
   - ✅ Request marked as "Aprobado"

4. **Student gains access**
   - Log out from admin
   - Log in as María García
   - Navigate to course with premium lessons
   - Click on previously locked lesson
   - ✅ Video loads and plays
   - ✅ No access restriction message

---

## Test Scenario 3: Lesson Interactions

**Goal**: Verify comments and likes functionality

### Steps:

1. **Post comment (as student with access)**
   - Navigate to any accessible lesson
   - Scroll to comment section
   - Type comment: "¡Excelente clase! Muy claro el movimiento."
   - Click "Publicar Comentario"
   - ✅ Comment appears at top of list
   - ✅ Author name and timestamp displayed

2. **Like lesson**
   - Click heart/like button
   - ✅ Like count increments by 1
   - ✅ Heart icon changes to filled/colored state
   - ✅ Button disabled or shows "Liked"

3. **Unlike lesson**
   - Click like button again
   - ✅ Like count decrements by 1
   - ✅ Heart icon returns to outline/unfilled state

4. **Admin moderates comment**
   - Log in as admin
   - Navigate to same lesson
   - Click "Eliminar" on María's comment
   - ✅ Confirmation prompt appears
   - Confirm deletion
   - ✅ Comment removed from list

---

## Test Scenario 4: Admin Content Management

**Goal**: Verify admins can manage courses, modules, and lessons

### Steps:

1. **Create new course**
   - Navigate to `/admin/courses`
   - Click "Crear Curso"
   - Fill form:
     - Title: "Locking Avanzado"
     - Description: "Técnicas avanzadas de locking..."
     - Level: "Advanced"
     - Upload cover image
   - Click "Guardar"
   - ✅ Course created and appears in list
   - ✅ Status shows "No Publicado"

2. **Add module to course**
   - Click on "Locking Avanzado" course
   - Click "Agregar Módulo"
   - Name: "Fundamentos Avanzados"
   - Order: 1
   - Click "Guardar"
   - ✅ Module appears in course structure

3. **Add lesson to module**
   - Click on "Fundamentos Avanzados" module
   - Click "Agregar Lección"
   - Fill form:
     - Title: "Waving Complejo"
     - Description: "Técnica de waving avanzada"
     - Video Type: "YouTube"
     - YouTube ID: "dQw4w9WgXcQ"
     - Trial: No (unchecked)
     - Order: 1
   - Click "Guardar"
   - ✅ Lesson created in module

4. **Publish course**
   - Return to course edit page
   - Check "Publicado" checkbox
   - Click "Guardar"
   - ✅ Course status changes to "Publicado"
   - Log out and log in as student
   - ✅ Course now visible in student course list

---

## Test Scenario 5: Mobile Responsiveness

**Goal**: Verify mobile-first design works on small screens

### Steps:

1. **Open dev tools**
   - Press F12
   - Toggle device toolbar (Ctrl+Shift+M)
   - Select "iPhone SE" (375x667) or similar

2. **Navigate homepage**
   - ✅ Hero section text readable (no overflow)
   - ✅ CTA buttons minimum 44px height (touch-friendly)
   - ✅ Navigation menu accessible (hamburger or bottom nav)

3. **Browse courses (mobile)**
   - Navigate to `/courses`
   - ✅ Course cards stack vertically
   - ✅ Images scale properly
   - ✅ No horizontal scroll

4. **Watch lesson (mobile)**
   - Click on trial lesson
   - ✅ Video player responsive (16:9 ratio maintained)
   - ✅ Controls accessible with touch
   - ✅ Comment form not obscured by keyboard

5. **Test bottom navigation (mobile)**
   - ✅ Fixed navigation at bottom
   - ✅ Icons minimum 44x44px
   - ✅ Active state clearly visible
   - ✅ Navigation persists across pages

---

## Test Scenario 6: Performance & Loading States

**Goal**: Verify loading states and perceived performance

### Steps:

1. **Enable slow 3G throttling**
   - Open dev tools Network tab
   - Select "Slow 3G" from throttling dropdown

2. **Navigate between pages**
   - Click between courses, lessons, dashboard
   - ✅ Loading spinner or skeleton screens visible
   - ✅ Navigation doesn't feel broken
   - ✅ Page transitions smooth (wire:navigate)

3. **Load course with many lessons**
   - Navigate to course with 10+ lessons
   - ✅ Lazy loading implemented (not all thumbnails load at once)
   - ✅ Page loads in <3 seconds
   - ✅ No layout shift as images load

4. **Submit comment (slow network)**
   - Post a comment on any lesson
   - ✅ Button shows "Publicando..." state
   - ✅ Button disabled during submission
   - ✅ Success feedback after post

---

## Test Scenario 7: Video Playback (YouTube + Local)

**Goal**: Verify both video types work correctly

### Steps:

1. **YouTube embed playback**
   - Navigate to lesson with YouTube video
   - ✅ Thumbnail loads (lazy facade pattern)
   - Click play button
   - ✅ YouTube iframe loads
   - ✅ Video starts playing in <3 seconds
   - ✅ Seek bar functional

2. **Local video playback (if implemented)**
   - Navigate to lesson with local video
   - ✅ HTML5 video player displays
   - ✅ Video starts playing
   - ✅ Seek functionality works (HTTP range requests)
   - ✅ Controls visible and functional

---

## Test Scenario 8: Authentication & Authorization

**Goal**: Verify role-based access control

### Steps:

1. **Student tries to access admin routes**
   - Log in as student
   - Navigate to `/admin/dashboard` directly
   - ✅ 403 Forbidden error or redirect to student dashboard
   - ✅ Error message: "Unauthorized action"

2. **Unauthenticated user accesses protected routes**
   - Log out
   - Navigate to `/courses/1/lessons/1` directly
   - ✅ Redirected to login page
   - ✅ After login, redirected back to lesson

3. **Trial user accesses premium lesson directly**
   - Log in as student without full access
   - Navigate to premium lesson URL directly
   - ✅ Access denied message or redirect
   - ✅ "Request Access" option visible

---

## Expected Performance Metrics

| Metric | Target | How to Measure |
|--------|--------|----------------|
| **FCP** | <1.5s on 3G | Lighthouse Performance tab |
| **Video Start** | <3s | Manual stopwatch on lesson page |
| **Page Transition** | <300ms | Perceived (wire:navigate) |
| **Database Queries** | <100ms | Laravel Debugbar |
| **Lighthouse Score** | 90+ | Chrome DevTools Lighthouse |

---

## Validation Checklist

- [ ] All 8 test scenarios pass
- [ ] No JavaScript console errors
- [ ] No PHP errors in `storage/logs/laravel.log`
- [ ] Mobile responsiveness verified (375px, 768px, 1024px)
- [ ] All forms have validation and error messages
- [ ] Loading states visible on slow connections
- [ ] Videos play successfully (YouTube + local)
- [ ] Comments and likes work without page reload
- [ ] Admin can manage all content (CRUD)
- [ ] Access control enforced (students can't access admin routes)
- [ ] Landing page loads with premium design aesthetic
- [ ] Animations smooth on mobile (60fps)

---

## Known Limitations (MVP)

- No email notifications (manual admin panel checks)
- No real-time notifications (refresh to see updates)
- No video transcoding (admin uploads pre-compressed videos)
- No CDN for local videos (shared hosting direct serve)
- No user profile editing beyond password change
- No course progress tracking (future feature)

---

## Next Steps After Quickstart

1. ✅ All scenarios pass → Proceed to automated testing (Phase 3)
2. ❌ Scenarios fail → Document bugs, fix, and re-run quickstart
3. Document any deviations from spec in `CHANGELOG.md`
4. Capture screenshots of key flows for documentation

---

**Quickstart Complete**: Ready for TDD implementation phase

