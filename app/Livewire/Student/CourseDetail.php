<?php

namespace App\Livewire\Student;

use App\Models\Course;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
class CourseDetail extends Component
{
    public Course $course;
    public $expandedModules = [];
    public string $activeTab = 'classes';
    public bool $isFavorited = false;

    public function mount(Course $course)
    {
        // Abort if course is not published and user is not admin
        if (!$course->is_published && !auth()->user()?->isAdmin()) {
            abort(404);
        }

        $this->course = $course;

        // Check if course is favorited
        $this->isFavorited = $course->isFavoritedBy(auth()->user());

        // Expand first module by default
        if ($course->modules->isNotEmpty()) {
            $this->expandedModules[] = $course->modules->first()->id;
        }
    }

    public function toggleFavorite()
    {
        $user = auth()->user();

        if ($this->isFavorited) {
            // Remove from favorites
            $user->favoriteCourses()->detach($this->course->id);
            $this->isFavorited = false;
        } else {
            // Add to favorites
            $user->favoriteCourses()->attach($this->course->id);
            $this->isFavorited = true;
        }
    }

    public function toggleModule($moduleId)
    {
        if (in_array($moduleId, $this->expandedModules)) {
            $this->expandedModules = array_diff($this->expandedModules, [$moduleId]);
        } else {
            $this->expandedModules[] = $moduleId;
        }
    }

    public function render()
    {
        // Eager load modules with lessons and instructor
        $this->course->load([
            'modules' => fn($query) => $query->orderBy('order'),
            'modules.lessons' => fn($query) => $query->orderBy('order'),
            'instructor',
        ]);

        // Calculate progress statistics based on completed lessons
        $allLessonIds = $this->course->modules->flatMap->lessons->pluck('id');
        $completedLessonIds = auth()->user()->completedLessons()
            ->whereIn('lessons.id', $allLessonIds)
            ->pluck('lessons.id');

        $totalLessons = $allLessonIds->count();
        $completedLessonsCount = $completedLessonIds->count();
        $completionPercentage = $totalLessons > 0 ? round(($completedLessonsCount / $totalLessons) * 100) : 0;

        // Calculate total minutes spent on completed lessons
        // Note: duration is stored in seconds, so we divide by 60
        $totalSeconds = auth()->user()->completedLessons()
            ->whereIn('lessons.id', $allLessonIds)
            ->sum('lessons.duration') ?? 0;
        $minutesSpent = round($totalSeconds / 60);

        // Get completed lessons for each module
        $moduleCompletionData = [];
        foreach ($this->course->modules as $module) {
            $moduleLessonIds = $module->lessons->pluck('id');
            $moduleCompletedCount = $completedLessonIds->intersect($moduleLessonIds)->count();
            $moduleCompletionData[$module->id] = [
                'total' => $moduleLessonIds->count(),
                'completed' => $moduleCompletedCount,
                'isFullyCompleted' => $moduleCompletedCount === $moduleLessonIds->count() && $moduleLessonIds->count() > 0,
            ];
        }

        // Find next lesson to continue (first incomplete lesson)
        $nextLesson = null;
        foreach ($this->course->modules as $module) {
            foreach ($module->lessons as $lesson) {
                if (!$completedLessonIds->contains($lesson->id) && $lesson->isAccessibleBy(auth()->user())) {
                    $nextLesson = $lesson;
                    break 2;
                }
            }
        }

        return view('livewire.student.course-detail', [
            'completionPercentage' => $completionPercentage,
            'completedLessons' => $completedLessonsCount,
            'totalLessons' => $totalLessons,
            'minutesSpent' => $minutesSpent,
            'completedLessonIds' => $completedLessonIds,
            'moduleCompletionData' => $moduleCompletionData,
            'nextLesson' => $nextLesson,
        ])->title($this->course->title . ' - Girls Lockers');
    }
}
