<?php

namespace App\Livewire\Student;

use App\Models\Course;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Cursos - Girls Lockers')]
class CourseCatalog extends Component
{
    use WithPagination;

    #[Url(as: 'nivel')]
    public $selectedLevel = 'all';

    #[Url(as: 'instructor')]
    public $selectedInstructor = null;

    #[Url(as: 'buscar')]
    public $search = '';

    public $showFilterModal = false;

    public $perPage = 12;

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
            ->paginate($this->perPage);

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
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectedInstructor()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->selectedInstructor = null;
        $this->selectedLevel = 'all';
        $this->search = '';
        $this->resetPage();
    }

    public function loadMore()
    {
        $this->perPage += 12;
    }
}
