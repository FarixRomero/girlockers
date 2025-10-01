<?php

namespace App\Livewire\Student;

use App\Models\Course;
use App\Models\Lesson;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Dashboard - Girls Lockers')]
class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();

        // Get accessible courses
        $courses = Course::where('is_published', true)
            ->withCount('modules')
            ->get();

        // Get trial lessons
        $trialLessons = Lesson::where('is_trial', true)
            ->with(['module.course'])
            ->latest()
            ->take(6)
            ->get();

        // Get user stats
        $stats = [
            'total_comments' => $user->comments()->count(),
            'total_likes' => $user->likes()->count(),
            'accessible_courses' => $courses->count(),
            'has_access' => $user->hasFullAccess(),
        ];

        // Get recent comments from user
        $recentComments = $user->comments()
            ->with('lesson.module.course')
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.student.dashboard', [
            'courses' => $courses,
            'trialLessons' => $trialLessons,
            'stats' => $stats,
            'recentComments' => $recentComments,
        ]);
    }
}
