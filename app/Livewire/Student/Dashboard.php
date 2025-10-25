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

        // Get specific tag categories for carousels
        $coreografiaTag = Tag::where('name', 'Coreografía')->first();
        $lecturasTag = Tag::where('name', 'Lecturas y Conceptos')->first();

        // Get lessons for Coreografía
        $coreografiaLessons = collect();
        if ($coreografiaTag) {
            $coreografiaLessons = Lesson::whereHas('tags', function($query) use ($coreografiaTag) {
                    $query->where('tags.id', $coreografiaTag->id);
                })
                ->accessibleBy($user)
                ->with(['module.course', 'instructor', 'tags'])
                ->inRandomOrder()
                ->take(8)
                ->get();
        }

        // Get lessons for Lecturas y Conceptos
        $lecturasLessons = collect();
        if ($lecturasTag) {
            $lecturasLessons = Lesson::whereHas('tags', function($query) use ($lecturasTag) {
                    $query->where('tags.id', $lecturasTag->id);
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
            'coreografiaLessons' => $coreografiaLessons,
            'lecturasLessons' => $lecturasLessons,
            'savedLessons' => $savedLessons,
            'trendingCourses' => $trendingCourses,
        ]);
    }
}
