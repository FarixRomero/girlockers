<?php

namespace App\Livewire\Admin;

use App\Models\Instructor;
use App\Services\FileUploadService;
use App\Livewire\Traits\ModalCrudTrait;
use App\Livewire\Traits\HasSearchableQueries;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Gestión de Instructores - Admin')]
class InstructorManagement extends Component
{
    use WithPagination, WithFileUploads, ModalCrudTrait, HasSearchableQueries;

    // Form fields
    public $instructorId = null;
    public $name = '';
    public $description = '';
    public $instagram = '';
    public $avatar;
    public $existingAvatar = '';

    protected $rules = [
        'name' => 'required|min:3|max:255',
        'description' => 'nullable|string',
        'instagram' => 'nullable|string|max:255',
        'avatar' => 'nullable|image|max:2048',
    ];

    /**
     * Get the model instance for editing
     */
    protected function getModelForEdit($id)
    {
        return Instructor::findOrFail($id);
    }

    /**
     * Load model data into component properties
     */
    protected function loadModelData($model)
    {
        $this->instructorId = $model->id;
        $this->name = $model->name;
        $this->description = $model->description ?? '';
        $this->instagram = $model->instagram ?? '';
        $this->existingAvatar = $model->avatar;
    }

    /**
     * Get the list of form field names to reset
     */
    protected function getFormFields(): array
    {
        return [
            'instructorId',
            'name',
            'description',
            'instagram',
            'existingAvatar',
            'avatar',
        ];
    }

    public function saveInstructor()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'instagram' => $this->instagram,
        ];

        // Handle avatar upload using FileUploadService
        if ($this->avatar) {
            $fileUploadService = app(FileUploadService::class);
            $data['avatar'] = $fileUploadService->uploadImage(
                $this->avatar,
                'instructors',
                $this->isEditing ? $this->existingAvatar : null
            );
        }

        if ($this->isEditing) {
            $instructor = Instructor::findOrFail($this->instructorId);
            $instructor->update($data);
            session()->flash('success', "Instructor '{$this->name}' actualizado exitosamente.");
        } else {
            Instructor::create($data);
            session()->flash('success', "Instructor '{$this->name}' creado exitosamente.");
        }

        $this->closeModal();
    }

    public function deleteInstructor($instructorId)
    {
        $instructor = Instructor::withCount('lessons')->findOrFail($instructorId);

        if ($instructor->lessons_count > 0) {
            session()->flash('error', 'No se puede eliminar un instructor con lecciones asignadas.');
            return;
        }

        $instructorName = $instructor->name;
        $instructor->delete();

        session()->flash('success', "Instructor '{$instructorName}' eliminado exitosamente.");
    }

    public function render()
    {
        $query = Instructor::withCount('lessons');

        // Apply search using HasSearchableQueries trait
        $query = $this->applySearch($query, ['name', 'description', 'instagram']);

        $instructors = $query->latest()->paginate(10);

        $stats = [
            'total' => Instructor::count(),
            'with_lessons' => Instructor::has('lessons')->count(),
        ];

        return view('livewire.admin.instructor-management', [
            'instructors' => $instructors,
            'stats' => $stats,
        ]);
    }
}
