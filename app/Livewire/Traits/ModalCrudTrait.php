<?php

namespace App\Livewire\Traits;

trait ModalCrudTrait
{
    public $showModal = false;
    public $isEditing = false;

    /**
     * Open the modal for creating a new record
     */
    public function openCreateModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->isEditing = false;
    }

    /**
     * Open the modal for editing an existing record
     */
    public function openEditModal($id)
    {
        $model = $this->getModelForEdit($id);
        $this->loadModelData($model);
        $this->showModal = true;
        $this->isEditing = true;
    }

    /**
     * Close the modal and reset form
     */
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    /**
     * Reset form fields and validation
     */
    public function resetForm()
    {
        $this->reset($this->getFormFields());
        $this->resetValidation();
    }

    /**
     * Get the model instance for editing
     * Must be implemented by the component
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    abstract protected function getModelForEdit($id);

    /**
     * Load model data into component properties
     * Must be implemented by the component
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    abstract protected function loadModelData($model);

    /**
     * Get the list of form field names to reset
     * Must be implemented by the component
     *
     * @return array
     */
    abstract protected function getFormFields(): array;
}
