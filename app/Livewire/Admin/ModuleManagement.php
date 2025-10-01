<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use App\Models\Module;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Gestión de Módulos - Admin')]
class ModuleManagement extends Component
{
    public $courseId;
    public $course;

    // Form fields
    public $moduleId = null;
    public $title = '';
    public $description = '';
    public $order = 1;

    // Modal state
    public $showModal = false;
    public $isEditing = false;

    protected $rules = [
        'title' => 'required|min:3|max:255',
        'description' => 'required|min:10',
        'order' => 'required|integer|min:1',
    ];

    public function mount($courseId)
    {
        $this->courseId = $courseId;
        $this->course = Course::with(['modules' => function ($query) {
            $query->withCount('lessons')->orderBy('order');
        }])->findOrFail($courseId);
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->order = $this->course->modules()->max('order') + 1;
        $this->showModal = true;
        $this->isEditing = false;
    }

    public function openEditModal($moduleId)
    {
        $module = Module::findOrFail($moduleId);

        $this->moduleId = $module->id;
        $this->title = $module->title;
        $this->description = $module->description;
        $this->order = $module->order;

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
        $this->reset(['moduleId', 'title', 'description', 'order']);
        $this->resetValidation();
    }

    public function saveModule()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'order' => $this->order,
            'course_id' => $this->courseId,
        ];

        if ($this->isEditing) {
            $module = Module::findOrFail($this->moduleId);
            $module->update($data);
            session()->flash('success', "Módulo '{$this->title}' actualizado exitosamente.");
        } else {
            Module::create($data);
            session()->flash('success', "Módulo '{$this->title}' creado exitosamente.");
        }

        $this->closeModal();
        $this->refreshCourse();
    }

    public function deleteModule($moduleId)
    {
        $module = Module::withCount('lessons')->findOrFail($moduleId);

        if ($module->lessons_count > 0) {
            session()->flash('error', 'No se puede eliminar un módulo con lecciones. Elimina primero las lecciones.');
            return;
        }

        $moduleName = $module->title;
        $module->delete();

        session()->flash('success', "Módulo '{$moduleName}' eliminado exitosamente.");
        $this->refreshCourse();
    }

    public function moveUp($moduleId)
    {
        $module = Module::findOrFail($moduleId);
        $previousModule = Module::where('course_id', $this->courseId)
            ->where('order', '<', $module->order)
            ->orderBy('order', 'desc')
            ->first();

        if ($previousModule) {
            $tempOrder = $module->order;
            $module->update(['order' => $previousModule->order]);
            $previousModule->update(['order' => $tempOrder]);

            session()->flash('success', "Módulo '{$module->title}' movido hacia arriba.");
            $this->refreshCourse();
        }
    }

    public function moveDown($moduleId)
    {
        $module = Module::findOrFail($moduleId);
        $nextModule = Module::where('course_id', $this->courseId)
            ->where('order', '>', $module->order)
            ->orderBy('order', 'asc')
            ->first();

        if ($nextModule) {
            $tempOrder = $module->order;
            $module->update(['order' => $nextModule->order]);
            $nextModule->update(['order' => $tempOrder]);

            session()->flash('success', "Módulo '{$module->title}' movido hacia abajo.");
            $this->refreshCourse();
        }
    }

    private function refreshCourse()
    {
        $this->course = Course::with(['modules' => function ($query) {
            $query->withCount('lessons')->orderBy('order');
        }])->findOrFail($this->courseId);
    }

    public function render()
    {
        return view('livewire.admin.module-management');
    }
}
