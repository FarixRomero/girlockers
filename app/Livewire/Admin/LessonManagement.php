<?php

namespace App\Livewire\Admin;

use App\Models\Lesson;
use App\Models\Module;
use Livewire\Component;
use Livewire\Attributes\On;

class LessonManagement extends Component
{
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
     * Move lesson up in order
     */
    public function moveUp($lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);

        $previousLesson = Lesson::where('module_id', $this->module->id)
            ->where('order', '<', $lesson->order)
            ->orderBy('order', 'desc')
            ->first();

        if ($previousLesson) {
            // Swap orders
            $tempOrder = $lesson->order;
            $lesson->update(['order' => $previousLesson->order]);
            $previousLesson->update(['order' => $tempOrder]);

            // Reload lessons
            $this->module->load([
                'lessons' => function ($query) {
                    $query->with(['instructor:id,name', 'tags:id,name'])
                        ->orderBy('order');
                }
            ]);

            session()->flash('message', 'Lección movida hacia arriba');
        }
    }

    /**
     * Move lesson down in order
     */
    public function moveDown($lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);

        $nextLesson = Lesson::where('module_id', $this->module->id)
            ->where('order', '>', $lesson->order)
            ->orderBy('order', 'asc')
            ->first();

        if ($nextLesson) {
            // Swap orders
            $tempOrder = $lesson->order;
            $lesson->update(['order' => $nextLesson->order]);
            $nextLesson->update(['order' => $tempOrder]);

            // Reload lessons
            $this->module->load([
                'lessons' => function ($query) {
                    $query->with(['instructor:id,name', 'tags:id,name'])
                        ->orderBy('order');
                }
            ]);

            session()->flash('message', 'Lección movida hacia abajo');
        }
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
        session()->flash('message', "Lección marcada como {$status}");
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

        session()->flash('message', 'Lección eliminada exitosamente');
    }

    public function render()
    {
        return view('livewire.admin.lesson-management')
            ->layout('layouts.admin')
            ->title('Gestión de Lecciones - Admin');
    }
}
