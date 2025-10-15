<?php

namespace App\Livewire\Student;

use App\Models\Lesson;
use App\Models\Course;
use App\Models\Instructor;
use Livewire\Component;

class GlobalSearch extends Component
{
    public $query = '';
    public $showResults = false;
    public $results = [];

    public function updatedQuery()
    {
        if (strlen($this->query) >= 2) {
            $this->showResults = true;
            $this->search();
        } else {
            $this->showResults = false;
            $this->results = [];
        }
    }

    public function search()
    {
        $user = auth()->user();

        // Search lessons
        $lessons = Lesson::with(['module.course', 'instructor'])
            ->where('title', 'like', '%' . $this->query . '%')
            ->accessibleBy($user)
            ->whereHas('module.course', function ($q) {
                $q->where('is_published', true);
            })
            ->limit(5)
            ->get();

        // Search courses
        $courses = Course::where('is_published', true)
            ->where('title', 'like', '%' . $this->query . '%')
            ->limit(5)
            ->get();

        // Search instructors
        $instructors = Instructor::where('name', 'like', '%' . $this->query . '%')
            ->withCount('lessons')
            ->having('lessons_count', '>', 0)
            ->limit(5)
            ->get();

        $this->results = [
            'lessons' => $lessons,
            'courses' => $courses,
            'instructors' => $instructors,
        ];
    }

    public function closeResults()
    {
        $this->showResults = false;
    }

    public function render()
    {
        return view('livewire.student.global-search');
    }
}
