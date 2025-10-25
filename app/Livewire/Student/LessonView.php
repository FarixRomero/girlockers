<?php

namespace App\Livewire\Student;

use App\Models\Lesson;
use App\Models\LessonView as LessonViewModel;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

#[Layout('layouts.student')]
class LessonView extends Component
{
    use AuthorizesRequests;

    public Lesson $lesson;
    public $nextLesson = null;
    public $previousLesson = null;
    public $upcomingLessons = [];
    public $similarLessons = [];
    public $isLiked = false;

    public function mount(Lesson $lesson)
    {
        // Authorization check
        $this->authorize('view', $lesson);

        $this->lesson = $lesson;

        // Load relationships
        $this->lesson->load(['module.course', 'module.lessons' => fn($query) => $query->orderBy('order'), 'tags', 'instructor']);

        // Find next and previous lessons
        $this->findAdjacentLessons();

        // Load upcoming lessons from course
        $this->loadUpcomingLessons();

        // Load similar lessons based on tags
        $this->loadSimilarLessons();

        // Check if lesson is liked
        $this->isLiked = $this->lesson->isLikedBy(auth()->user());

        // Record lesson view
        LessonViewModel::recordView(auth()->id(), $lesson->id);
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

    protected function loadUpcomingLessons()
    {
        $allLessons = $this->lesson->module->lessons;
        $currentIndex = $allLessons->search(fn($l) => $l->id === $this->lesson->id);

        if ($currentIndex !== false) {
            // Get next 8 lessons after current one
            $this->upcomingLessons = $allLessons
                ->skip($currentIndex + 1)
                ->take(8)
                ->filter(fn($lesson) => $lesson->isAccessibleBy(auth()->user()))
                ->values();
        }
    }

    protected function loadSimilarLessons()
    {
        $tagIds = $this->lesson->tags->pluck('id');

        if ($tagIds->isEmpty()) {
            // If no tags, get lessons from same instructor
            $this->similarLessons = Lesson::where('instructor_id', $this->lesson->instructor_id)
                ->where('id', '!=', $this->lesson->id)
                ->accessibleBy(auth()->user())
                ->with(['module.course', 'instructor', 'tags'])
                ->inRandomOrder()
                ->limit(8)
                ->get();
        } else {
            // Get lessons with similar tags
            $this->similarLessons = Lesson::whereHas('tags', function($query) use ($tagIds) {
                    $query->whereIn('tags.id', $tagIds);
                })
                ->where('id', '!=', $this->lesson->id)
                ->accessibleBy(auth()->user())
                ->with(['module.course', 'instructor', 'tags'])
                ->withCount(['tags' => function($query) use ($tagIds) {
                    $query->whereIn('tags.id', $tagIds);
                }])
                ->orderByDesc('tags_count')
                ->limit(8)
                ->get();
        }
    }

    public function toggleLike()
    {
        $user = auth()->user();

        if ($this->lesson->isLikedBy($user)) {
            // Unlike
            $this->lesson->likes()->detach($user->id);
            $this->lesson->decrementLikes();
            $this->isLiked = false;
        } else {
            // Like
            $this->lesson->likes()->attach($user->id);
            $this->lesson->incrementLikes();
            $this->isLiked = true;
        }

        // Refresh the lesson to get updated likes count
        $this->lesson->refresh();
    }

    public function render()
    {
        return view('livewire.student.lesson-view')
            ->title($this->lesson->title . ' - ' . $this->lesson->module->course->title);
    }
}
