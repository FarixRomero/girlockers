<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Instructor;
use App\Models\Tag;
use App\Services\LessonService;
use App\Livewire\Forms\LessonForm;
use Livewire\Component;
use Livewire\WithFileUploads;

class LessonEdit extends Component
{
    use WithFileUploads;

    public LessonForm $form;
    public Lesson $lesson;
    public int $lessonId;

    public function mount($lessonId)
    {
        $this->lessonId = $lessonId;
        $this->lesson = Lesson::with(['module.course', 'tags'])->findOrFail($lessonId);

        // Load lesson data into form
        $this->form->setLesson($this->lesson);
    }

    public function updatedFormThumbnail()
    {
        $this->validateOnly('form.thumbnail');
        $this->form->updateThumbnailPreview();
    }

    public function saveDraft()
    {
        $this->form->validate();

        try {
            $lessonService = app(LessonService::class);
            $lessonService->updateLesson(
                $this->lesson,
                $this->form->getData(false), // false = no publicar
                $this->form->selectedTags,
                $this->form->thumbnail,
                $this->form->existingThumbnail
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

        try {
            $lessonService = app(LessonService::class);
            $lessonService->updateLesson(
                $this->lesson,
                $this->form->getData(true), // true = publicar
                $this->form->selectedTags,
                $this->form->thumbnail,
                $this->form->existingThumbnail
            );

            session()->flash('success', 'Lección actualizada y publicada exitosamente');
            return redirect()->route('admin.modules.lessons', $this->form->module_id);
        } catch (\Exception $e) {
            session()->flash('error', 'Error al publicar: ' . $e->getMessage());
        }
    }

    /**
     * Save only duration to database (called when auto-fetched from Bunny.net)
     */
    public function saveDuration()
    {
        try {
            // Calculate total duration in seconds
            $totalDuration = ($this->form->duration_minutes * 60) + $this->form->duration_seconds;

            // Update only the duration field
            $this->lesson->update([
                'duration' => $totalDuration
            ]);

            \Log::info("Duración guardada automáticamente para lección {$this->lesson->id}: {$totalDuration} segundos");

            return true;
        } catch (\Exception $e) {
            \Log::error("Error al guardar duración automáticamente: {$e->getMessage()}");
            return false;
        }
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
