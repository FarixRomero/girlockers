<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use App\Models\Module;
use App\Models\Instructor;
use App\Models\Tag;
use App\Services\LessonService;
use App\Livewire\Forms\LessonForm;
use Livewire\Component;
use Livewire\WithFileUploads;

class LessonCreate extends Component
{
    use WithFileUploads;

    public LessonForm $form;

    public function mount($moduleId = null)
    {
        // Si viene un moduleId, pre-seleccionarlo
        if ($moduleId) {
            $this->form->module_id = $moduleId;
        }

        // Set default instructor if available
        $firstInstructor = Instructor::first();
        if ($firstInstructor) {
            $this->form->instructor_id = $firstInstructor->id;
        }
    }

    public function updatedFormThumbnail()
    {
        $this->validateOnly('form.thumbnail');
        $this->form->updateThumbnailPreview();
    }

    public function saveDraft()
    {
        $this->form->validate();

        // Validar que el video haya sido subido
        if (empty($this->form->bunny_video_id)) {
            session()->flash('error', 'Debes subir un video antes de guardar');
            return;
        }

        try {
            $lessonService = app(LessonService::class);
            $lesson = $lessonService->createLesson(
                $this->form->getData(false), // false = no publicar
                $this->form->selectedTags,
                $this->form->thumbnail
            );

            session()->flash('success', 'Borrador guardado exitosamente');
            return redirect()->route('admin.modules.lessons', $this->form->module_id);
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    public function publish()
    {
        $this->form->validate();

        // Validar que el video haya sido subido
        if (empty($this->form->bunny_video_id)) {
            session()->flash('error', 'Debes subir un video antes de publicar');
            return;
        }

        try {
            $lessonService = app(LessonService::class);
            $lesson = $lessonService->createLesson(
                $this->form->getData(true), // true = publicar
                $this->form->selectedTags,
                $this->form->thumbnail
            );

            session()->flash('success', 'Lección publicada exitosamente');
            return redirect()->route('admin.modules.lessons', $this->form->module_id);
        } catch (\Exception $e) {
            session()->flash('error', 'Error al publicar: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $courses = Course::with('modules')->orderBy('title')->get();
        $instructors = Instructor::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        // Get modules for selected course
        $modules = [];
        if ($this->form->module_id) {
            $module = Module::find($this->form->module_id);
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
