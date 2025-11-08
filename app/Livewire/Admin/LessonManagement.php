<?php

namespace App\Livewire\Admin;

use App\Models\Lesson;
use App\Models\Module;
use App\Livewire\Traits\HasOrderableItems;
use Livewire\Component;
use Livewire\Attributes\On;

class LessonManagement extends Component
{
    use HasOrderableItems;
    public Module $module;
    public $courseTitle;
    public $lessonsCount;

    public function mount($moduleId)
    {
        $this->module = Module::with([
            'course:id,title',
            'lessons' => function ($query) {
                $query->with(['instructor:id,name', 'tags:id,name'])
                    ->orderBy('order');
            }
        ])->findOrFail($moduleId);

        $this->courseTitle = $this->module->course->title;
        $this->lessonsCount = $this->module->lessons->count();
    }

    /**
     * Get the model class for orderable items
     */
    protected function getOrderableModel(): string
    {
        return Lesson::class;
    }

    /**
     * Get the parent column name
     */
    protected function getParentColumn(): string
    {
        return 'module_id';
    }

    /**
     * Get the parent ID
     */
    protected function getParentId(): int
    {
        return $this->module->id;
    }

    /**
     * Reload items after reordering
     */
    protected function reloadItems(): void
    {
        $this->module->load([
            'lessons' => function ($query) {
                $query->with(['instructor:id,name', 'tags:id,name'])
                    ->orderBy('order');
            }
        ]);
    }

    /**
     * Toggle trial status
     */
    public function toggleTrial($lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        $lesson->update(['is_trial' => !$lesson->is_trial]);

        // Reload lessons
        $this->module->load([
            'lessons' => function ($query) {
                $query->with(['instructor:id,name', 'tags:id,name'])
                    ->orderBy('order');
            }
        ]);

        $status = $lesson->is_trial ? 'gratuita' : 'premium';
        session()->flash('success', "Lección marcada como {$status}");
    }

    /**
     * Delete lesson
     */
    #[On('confirmDelete')]
    public function deleteLesson($lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);

        // Delete thumbnail if exists
        if ($lesson->thumbnail && \Storage::disk('public')->exists($lesson->thumbnail)) {
            \Storage::disk('public')->delete($lesson->thumbnail);
        }

        // If Bunny video, optionally delete from Bunny (commented out for safety)
        // if ($lesson->video_type === 'bunny' && $lesson->bunny_video_id) {
        //     $bunnyService = new \App\Services\BunnyService();
        //     $bunnyService->deleteVideo($lesson->bunny_video_id);
        // }

        $lesson->delete();

        // Reload lessons
        $this->module->load([
            'lessons' => function ($query) {
                $query->with(['instructor:id,name', 'tags:id,name'])
                    ->orderBy('order');
            }
        ]);

        $this->lessonsCount = $this->module->lessons->count();

        session()->flash('success', 'Lección eliminada exitosamente');
    }

    public function render()
    {
        return view('livewire.admin.lesson-management')
            ->layout('layouts.admin')
            ->title('Gestión de Lecciones - Admin');
    }
}
