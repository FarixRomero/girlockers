<?php

namespace App\Livewire\Student;

use App\Models\Lesson;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Clases - Girls Lockers')]
class LessonCatalog extends Component
{
    use WithPagination;

    #[Url(as: 'buscar')]
    public $search = '';

    #[Url(as: 'nivel')]
    public $selectedLevel = 'all';

    #[Url(as: 'gratis')]
    public $onlyFree = false;

    #[Url(as: 'tag')]
    public $selectedTag = null;

    #[Url(as: 'instructor')]
    public $selectedInstructor = null;

    public $showFilterModal = false;

    public $perPage = 12;

    public function render()
    {
        $user = auth()->user();

        $query = Lesson::with(['module.course', 'tags', 'instructor'])
            ->withCount('likes')
            ->withExists(['likes as is_liked' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->accessibleBy($user);

        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        // Filter by free lessons only
        if ($this->onlyFree) {
            $query->where('is_trial', true);
        }

        // Filter by tag
        if ($this->selectedTag) {
            $query->whereHas('tags', function ($q) {
                $q->where('tags.id', $this->selectedTag);
            });
        }

        // Filter by instructor
        if ($this->selectedInstructor) {
            $query->where('instructor_id', $this->selectedInstructor);
        }

        if ($this->selectedLevel !== 'all') {
            $query->whereHas('module.course', function ($q) {
                $q->where('level', $this->selectedLevel)
                    ->where('is_published', true);
            });
        } else {
            // Filter only published courses
            $query->whereHas('module.course', function ($q) {
                $q->where('is_published', true);
            });
        }

        $lessons = $query->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        // Get all tags and instructors for the filter
        $tags = \App\Models\Tag::orderBy('name')->get();
        $instructors = \App\Models\Instructor::withCount('lessons')
            ->having('lessons_count', '>', 0)
            ->orderBy('name')
            ->get();

        return view('livewire.student.lesson-catalog', [
            'lessons' => $lessons,
            'tags' => $tags,
            'instructors' => $instructors,
        ]);
    }

    public function filterByLevel($level)
    {
        $this->selectedLevel = $level;
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedOnlyFree()
    {
        $this->resetPage();
    }

    public function updatedSelectedTag()
    {
        $this->resetPage();
    }

    public function updatedSelectedInstructor()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->selectedTag = null;
        $this->selectedInstructor = null;
        $this->selectedLevel = 'all';
        $this->onlyFree = false;
        $this->search = '';
        $this->resetPage();
    }

    public function toggleLike($lessonId)
    {
        $user = auth()->user();
        $lesson = Lesson::findOrFail($lessonId);

        if ($lesson->isLikedBy($user)) {
            // Remove like
            $user->likes()->detach($lessonId);
            $lesson->decrementLikes();
        } else {
            // Add like
            $user->likes()->attach($lessonId, ['created_at' => now()]);
            $lesson->incrementLikes();
        }
    }

    public function loadMore()
    {
        $this->perPage += 12;
    }
}
