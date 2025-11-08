<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use App\Models\Module;
use App\Livewire\Traits\ModalCrudTrait;
use App\Livewire\Traits\HasOrderableItems;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Gestión de Módulos - Admin')]
class ModuleManagement extends Component
{
    use ModalCrudTrait, HasOrderableItems;

    public $courseId;
    public $course;

    // Form fields
    public $moduleId = null;
    public $title = '';
    public $order = 1;

    protected $rules = [
        'title' => 'required|min:3|max:255',
        'order' => 'required|integer|min:1',
    ];

    public function mount($courseId)
    {
        $this->courseId = $courseId;
        $this->course = Course::with(['modules' => function ($query) {
            $query->withCount('lessons')->orderBy('order');
        }])->findOrFail($courseId);
    }

    /**
     * Override to set default order
     */
    public function openCreateModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->isEditing = false;
        $this->order = $this->course->modules()->max('order') + 1;
    }

    /**
     * Get the model instance for editing
     */
    protected function getModelForEdit($id)
    {
        return Module::findOrFail($id);
    }

    /**
     * Load model data into component properties
     */
    protected function loadModelData($model)
    {
        $this->moduleId = $model->id;
        $this->title = $model->title;
        $this->order = $model->order;
    }

    /**
     * Get the list of form field names to reset
     */
    protected function getFormFields(): array
    {
        return ['moduleId', 'title', 'order'];
    }

    public function saveModule()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
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

    /**
     * Get the model class for orderable items
     */
    protected function getOrderableModel(): string
    {
        return Module::class;
    }

    /**
     * Get the parent column name
     */
    protected function getParentColumn(): string
    {
        return 'course_id';
    }

    /**
     * Get the parent ID
     */
    protected function getParentId(): int
    {
        return $this->courseId;
    }

    /**
     * Reload items after reordering
     */
    protected function reloadItems(): void
    {
        $this->refreshCourse();
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
