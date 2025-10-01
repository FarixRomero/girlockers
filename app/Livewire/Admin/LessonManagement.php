<?php

namespace App\Livewire\Admin;

use App\Models\Module;
use App\Models\Lesson;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Gestión de Lecciones - Admin')]
class LessonManagement extends Component
{
    use WithFileUploads;

    public $moduleId;
    public $module;

    // Form fields
    public $lessonId = null;
    public $title = '';
    public $description = '';
    public $video_type = 'youtube'; // youtube or local
    public $youtube_id = '';
    public $video_file;
    public $video_path = '';
    public $duration = 0;
    public $order = 1;
    public $is_trial = false;

    // Modal state
    public $showModal = false;
    public $isEditing = false;

    protected function rules()
    {
        $rules = [
            'title' => 'required|min:3|max:255',
            'description' => 'required|min:10',
            'video_type' => 'required|in:youtube,local',
            'duration' => 'nullable|integer|min:0',
            'order' => 'required|integer|min:1',
            'is_trial' => 'boolean',
        ];

        if ($this->video_type === 'youtube') {
            $rules['youtube_id'] = 'required|string|max:20';
        } elseif (!$this->isEditing || $this->video_file) {
            $rules['video_file'] = 'required|file|mimes:mp4,mov,avi,wmv|max:512000'; // 500MB max
        }

        return $rules;
    }

    public function mount($moduleId)
    {
        $this->moduleId = $moduleId;
        $this->module = Module::with(['course', 'lessons' => function ($query) {
            $query->orderBy('order');
        }])->findOrFail($moduleId);
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->order = $this->module->lessons()->max('order') + 1;
        $this->showModal = true;
        $this->isEditing = false;
    }

    public function openEditModal($lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);

        $this->lessonId = $lesson->id;
        $this->title = $lesson->title;
        $this->description = $lesson->description;
        $this->video_type = $lesson->video_type;
        $this->youtube_id = $lesson->youtube_id ?? '';
        $this->video_path = $lesson->video_path ?? '';
        $this->duration = $lesson->duration;
        $this->order = $lesson->order;
        $this->is_trial = $lesson->is_trial;

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
            'lessonId',
            'title',
            'description',
            'video_type',
            'youtube_id',
            'video_file',
            'video_path',
            'duration',
            'order',
            'is_trial',
        ]);
        $this->resetValidation();
    }

    public function saveLesson()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'video_type' => $this->video_type,
            'duration' => $this->duration,
            'order' => $this->order,
            'is_trial' => $this->is_trial,
            'module_id' => $this->moduleId,
        ];

        if ($this->video_type === 'youtube') {
            $data['youtube_id'] = $this->youtube_id;
            $data['video_path'] = null;
        } elseif ($this->video_file) {
            $data['video_path'] = $this->video_file->store('lessons', 'public');
            $data['youtube_id'] = null;
        }

        if ($this->isEditing) {
            $lesson = Lesson::findOrFail($this->lessonId);
            $lesson->update($data);
            session()->flash('success', "Lección '{$this->title}' actualizada exitosamente.");
        } else {
            Lesson::create($data);
            session()->flash('success', "Lección '{$this->title}' creada exitosamente.");
        }

        $this->closeModal();
        $this->refreshModule();
    }

    public function deleteLesson($lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        $lessonName = $lesson->title;

        // Delete video file if it's a local video
        if ($lesson->video_type === 'local' && $lesson->video_path) {
            \Storage::disk('public')->delete($lesson->video_path);
        }

        $lesson->delete();

        session()->flash('success', "Lección '{$lessonName}' eliminada exitosamente.");
        $this->refreshModule();
    }

    public function moveUp($lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        $previousLesson = Lesson::where('module_id', $this->moduleId)
            ->where('order', '<', $lesson->order)
            ->orderBy('order', 'desc')
            ->first();

        if ($previousLesson) {
            $tempOrder = $lesson->order;
            $lesson->update(['order' => $previousLesson->order]);
            $previousLesson->update(['order' => $tempOrder]);

            session()->flash('success', "Lección '{$lesson->title}' movida hacia arriba.");
            $this->refreshModule();
        }
    }

    public function moveDown($lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        $nextLesson = Lesson::where('module_id', $this->moduleId)
            ->where('order', '>', $lesson->order)
            ->orderBy('order', 'asc')
            ->first();

        if ($nextLesson) {
            $tempOrder = $lesson->order;
            $lesson->update(['order' => $nextLesson->order]);
            $nextLesson->update(['order' => $tempOrder]);

            session()->flash('success', "Lección '{$lesson->title}' movida hacia abajo.");
            $this->refreshModule();
        }
    }

    public function toggleTrial($lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        $lesson->update(['is_trial' => !$lesson->is_trial]);

        $status = $lesson->is_trial ? 'trial' : 'premium';
        session()->flash('success', "Lección '{$lesson->title}' marcada como {$status}.");
        $this->refreshModule();
    }

    private function refreshModule()
    {
        $this->module = Module::with(['course', 'lessons' => function ($query) {
            $query->orderBy('order');
        }])->findOrFail($this->moduleId);
    }

    public function render()
    {
        return view('livewire.admin.lesson-management');
    }
}
