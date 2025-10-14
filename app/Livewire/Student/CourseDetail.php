<?php

namespace App\Livewire\Student;

use App\Models\Course;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.student')]
class CourseDetail extends Component
{
    public Course $course;
    public $expandedModules = [];

    public function mount(Course $course)
    {
        // Abort if course is not published and user is not admin
        if (!$course->is_published && !auth()->user()?->isAdmin()) {
            abort(404);
        }

        $this->course = $course;

        // Expand first module by default
        if ($course->modules->isNotEmpty()) {
            $this->expandedModules[] = $course->modules->first()->id;
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
        // Eager load modules with lessons
        $this->course->load([
            'modules' => fn($query) => $query->orderBy('order'),
            'modules.lessons' => fn($query) => $query->orderBy('order'),
        ]);

        return view('livewire.student.course-detail')
            ->title($this->course->title . ' - Girls Lockers');
    }
}
