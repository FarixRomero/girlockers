<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\Instructor;
use App\Models\Tag;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;

class LessonEdit extends Component
{
    use WithFileUploads;

    public $lessonId;
    public $lesson;

    // Form fields
    #[Validate('required|string|max:255')]
    public $title = '';

    #[Validate('nullable|string')]
    public $description = '';

    #[Validate('required|exists:modules,id')]
    public $module_id = '';

    #[Validate('required|exists:instructors,id')]
    public $instructor_id = '';

    #[Validate('nullable|array')]
    public $selectedTags = [];

    #[Validate('nullable|image|max:10240')] // 10MB max
    public $thumbnail;

    public $video_type = 'bunny';
    public $bunny_video_id = '';
    public $is_trial = false;
    public $is_published = true;
    public $duration = 0;

    // UI State
    public $thumbnailPreview = null;
    public $existingThumbnail = null;

    public function mount($lessonId)
    {
        $this->lessonId = $lessonId;
        $this->lesson = Lesson::with(['module.course', 'tags'])->findOrFail($lessonId);

        // Cargar datos de la lección
        $this->title = $this->lesson->title;
        $this->description = $this->lesson->description;
        $this->module_id = $this->lesson->module_id;
        $this->instructor_id = $this->lesson->instructor_id;
        $this->bunny_video_id = $this->lesson->bunny_video_id ?? '';
        $this->video_type = $this->lesson->video_type;
        $this->duration = $this->lesson->duration;
        $this->is_trial = $this->lesson->is_trial;
        $this->is_published = $this->lesson->is_published;
        $this->selectedTags = $this->lesson->tags->pluck('id')->toArray();

        // Guardar thumbnail existente
        if ($this->lesson->thumbnail) {
            $this->existingThumbnail = $this->lesson->thumbnail;
            $this->thumbnailPreview = asset('storage/' . $this->lesson->thumbnail);
        }
    }

    public function updatedThumbnail()
    {
        $this->validate(['thumbnail' => 'nullable|image|max:10240']);
        if ($this->thumbnail) {
            $this->thumbnailPreview = $this->thumbnail->temporaryUrl();
        }
    }

    public function saveDraft()
    {
        $this->validate();

        try {
            $this->updateLesson(false); // false = no publicar
            session()->flash('success', 'Borrador guardado exitosamente');
            return redirect()->route('admin.modules.lessons', $this->module_id);
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    public function publish()
    {
        $this->validate();

        try {
            $this->updateLesson(true); // true = publicar
            session()->flash('success', 'Lección actualizada y publicada exitosamente');
            return redirect()->route('admin.modules.lessons', $this->module_id);
        } catch (\Exception $e) {
            session()->flash('error', 'Error al publicar: ' . $e->getMessage());
        }
    }

    private function updateLesson($isPublished = true)
    {
        // Handle thumbnail upload
        $thumbnailPath = $this->existingThumbnail;
        if ($this->thumbnail) {
            // Delete old thumbnail if exists
            if ($this->existingThumbnail && file_exists(storage_path('app/public/' . $this->existingThumbnail))) {
                unlink(storage_path('app/public/' . $this->existingThumbnail));
            }
            $thumbnailPath = $this->thumbnail->store('lessons/thumbnails', 'public');
        }

        // Update lesson
        $this->lesson->update([
            'title' => $this->title,
            'description' => $this->description,
            'module_id' => $this->module_id,
            'instructor_id' => $this->instructor_id,
            'thumbnail' => $thumbnailPath,
            'bunny_video_id' => $this->bunny_video_id,
            'duration' => $this->duration,
            'is_trial' => $this->is_trial,
            'is_published' => $isPublished,
        ]);

        // Sync tags
        $this->lesson->tags()->sync($this->selectedTags);

        return $this->lesson;
    }

    public function render()
    {
        $courses = Course::with('modules')->orderBy('title')->get();
        $instructors = Instructor::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('livewire.admin.lesson-edit', [
            'courses' => $courses,
            'instructors' => $instructors,
            'tags' => $tags,
        ])->layout('layouts.admin');
    }
}
