<?php

namespace App\Livewire\Admin;

use App\Models\Module;
use App\Models\Lesson;
use App\Services\BunnyService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
    public $video_type = 'youtube'; // youtube, local or bunny
    public $youtube_id = '';
    public $video_file;
    public $video_path = '';
    public $bunny_video_id = '';
    public $video_duration = 0;
    public $duration = 0;
    public $order = 1;
    public $is_trial = false;
    public $uploadProgress = 0;

    // Modal state
    public $showModal = false;
    public $isEditing = false;

    protected function rules()
    {
        $rules = [
            'title' => 'required|min:3|max:255',
            'description' => 'required|min:10',
            'video_type' => 'required|in:youtube,local,bunny',
            'duration' => 'nullable|integer|min:0',
            'order' => 'required|integer|min:1',
            'is_trial' => 'boolean',
        ];

        if ($this->video_type === 'youtube') {
            $rules['youtube_id'] = 'required|string|max:20';
        } elseif ($this->video_type === 'local') {
            if (!$this->isEditing || $this->video_file) {
                $rules['video_file'] = 'required|file|mimes:mp4,mov,avi,wmv|max:512000'; // 500MB max
            }
        } elseif ($this->video_type === 'bunny') {
            // Para Bunny, el video se crea y sube directamente desde el navegador
            // El bunny_video_id se obtiene automáticamente después del upload
            // No validamos aquí porque el botón de submit está deshabilitado hasta que termine el upload
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

    /**
     * Inicializa la subida directa a Bunny.net (Paso 1)
     */
    public function initDirectUpload($title)
    {
        try {
            $bunnyService = new BunnyService();

            // Eliminar video anterior si existe
            if ($this->bunny_video_id) {
                $bunnyService->deleteVideo($this->bunny_video_id);
            }

            // Crear video en Bunny.net
            $videoData = $bunnyService->createVideo($title);

            if (!$videoData || !isset($videoData['guid'])) {
                return [
                    'success' => false,
                    'message' => 'Error al crear video en Bunny.net'
                ];
            }

            $videoId = $videoData['guid'];

            return [
                'success' => true,
                'video_id' => $videoId,
                'library_id' => config('bunny.library_id'),
                'upload_url' => config('bunny.stream_url') . "/library/" . config('bunny.library_id') . "/videos/" . $videoId,
                'api_key' => config('bunny.api_key')
            ];
        } catch (\Exception $e) {
            Log::error('Error en initDirectUpload: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al inicializar subida: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Confirma la subida directa (Paso 3)
     */
    public function confirmDirectUpload($videoId)
    {
        try {
            // Guardar el video_id en la propiedad para usarlo al guardar
            $this->bunny_video_id = $videoId;

            return [
                'success' => true,
                'message' => 'Video vinculado correctamente',
                'video_id' => $videoId
            ];
        } catch (\Exception $e) {
            Log::error('Error en confirmDirectUpload: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al confirmar subida: ' . $e->getMessage()
            ];
        }
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
        $this->bunny_video_id = $lesson->bunny_video_id ?? '';
        $this->video_duration = $lesson->video_duration ?? 0;
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
            'bunny_video_id',
            'video_duration',
            'duration',
            'order',
            'is_trial',
            'uploadProgress',
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
            $data['bunny_video_id'] = null;
        } elseif ($this->video_type === 'local' && $this->video_file) {
            $data['video_path'] = $this->video_file->store('lessons', 'public');
            $data['youtube_id'] = null;
            $data['bunny_video_id'] = null;
        } elseif ($this->video_type === 'bunny' && $this->bunny_video_id) {
            // El video ya fue subido directamente desde el navegador
            $data['bunny_video_id'] = $this->bunny_video_id;
            $data['youtube_id'] = null;
            $data['video_path'] = null;

            // Obtener información del video para obtener la duración
            $bunnyService = new BunnyService();
            $videoInfo = $bunnyService->getVideoInfo($this->bunny_video_id);
            if ($videoInfo && isset($videoInfo['length'])) {
                $data['video_duration'] = $videoInfo['length'];
            }
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
            Storage::disk('public')->delete($lesson->video_path);
        }

        // Delete video from Bunny.net if it's a bunny video
        if ($lesson->video_type === 'bunny' && $lesson->bunny_video_id) {
            $bunnyService = new BunnyService();
            $bunnyService->deleteVideo($lesson->bunny_video_id);
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
