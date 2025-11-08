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
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\StudentManagement;
use App\Livewire\Admin\CommentModeration;
use App\Livewire\Admin\CourseManagement;
use App\Livewire\Admin\ModuleManagement;
use App\Livewire\Admin\InstructorManagement;
use App\Livewire\Admin\TagManagement;
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
    Route::get('courses', CourseManagement::class)->name('courses.index');
    Route::get('courses/{courseId}/modules', ModuleManagement::class)->name('courses.modules');

    // Lesson list view (uses vanilla JS + API, NOT Livewire)
    Route::get('modules/{moduleId}/lessons', function ($moduleId) {
        $instructors = \App\Models\Instructor::orderBy('name')->get();
        $tags = \App\Models\Tag::orderBy('name')->get();
        return view('admin.lesson-management', [
            'moduleId' => $moduleId,
            'instructors' => $instructors,
            'tags' => $tags
        ]);
    })->name('modules.lessons');
    // Global lesson create (from navbar)
    Route::get('lessons/create', function () {
        $courses = \App\Models\Course::with('modules')->orderBy('title')->get();
        $instructors = \App\Models\Instructor::orderBy('name')->get();
        $tags = \App\Models\Tag::orderBy('name')->get();
        return view('admin.lesson-form', [
            'module' => null,
            'lesson' => null,
            'courses' => $courses,
            'instructors' => $instructors,
            'tags' => $tags,
            'nextOrder' => 1
        ]);
    })->name('lessons.create-global');

    Route::get('modules/{moduleId}/lessons/create', function ($moduleId) {
        $module = \App\Models\Module::with('course')->findOrFail($moduleId);
        $instructors = \App\Models\Instructor::orderBy('name')->get();
        $tags = \App\Models\Tag::orderBy('name')->get();
        $nextOrder = $module->lessons()->max('order') + 1;
        return view('admin.lesson-form', [
            'module' => $module,
            'lesson' => null,
            'courses' => null,
            'instructors' => $instructors,
            'tags' => $tags,
            'nextOrder' => $nextOrder
        ]);
    })->name('lessons.create');
    Route::get('lessons/{lessonId}/edit', function ($lessonId) {
        $lesson = \App\Models\Lesson::with(['module.course', 'tags'])->findOrFail($lessonId);
        $instructors = \App\Models\Instructor::orderBy('name')->get();
        $tags = \App\Models\Tag::orderBy('name')->get();
        return view('admin.lesson-form', [
            'module' => $lesson->module,
            'lesson' => $lesson,
            'courses' => null,
            'instructors' => $instructors,
            'tags' => $tags,
            'nextOrder' => null
        ]);
    })->name('lessons.edit');
    Route::get('instructors', InstructorManagement::class)->name('instructors.index');
    Route::get('tags', TagManagement::class)->name('tags.index');

    // Bunny.net video upload endpoints
    Route::post('lessons/bunny/init-upload', [App\Http\Controllers\Admin\BunnyUploadController::class, 'initUpload'])->name('lessons.bunny.init');
    Route::post('lessons/bunny/confirm-upload', [App\Http\Controllers\Admin\BunnyUploadController::class, 'confirmUpload'])->name('lessons.bunny.confirm');

    // Thumbnail upload
    Route::post('api/upload-thumbnail', [App\Http\Controllers\Api\LessonController::class, 'uploadThumbnail'])->name('api.upload-thumbnail');

    // API Routes for Lesson Management
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('modules/{moduleId}/lessons', [App\Http\Controllers\Api\LessonController::class, 'index'])->name('lessons.index');
        Route::post('lessons', [App\Http\Controllers\Api\LessonController::class, 'store'])->name('lessons.store');
        Route::get('lessons/{id}', [App\Http\Controllers\Api\LessonController::class, 'show'])->name('lessons.show');
        Route::put('lessons/{id}', [App\Http\Controllers\Api\LessonController::class, 'update'])->name('lessons.update');
        Route::delete('lessons/{id}', [App\Http\Controllers\Api\LessonController::class, 'destroy'])->name('lessons.destroy');
        Route::post('lessons/{id}/move-up', [App\Http\Controllers\Api\LessonController::class, 'moveUp'])->name('lessons.move-up');
        Route::post('lessons/{id}/move-down', [App\Http\Controllers\Api\LessonController::class, 'moveDown'])->name('lessons.move-down');
        Route::post('lessons/{id}/toggle-trial', [App\Http\Controllers\Api\LessonController::class, 'toggleTrial'])->name('lessons.toggle-trial');
        Route::post('lessons/bunny/duration', [App\Http\Controllers\Api\LessonController::class, 'getBunnyDuration'])->name('lessons.bunny.duration');
    });
});

require __DIR__.'/auth.php';
