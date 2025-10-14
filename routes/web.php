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
use App\Livewire\Admin\AccessRequests;
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
    Route::view('profile', 'profile')->name('profile');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', AdminDashboard::class)->name('dashboard');
    Route::get('users', StudentManagement::class)->name('users.index');
    Route::get('access-requests', AccessRequests::class)->name('access-requests.index');
    Route::get('comments', CommentModeration::class)->name('comments.index');
    Route::get('courses', CourseManagement::class)->name('courses.index');
    Route::get('courses/{courseId}/modules', ModuleManagement::class)->name('courses.modules');
    Route::get('modules/{moduleId}/lessons', function ($moduleId) {
        $instructors = \App\Models\Instructor::orderBy('name')->get();
        $tags = \App\Models\Tag::orderBy('name')->get();
        return view('livewire.admin.lesson-management', [
            'moduleId' => $moduleId,
            'instructors' => $instructors,
            'tags' => $tags
        ]);
    })->name('modules.lessons');
    Route::get('instructors', InstructorManagement::class)->name('instructors.index');
    Route::get('tags', TagManagement::class)->name('tags.index');
    Route::get('modules/{moduleId}/lessons-pure', function ($moduleId) {
        return view('admin.lesson-management-pure', ['moduleId' => $moduleId]);
    })->name('modules.lessons-pure');

    // Bunny.net video upload endpoints
    Route::post('lessons/bunny/init-upload', [App\Http\Controllers\Admin\BunnyUploadController::class, 'initUpload'])->name('lessons.bunny.init');
    Route::post('lessons/bunny/confirm-upload', [App\Http\Controllers\Admin\BunnyUploadController::class, 'confirmUpload'])->name('lessons.bunny.confirm');

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
    });
});

require __DIR__.'/auth.php';
