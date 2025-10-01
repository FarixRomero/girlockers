<?php

namespace App\Livewire\Student;

use App\Models\Course;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Cursos - Girl Lockers')]
class CourseCatalog extends Component
{
    public $selectedLevel = 'all';

    public function render()
    {
        $query = Course::where('is_published', true)
            ->withCount('modules')
            ->with(['modules' => function ($query) {
                $query->withCount('lessons');
            }]);

        if ($this->selectedLevel !== 'all') {
            $query->where('level', $this->selectedLevel);
        }

        $courses = $query->orderBy('level')
            ->orderBy('title')
            ->get();

        return view('livewire.student.course-catalog', [
            'courses' => $courses,
        ]);
    }

    public function filterByLevel($level)
    {
        $this->selectedLevel = $level;
    }
}
