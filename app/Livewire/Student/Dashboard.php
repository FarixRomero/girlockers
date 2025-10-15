<?php

namespace App\Livewire\Student;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Tag;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.student')]
#[Title('Dashboard - Girls Lockers')]
class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();

        // Get user stats - minutos bailando calculados desde las clases completadas
        $completedLessons = $user->likes()->count(); // Usando likes como proxy de clases completadas
        $totalMinutes = $user->likes()
            ->whereNotNull('duration')
            ->sum('duration') ?? 0;

        $stats = [
            'name' => $user->name,
            'total_minutes' => $totalMinutes,
            'completed_lessons' => $completedLessons,
            'has_access' => $user->hasFullAccess(),
        ];

        // Get recent lessons (últimas clases agregadas a la plataforma)
        $recentLessons = Lesson::accessibleBy($user)
            ->with(['module.course', 'instructor', 'tags'])
            ->latest()
            ->take(8)
            ->get();

        // Get 4 tags with most lessons
        $topTags = Tag::withCount('lessons')
            ->orderByDesc('lessons_count')
            ->take(4)
            ->get();

        // Get lessons by each tag
        $lessonsByTag = [];
        foreach ($topTags as $tag) {
            $lessonsByTag[$tag->name] = Lesson::whereHas('tags', function($query) use ($tag) {
                    $query->where('tags.id', $tag->id);
                })
                ->accessibleBy($user)
                ->with(['module.course', 'instructor', 'tags'])
                ->inRandomOrder()
                ->take(8)
                ->get();
        }

        // Get saved/liked lessons
        $savedLessons = $user->likes()
            ->with(['module.course', 'instructor', 'tags'])
            ->take(8)
            ->get();

        // Get trending courses (cursos de moda - basados en número de módulos)
        $trendingCourses = Course::where('is_published', true)
            ->withCount('modules')
            ->with(['modules'])
            ->orderByDesc('modules_count')
            ->take(6)
            ->get();

        return view('livewire.student.dashboard', [
            'stats' => $stats,
            'recentLessons' => $recentLessons,
            'topTags' => $topTags,
            'lessonsByTag' => $lessonsByTag,
            'savedLessons' => $savedLessons,
            'trendingCourses' => $trendingCourses,
        ]);
    }
}
