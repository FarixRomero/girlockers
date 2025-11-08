<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use App\Models\Instructor;
use App\Services\NotificationService;
use App\Services\FileUploadService;
use App\Livewire\Traits\ModalCrudTrait;
use App\Livewire\Traits\HasSearchableQueries;
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
    use WithPagination, WithFileUploads, ModalCrudTrait, HasSearchableQueries;
    public $filterLevel = 'all';
    public $filterPublished = 'all';

    // Form fields
    public $courseId = null;
    public $title = '';
    public $slug = '';
    public $description = '';
    public $instructor_id = null;
    public $level = 'principiante';
    public $existingImage = '';
    public $image;
    public $is_published = false;

    protected $rules = [
        'title' => 'required|min:3|max:255',
        'description' => 'required|min:10',
        'instructor_id' => 'nullable|exists:instructors,id',
        'level' => 'required|in:principiante,intermedio,avanzado',
        'image' => 'nullable|image|max:10240',
        'is_published' => 'boolean',
    ];

    public function updatedTitle()
    {
        // Generar slug automáticamente cada vez que cambia el título
        $this->slug = Str::slug($this->title);
    }

    /**
     * Get the model instance for editing
     */
    protected function getModelForEdit($id)
    {
        return Course::findOrFail($id);
    }

    /**
     * Load model data into component properties
     */
    protected function loadModelData($model)
    {
        $this->courseId = $model->id;
        $this->title = $model->title;
        $this->slug = $model->slug;
        $this->description = $model->description;
        $this->instructor_id = $model->instructor_id;
        $this->level = $model->level;
        $this->existingImage = $model->image;
        $this->is_published = $model->is_published;
    }

    /**
     * Get the list of form field names to reset
     */
    protected function getFormFields(): array
    {
        return [
            'courseId',
            'title',
            'slug',
            'description',
            'instructor_id',
            'level',
            'existingImage',
            'image',
            'is_published',
        ];
    }

    public function saveCourse()
    {
        // Generar slug antes de validar
        $this->slug = Str::slug($this->title);

        $this->validate();

        // Verificar unicidad del slug
        $slugExists = Course::where('slug', $this->slug)
            ->when($this->isEditing, fn($q) => $q->where('id', '!=', $this->courseId))
            ->exists();

        if ($slugExists) {
            $this->addError('title', 'Ya existe un curso con un título similar.');
            return;
        }

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'instructor_id' => $this->instructor_id,
            'level' => $this->level,
            'is_published' => $this->is_published,
        ];

        // Handle image upload using FileUploadService
        if ($this->image) {
            $fileUploadService = app(FileUploadService::class);
            $data['image'] = $fileUploadService->uploadImage(
                $this->image,
                'courses',
                $this->isEditing ? $this->existingImage : null
            );
        }

        if ($this->isEditing) {
            $course = Course::findOrFail($this->courseId);
            $course->update($data);
            session()->flash('success', "Curso '{$this->title}' actualizado exitosamente.");
        } else {
            $course = Course::create($data);

            // Send notifications to users about new course
            $notificationService = new NotificationService();
            $notificationService->notifyNewCourse($course);

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
            }, 'instructor']);

        // Apply search using HasSearchableQueries trait
        $query = $this->applySearch($query, ['title', 'description']);

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

        $instructors = Instructor::orderBy('name')->get();

        return view('livewire.admin.course-management', [
            'courses' => $courses,
            'stats' => $stats,
            'instructors' => $instructors,
        ]);
    }
}
