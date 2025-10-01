<!--
SYNC IMPACT REPORT
===================
Version: 0.0.0 → 1.0.0
Rationale: Initial constitution creation for Girl Lockers mobile-first dance platform

Added Principles:
- I. Mobile-First Design
- II. Progressive Enhancement
- III. Content-First Architecture
- IV. Performance & Optimization
- V. Security & Privacy
- VI. Accessibility & Inclusion
- VII. Test-Driven Development

Added Sections:
- Technical Standards (video, database, authentication)
- Development Workflow (TDD, review, deployment)
- Governance (compliance, versioning, amendments)

Templates Status:
✅ plan-template.md - Constitution Check section references this file
✅ spec-template.md - Aligned with user-centric, testable requirements approach
✅ tasks-template.md - Aligned with TDD and parallel execution principles

Follow-up TODOs: None - all placeholders resolved

Commit Message:
docs: create constitution v1.0.0 (mobile-first dance platform principles)
-->

# Girl Lockers Platform Constitution

## Core Principles

### I. Mobile-First Design
Every feature, component, and user interface MUST be designed and developed with mobile devices as the primary target. Desktop experiences are secondary enhancements.

**Rules**:
- Touch targets MUST be minimum 44x44px
- All interactions MUST work with touch gestures
- Navigation MUST be thumb-friendly (bottom-oriented for key actions)
- Viewport units and responsive breakpoints MUST start from 320px width
- Testing on actual mobile devices MUST occur before deployment

**Rationale**: The platform's primary users are dance students accessing content on-the-go. Mobile-first ensures optimal experience where it matters most.

### II. Progressive Enhancement
Core functionality MUST work without JavaScript. Enhanced features MAY require JavaScript. All content MUST be accessible regardless of device capabilities.

**Rules**:
- Video playback MUST use native HTML5 controls as baseline
- Forms MUST submit and validate server-side
- Navigation MUST work with standard links before JS enhancement
- Critical CSS MUST inline for first paint
- Offline indicators MUST inform users of connectivity requirements

**Rationale**: Ensures reliability across varying network conditions and device capabilities common in dance studio environments.

### III. Content-First Architecture
Video content and learning material delivery takes precedence over all other concerns. Every architectural decision MUST optimize for content access speed and reliability.

**Rules**:
- Video streaming MUST use adaptive bitrate (ABR) for mobile networks
- Lesson content MUST be cacheable
- Database queries for content MUST have indexes and be optimized for <100ms response
- Static assets (thumbnails, course images) MUST use CDN
- Course structure MUST be denormalized for fast reads (write optimization secondary)

**Rationale**: Students pay for content access. Poor video performance directly impacts business value and user satisfaction.

### IV. Performance & Optimization
Page load times, video start times, and interaction responsiveness directly impact learning engagement and retention.

**Rules**:
- First Contentful Paint (FCP) MUST be <1.5s on 3G connections
- Video start time MUST be <3s on mobile
- Total Blocking Time (TBT) MUST be <300ms
- Largest Contentful Paint (LCP) MUST be <2.5s
- Lazy loading MUST be implemented for below-fold video thumbnails
- Database queries MUST complete in <100ms (p95)

**Rationale**: Dance instruction requires smooth video playback and responsive interactions. Performance is a feature, not an afterthought.

### V. Security & Privacy
Student data, access credentials, and payment validation (external) MUST be protected. Admin privileges MUST be tightly controlled.

**Rules**:
- All connections MUST use HTTPS
- Passwords MUST be hashed with bcrypt (cost factor ≥12)
- Session tokens MUST expire after 24 hours of inactivity
- Admin actions MUST be logged with timestamp, user, and action type
- User email addresses MUST NOT be exposed in client-side code or URLs
- File uploads (if added) MUST validate file type and size server-side
- CSRF protection MUST be enabled for all state-changing requests

**Rationale**: Platform handles user registration, authentication, and admin-managed access control. Security breaches would compromise trust and business operations.

### VI. Accessibility & Inclusion
Dance is for everyone. The platform MUST be usable by people with varying abilities, on varying devices, in varying contexts.

**Rules**:
- Color contrast MUST meet WCAG 2.1 AA standards (4.5:1 for normal text)
- Video players MUST support captions/subtitles when available
- Keyboard navigation MUST reach all interactive elements
- Screen reader announcements MUST be tested for critical flows (registration, lesson access)
- Focus indicators MUST be visible and clear
- Error messages MUST be descriptive and actionable

**Rationale**: Dance education should be accessible to all learners. Accessibility expands market reach and aligns with inclusive values.

### VII. Test-Driven Development
Features MUST have tests written before implementation. Tests define behavior; implementation satisfies tests.

**Rules**:
- Every user story MUST have integration tests written first
- Every API endpoint MUST have contract tests before implementation
- Tests MUST fail initially (red)
- Implementation MUST make tests pass (green)
- Refactoring MUST keep tests green
- Critical paths (registration, video access, admin approval) MUST have end-to-end tests

**Rationale**: TDD ensures features work as specified, reduces bugs, and provides regression protection as platform evolves.

## Technical Standards

**Stack Requirements**:
- Backend: Laravel (PHP 8.1+)
- Frontend: Livewire (Laravel integration for reactive components)
- Database: MySQL 8.0+ or PostgreSQL 13+
- Video: HTML5 with HLS or DASH for adaptive streaming
- Authentication: Laravel Breeze or Jetstream (email/password)

**Video Delivery**:
- Video files MUST be transcoded to multiple bitrates (360p, 480p, 720p minimum)
- Video hosting MUST support HTTP range requests for seeking
- Adaptive bitrate switching MUST be automatic based on network conditions

**Database Schema**:
- Indexes MUST exist on foreign keys and frequently queried fields
- Student access status MUST be queryable in O(1) time (indexed boolean or enum)
- Comments MUST be soft-deletable (preserve for moderation history)

**Notifications**:
- Admin notifications for new access requests MUST be real-time or <5 minute delay
- Notification delivery failures MUST be logged for retry

## Development Workflow

**Test-First Protocol**:
1. Write failing test(s) for feature
2. Submit tests for user approval
3. Implement minimum code to pass tests
4. Refactor while keeping tests green
5. Code review includes test coverage verification

**Code Review Requirements**:
- All PRs MUST pass automated tests before review
- At least one manual mobile device test MUST be documented in PR
- Performance impact MUST be assessed for video-related changes
- Security implications MUST be reviewed for authentication/authorization changes

**Deployment Gates**:
- All tests MUST pass
- No critical security vulnerabilities (from dependency scanning)
- Manual smoke test on staging (mobile device) MUST be documented
- Database migrations MUST be reversible

## Governance

**Compliance**:
All code, features, and design decisions MUST align with these principles. Deviations MUST be:
1. Documented with justification in plan.md (Complexity Tracking section)
2. Reviewed and approved explicitly
3. Time-boxed with plan to refactor toward compliance

**Amendment Process**:
- Amendments MUST be proposed with specific use case and rationale
- Breaking changes (removing principles) require MAJOR version increment
- New principles or expanded guidance require MINOR version increment
- Clarifications and wording improvements require PATCH version increment
- All amendments MUST propagate to plan-template.md, spec-template.md, tasks-template.md

**Version Control**:
- This constitution governs all features and workflows
- Outdated patterns in legacy code MUST migrate toward compliance during relevant changes
- New features MUST comply from start

**Review Cadence**:
- Constitution MUST be reviewed after first 3 features implemented
- Principles causing friction MUST be evaluated for refinement (not removal without cause)

**Version**: 1.0.0 | **Ratified**: 2025-09-30 | **Last Amended**: 2025-09-30
