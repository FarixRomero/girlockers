<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Str;

#[Layout('layouts.admin')]
#[Title('Gestión de Cursos - Admin')]
class CourseManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $filterLevel = 'all';
    public $filterPublished = 'all';

    // Form fields
    public $courseId = null;
    public $title = '';
    public $slug = '';
    public $description = '';
    public $level = 'principiante';
    public $image_path = '';
    public $image;
    public $is_published = false;

    // Modal state
    public $showModal = false;
    public $isEditing = false;

    protected $rules = [
        'title' => 'required|min:3|max:255',
        'slug' => 'required|unique:courses,slug',
        'description' => 'required|min:10',
        'level' => 'required|in:principiante,intermedio,avanzado',
        'image' => 'nullable|image|max:2048',
        'is_published' => 'boolean',
    ];

    public function updatedTitle()
    {
        if (!$this->isEditing) {
            $this->slug = Str::slug($this->title);
        }
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->isEditing = false;
    }

    public function openEditModal($courseId)
    {
        $course = Course::findOrFail($courseId);

        $this->courseId = $course->id;
        $this->title = $course->title;
        $this->slug = $course->slug;
        $this->description = $course->description;
        $this->level = $course->level;
        $this->image_path = $course->image_path;
        $this->is_published = $course->is_published;

        $this->showModal = true;
        $this->isEditing = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'courseId',
            'title',
            'slug',
            'description',
            'level',
            'image_path',
            'image',
            'is_published',
        ]);
        $this->resetValidation();
    }

    public function saveCourse()
    {
        if ($this->isEditing) {
            $this->rules['slug'] = 'required|unique:courses,slug,' . $this->courseId;
        }

        $this->validate();

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'level' => $this->level,
            'is_published' => $this->is_published,
        ];

        if ($this->image) {
            $data['image_path'] = $this->image->store('courses', 'public');
        }

        if ($this->isEditing) {
            $course = Course::findOrFail($this->courseId);
            $course->update($data);
            session()->flash('success', "Curso '{$this->title}' actualizado exitosamente.");
        } else {
            Course::create($data);
            session()->flash('success', "Curso '{$this->title}' creado exitosamente.");
        }

        $this->closeModal();
    }

    public function deleteCourse($courseId)
    {
        $course = Course::withCount(['modules', 'modules.lessons'])->findOrFail($courseId);

        if ($course->modules_count > 0) {
            session()->flash('error', 'No se puede eliminar un curso con módulos. Elimina primero los módulos.');
            return;
        }

        $courseName = $course->title;
        $course->delete();

        session()->flash('success', "Curso '{$courseName}' eliminado exitosamente.");
    }

    public function togglePublished($courseId)
    {
        $course = Course::findOrFail($courseId);
        $course->update(['is_published' => !$course->is_published]);

        $status = $course->is_published ? 'publicado' : 'despublicado';
        session()->flash('success', "Curso '{$course->title}' {$status} exitosamente.");
    }

    public function render()
    {
        $query = Course::withCount('modules')
            ->with(['modules' => function ($query) {
                $query->withCount('lessons');
            }]);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterLevel !== 'all') {
            $query->where('level', $this->filterLevel);
        }

        if ($this->filterPublished !== 'all') {
            $query->where('is_published', $this->filterPublished === 'published');
        }

        $courses = $query->latest()->paginate(10);

        $stats = [
            'total' => Course::count(),
            'published' => Course::where('is_published', true)->count(),
            'draft' => Course::where('is_published', false)->count(),
        ];

        return view('livewire.admin.course-management', [
            'courses' => $courses,
            'stats' => $stats,
        ]);
    }
}
