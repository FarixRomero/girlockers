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

class LessonCreate extends Component
{
    use WithFileUploads;

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

    public $video_type = 'bunny'; // Always use Bunny.net

    public $bunny_video_id = '';

    public $is_trial = false;
    public $duration = 0;

    // UI State
    public $thumbnailPreview = null;

    public function mount()
    {
        // Set default instructor if available
        $firstInstructor = Instructor::first();
        if ($firstInstructor) {
            $this->instructor_id = $firstInstructor->id;
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

        // Validar que el video haya sido subido
        if (empty($this->bunny_video_id)) {
            session()->flash('error', 'Debes subir un video antes de guardar');
            return;
        }

        try {
            $lesson = $this->createLesson();
            session()->flash('success', 'Borrador guardado exitosamente');
            return redirect()->route('admin.modules.lessons', $this->module_id);
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    public function publish()
    {
        $this->validate();

        // Validar que el video haya sido subido
        if (empty($this->bunny_video_id)) {
            session()->flash('error', 'Debes subir un video antes de publicar');
            return;
        }

        try {
            $lesson = $this->createLesson();
            session()->flash('success', 'Lección publicada exitosamente');
            return redirect()->route('admin.modules.lessons', $this->module_id);
        } catch (\Exception $e) {
            session()->flash('error', 'Error al publicar: ' . $e->getMessage());
        }
    }

    private function createLesson()
    {
        // Get next order number
        $module = Module::findOrFail($this->module_id);
        $nextOrder = $module->lessons()->max('order') + 1;

        // Handle thumbnail upload
        $thumbnailPath = null;
        if ($this->thumbnail) {
            $thumbnailPath = $this->thumbnail->store('lessons/thumbnails', 'public');
        }

        // Create lesson with Bunny.net video
        $lesson = Lesson::create([
            'title' => $this->title,
            'description' => $this->description,
            'module_id' => $this->module_id,
            'instructor_id' => $this->instructor_id,
            'thumbnail' => $thumbnailPath,
            'video_path' => null,
            'video_type' => 'bunny',
            'youtube_id' => null,
            'bunny_video_id' => $this->bunny_video_id,
            'duration' => $this->duration,
            'is_trial' => $this->is_trial,
            'order' => $nextOrder,
        ]);

        // Attach tags
        if (!empty($this->selectedTags)) {
            $lesson->tags()->attach($this->selectedTags);
        }

        return $lesson;
    }

    public function render()
    {
        $courses = Course::with('modules')->orderBy('title')->get();
        $instructors = Instructor::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        // Get modules for selected course
        $modules = [];
        if ($this->module_id) {
            $module = Module::find($this->module_id);
            if ($module) {
                $modules = Module::where('course_id', $module->course_id)->orderBy('order')->get();
            }
        }

        return view('livewire.admin.lesson-create', [
            'courses' => $courses,
            'modules' => $modules,
            'instructors' => $instructors,
            'tags' => $tags,
        ])->layout('layouts.admin');
    }
}
