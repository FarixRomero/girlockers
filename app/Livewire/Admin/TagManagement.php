<?php

namespace App\Livewire\Admin;

use App\Models\Tag;
use App\Livewire\Traits\ModalCrudTrait;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Str;

#[Layout('layouts.admin')]
#[Title('Gestión de Tags - Admin')]
class TagManagement extends Component
{
    use WithPagination, ModalCrudTrait;

    public $search = '';

    // Form fields
    public $tagId = null;
    public $name = '';
    public $slug = '';

    protected $rules = [
        'name' => 'required|min:3|max:255',
        'slug' => 'required|min:3|max:255|unique:tags,slug',
    ];

    public function updatedName()
    {
        $this->slug = Str::slug($this->name);
    }

    /**
     * Get the model instance for editing
     */
    protected function getModelForEdit($id)
    {
        return Tag::findOrFail($id);
    }

    /**
     * Load model data into component properties
     */
    protected function loadModelData($model)
    {
        $this->tagId = $model->id;
        $this->name = $model->name;
        $this->slug = $model->slug;
    }

    /**
     * Get the list of form field names to reset
     */
    protected function getFormFields(): array
    {
        return ['tagId', 'name', 'slug'];
    }

    public function saveTag()
    {
        $this->slug = Str::slug($this->name);

        $rules = $this->rules;
        if ($this->isEditing) {
            $rules['slug'] = 'required|min:3|max:255|unique:tags,slug,' . $this->tagId;
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
        ];

        if ($this->isEditing) {
            $tag = Tag::findOrFail($this->tagId);
            $tag->update($data);
            session()->flash('success', "Tag '{$this->name}' actualizado exitosamente.");
        } else {
            Tag::create($data);
            session()->flash('success', "Tag '{$this->name}' creado exitosamente.");
        }

        $this->closeModal();
    }

    public function deleteTag($tagId)
    {
        $tag = Tag::withCount('lessons')->findOrFail($tagId);

        if ($tag->lessons_count > 0) {
            session()->flash('error', 'No se puede eliminar un tag con lecciones asignadas.');
            return;
        }

        $tagName = $tag->name;
        $tag->delete();

        session()->flash('success', "Tag '{$tagName}' eliminado exitosamente.");
    }

    public function render()
    {
        $query = Tag::withCount('lessons');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('slug', 'like', '%' . $this->search . '%');
            });
        }

        $tags = $query->latest()->paginate(15);

        $stats = [
            'total' => Tag::count(),
            'with_lessons' => Tag::has('lessons')->count(),
        ];

        return view('livewire.admin.tag-management', [
            'tags' => $tags,
            'stats' => $stats,
        ]);
    }
}
