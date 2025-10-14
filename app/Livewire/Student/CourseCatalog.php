<?php

namespace App\Livewire\Student;

use App\Models\Course;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;

#[Layout('layouts.student')]
#[Title('Cursos - Girls Lockers')]
class CourseCatalog extends Component
{
    #[Url(as: 'nivel')]
    public $selectedLevel = 'all';

    #[Url(as: 'instructor')]
    public $selectedInstructor = null;

    #[Url(as: 'buscar')]
    public $search = '';

    public $showFilterModal = false;

    public function mount()
    {
        // Los parámetros URL se cargan automáticamente gracias a #[Url]
    }

    public function render()
    {
        $query = Course::where('is_published', true)
            ->withCount('modules')
            ->with(['modules' => function ($query) {
                $query->withCount('lessons');
            }]);

        // Filtro por búsqueda
        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        // Filtro por nivel
        if ($this->selectedLevel !== 'all') {
            $query->where('level', $this->selectedLevel);
        }

        // Filtro por instructor (cursos que tienen lecciones de este instructor)
        if ($this->selectedInstructor) {
            $query->whereHas('modules.lessons', function ($q) {
                $q->where('instructor_id', $this->selectedInstructor);
            });
        }

        $courses = $query->orderBy('level')
            ->orderBy('title')
            ->get();

        // Obtener instructores para el modal de filtros
        $instructors = \App\Models\Instructor::withCount('lessons')
            ->having('lessons_count', '>', 0)
            ->orderBy('name')
            ->get();

        return view('livewire.student.course-catalog', [
            'courses' => $courses,
            'instructors' => $instructors,
        ]);
    }

    public function filterByLevel($level)
    {
        $this->selectedLevel = $level;
    }

    public function updatingSearch()
    {
        // Se ejecuta cuando cambia el search
    }

    public function clearFilters()
    {
        $this->selectedInstructor = null;
        $this->selectedLevel = 'all';
        $this->search = '';
    }
}
