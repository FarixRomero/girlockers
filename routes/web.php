<?php

use App\Livewire\Landing\HomePage;
use App\Livewire\Student\CourseCatalog;
use App\Livewire\Student\CourseDetail;
use App\Livewire\Student\LessonView;
use App\Livewire\Student\RequestAccess;
use App\Livewire\Student\Dashboard;
use App\Livewire\Student\LessonCatalog;
use App\Livewire\Student\InstructorCatalog;
use App\Livewire\Student\SavedContent;
use App\Livewire\Student\WatchHistory;
use App\Livewire\Student\PurchaseMembership;
use App\Livewire\Student\PaymentSuccess;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\StudentManagement;
use App\Livewire\Admin\CommentModeration;
use App\Livewire\Admin\CourseManagement;
use App\Livewire\Admin\ModuleManagement;
use App\Livewire\Admin\InstructorManagement;
use App\Livewire\Admin\TagManagement;
use App\Livewire\Admin\LandingConfig;
use App\Http\Controllers\VideoStreamController;
use Illuminate\Support\Facades\Route;

// Landing Page
Route::get('/', HomePage::class)->name('home');

// Student Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('dashboard', Dashboard::class)->name('dashboard');

    // Courses & Lessons
    Route::get('courses', CourseCatalog::class)->name('courses.index');
    Route::get('courses/{course}', CourseDetail::class)->name('courses.show');
    Route::get('lessons', LessonCatalog::class)->name('lessons.index');
    Route::get('lessons/{lesson}', LessonView::class)->name('lessons.show');

    // Instructors
    Route::get('instructors', InstructorCatalog::class)->name('instructors.index');

    // Saved & History
    Route::get('saved', SavedContent::class)->name('saved.index');
    Route::get('history', WatchHistory::class)->name('history.index');

    // Video Streaming
    Route::get('lessons/{lesson}/stream', [VideoStreamController::class, 'stream'])->name('lessons.stream');

    // Access Request
    Route::get('request-access', RequestAccess::class)->name('request-access');

    // Profile
    Route::get('profile', \App\Livewire\Student\Profile::class)->name('profile');

    // Membership Purchase
    Route::get('purchase-membership', PurchaseMembership::class)->name('purchase-membership');
    Route::get('payment/form/{paymentId}', [App\Http\Controllers\PaymentFormController::class, 'show'])->name('payment.form');
    Route::post('payment/pay-with-saved-card', [App\Http\Controllers\PaymentFormController::class, 'payWithSavedCard'])->name('payment.pay-with-saved-card');
    Route::get('payment/success', PaymentSuccess::class)->name('payment.success');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Redirect /admin to /admin/dashboard
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    Route::get('dashboard', AdminDashboard::class)->name('dashboard');
    Route::get('users', StudentManagement::class)->name('users.index');
    Route::get('comments', CommentModeration::class)->name('comments.index');
    Route::get('profile', \App\Livewire\Admin\AdminProfile::class)->name('profile');
    Route::get('courses', CourseManagement::class)->name('courses.index');
    Route::get('courses/{courseId}/modules', ModuleManagement::class)->name('courses.modules');

    // Lesson list view (Livewire component)
    Route::get('modules/{moduleId}/lessons', \App\Livewire\Admin\LessonManagement::class)->name('modules.lessons');
    // Global lesson create (from navbar) - Instagram style
    Route::get('lessons/create', \App\Livewire\Admin\LessonCreate::class)->name('lessons.create-global');

    // Module-specific lesson create - Instagram style
    Route::get('modules/{moduleId}/lessons/create', \App\Livewire\Admin\LessonCreate::class)->name('lessons.create');

    // Lesson edit - Instagram style
    Route::get('lessons/{lessonId}/edit', \App\Livewire\Admin\LessonEdit::class)->name('lessons.edit');
    Route::get('instructors', InstructorManagement::class)->name('instructors.index');
    Route::get('tags', TagManagement::class)->name('tags.index');
    Route::get('landing-config', LandingConfig::class)->name('landing-config.index');

    // Bunny.net video upload endpoints
    Route::post('lessons/bunny/init-upload', [App\Http\Controllers\Admin\BunnyUploadController::class, 'initUpload'])->name('lessons.bunny.init');
    Route::post('lessons/bunny/confirm-upload', [App\Http\Controllers\Admin\BunnyUploadController::class, 'confirmUpload'])->name('lessons.bunny.confirm');
    Route::post('lessons/bunny/duration', [App\Http\Controllers\Admin\BunnyUploadController::class, 'getBunnyDuration'])->name('lessons.bunny.duration');

    // Thumbnail upload (used by LessonCreate/LessonEdit)
    Route::post('api/upload-thumbnail', [App\Http\Controllers\Admin\BunnyUploadController::class, 'uploadThumbnail'])->name('api.upload-thumbnail');

    // Admin - Membership Plans Management (placeholder - will be implemented in Phase 4)
    // Route::get('membership-plans', \App\Livewire\Admin\MembershipPlanManagement::class)->name('membership-plans');
});

// Payment Callbacks (without CSRF protection - handled by Izipay)
Route::post('payment/callback/success', [App\Http\Controllers\PaymentCallbackController::class, 'handleSuccess'])
    ->name('payment.callback.success');

Route::post('payment/callback/error', [App\Http\Controllers\PaymentCallbackController::class, 'handleError'])
    ->name('payment.callback.error');

require __DIR__.'/auth.php';
