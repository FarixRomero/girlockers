<?php

use App\Livewire\Landing\HomePage;
use App\Livewire\Student\CourseCatalog;
use App\Livewire\Student\CourseDetail;
use App\Livewire\Student\LessonView;
use App\Livewire\Student\RequestAccess;
use App\Livewire\Student\Dashboard;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\StudentManagement;
use App\Livewire\Admin\AccessRequests;
use App\Livewire\Admin\CommentModeration;
use App\Livewire\Admin\CourseManagement;
use App\Livewire\Admin\ModuleManagement;
use App\Livewire\Admin\LessonManagement;
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
    Route::get('lessons/{lesson}', LessonView::class)->name('lessons.show');

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
    Route::get('modules/{moduleId}/lessons', LessonManagement::class)->name('modules.lessons');
});

require __DIR__.'/auth.php';
