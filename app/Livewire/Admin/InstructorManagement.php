<?php

namespace App\Livewire\Admin;

use App\Models\Instructor;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Gestión de Instructores - Admin')]
class InstructorManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';

    // Form fields
    public $instructorId = null;
    public $name = '';
    public $description = '';
    public $instagram = '';
    public $avatar;
    public $existingAvatar = '';

    // Modal state
    public $showModal = false;
    public $isEditing = false;

    protected $rules = [
        'name' => 'required|min:3|max:255',
        'description' => 'nullable|string',
        'instagram' => 'nullable|string|max:255',
        'avatar' => 'nullable|image|max:2048',
    ];

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->isEditing = false;
    }

    public function openEditModal($instructorId)
    {
        $instructor = Instructor::findOrFail($instructorId);

        $this->instructorId = $instructor->id;
        $this->name = $instructor->name;
        $this->description = $instructor->description ?? '';
        $this->instagram = $instructor->instagram ?? '';
        $this->existingAvatar = $instructor->avatar;

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
            'instructorId',
            'name',
            'description',
            'instagram',
            'existingAvatar',
            'avatar',
        ]);
        $this->resetValidation();
    }

    public function saveInstructor()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'instagram' => $this->instagram,
        ];

        if ($this->avatar) {
            $data['avatar'] = $this->avatar->store('instructors', 'public');
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

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('instagram', 'like', '%' . $this->search . '%');
            });
        }

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
