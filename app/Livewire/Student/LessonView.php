<?php

namespace App\Livewire\Student;

use App\Models\Lesson;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

#[Layout('layouts.app')]
class LessonView extends Component
{
    use AuthorizesRequests;

    public Lesson $lesson;
    public $nextLesson = null;
    public $previousLesson = null;

    public function mount(Lesson $lesson)
    {
        // Authorization check
        $this->authorize('view', $lesson);

        $this->lesson = $lesson;

        // Load relationships
        $this->lesson->load(['module.course', 'module.lessons' => fn($query) => $query->orderBy('order')]);

        // Find next and previous lessons
        $this->findAdjacentLessons();
    }

    protected function findAdjacentLessons()
    {
        $allLessons = $this->lesson->module->lessons;
        $currentIndex = $allLessons->search(fn($l) => $l->id === $this->lesson->id);

        if ($currentIndex !== false) {
            // Next lesson
            if ($currentIndex < $allLessons->count() - 1) {
                $nextLesson = $allLessons[$currentIndex + 1];
                if ($nextLesson->isAccessibleBy(auth()->user())) {
                    $this->nextLesson = $nextLesson;
                }
            }

            // Previous lesson
            if ($currentIndex > 0) {
                $previousLesson = $allLessons[$currentIndex - 1];
                if ($previousLesson->isAccessibleBy(auth()->user())) {
                    $this->previousLesson = $previousLesson;
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.student.lesson-view')
            ->title($this->lesson->title . ' - ' . $this->lesson->module->course->title);
    }
}
