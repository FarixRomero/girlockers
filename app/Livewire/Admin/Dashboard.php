<?php

namespace App\Livewire\Admin;

use App\Models\AccessRequest;
use App\Models\Course;
use App\Models\User;
use App\Models\Lesson;
use App\Models\Comment;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Admin Dashboard - Girls Lockers')]
class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'total_students' => User::where('role', 'student')->count(),
            'pending_requests' => AccessRequest::where('status', 'pending')->count(),
            'total_courses' => Course::count(),
            'published_courses' => Course::where('is_published', true)->count(),
            'total_lessons' => Lesson::count(),
            'total_comments' => Comment::count(),
            'premium_students' => User::where('role', 'student')->where('has_full_access', true)->count(),
        ];

        $pendingRequests = AccessRequest::where('status', 'pending')
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        $recentComments = Comment::with(['user', 'lesson.module.course'])
            ->latest()
            ->take(10)
            ->get();

        return view('livewire.admin.dashboard', [
            'stats' => $stats,
            'pendingRequests' => $pendingRequests,
            'recentComments' => $recentComments,
        ]);
    }
}
