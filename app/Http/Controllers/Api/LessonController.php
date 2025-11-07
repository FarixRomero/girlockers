<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Module;
use App\Services\BunnyService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    /**
     * Display a listing of lessons for a module.
     */
    public function index($moduleId)
    {
        $module = Module::with(['course', 'lessons' => function ($query) {
            $query->with(['instructor', 'tags'])->orderBy('order');
        }])->findOrFail($moduleId);

        return response()->json([
            'success' => true,
            'module' => $module
        ]);
    }

    /**
     * Store a newly created lesson.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'title' => 'required|min:3|max:255',
            'description' => 'required|min:10',
            'instructor_id' => 'nullable|exists:instructors,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'video_type' => 'required|in:youtube,local,bunny',
            'youtube_id' => 'required_if:video_type,youtube|string|max:20|nullable',
            'video_path' => 'nullable|string',
            'bunny_video_id' => 'required_if:video_type,bunny|string|nullable',
            'thumbnail' => 'nullable|string',
            'video_duration' => 'nullable|integer|min:0',
            'duration' => 'nullable|integer|min:0',
            'order' => 'required|integer|min:1',
            'is_trial' => 'boolean',
        ]);

        // Si es video Bunny, obtener información del video
        if ($validated['video_type'] === 'bunny' && isset($validated['bunny_video_id'])) {
            $bunnyService = new BunnyService();
            $videoInfo = $bunnyService->getVideoInfo($validated['bunny_video_id']);
            if ($videoInfo && isset($videoInfo['length'])) {
                $validated['video_duration'] = $videoInfo['length'];
            }
        }

        $tags = $validated['tags'] ?? [];
        unset($validated['tags']);

        $lesson = Lesson::create($validated);

        if (!empty($tags)) {
            $lesson->tags()->sync($tags);
        }

        $lesson->load(['instructor', 'tags']);

        // Send notifications to users
        $notificationService = new NotificationService();
        $notificationService->notifyNewLesson($lesson);

        return response()->json([
            'success' => true,
            'message' => 'Lección creada exitosamente',
            'lesson' => $lesson
        ], 201);
    }

    /**
     * Display the specified lesson.
     */
    public function show($id)
    {
        $lesson = Lesson::with('module')->findOrFail($id);

        return response()->json([
            'success' => true,
            'lesson' => $lesson
        ]);
    }

    /**
     * Update the specified lesson.
     */
    public function update(Request $request, $id)
    {
        $lesson = Lesson::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|min:3|max:255',
            'description' => 'required|min:10',
            'instructor_id' => 'nullable|exists:instructors,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'video_type' => 'required|in:youtube,local,bunny',
            'youtube_id' => 'required_if:video_type,youtube|string|max:20|nullable',
            'video_path' => 'nullable|string',
            'bunny_video_id' => 'required_if:video_type,bunny|string|nullable',
            'thumbnail' => 'nullable|string',
            'video_duration' => 'nullable|integer|min:0',
            'duration' => 'nullable|integer|min:0',
            'order' => 'required|integer|min:1',
            'is_trial' => 'boolean',
        ]);

        // Si es video Bunny, obtener información del video
        if ($validated['video_type'] === 'bunny' && isset($validated['bunny_video_id'])) {
            // Si cambió el video, eliminar el anterior
            if ($lesson->bunny_video_id && $lesson->bunny_video_id !== $validated['bunny_video_id']) {
                $bunnyService = new BunnyService();
                $bunnyService->deleteVideo($lesson->bunny_video_id);
            }

            // Obtener duración del nuevo video
            $bunnyService = new BunnyService();
            $videoInfo = $bunnyService->getVideoInfo($validated['bunny_video_id']);
            if ($videoInfo && isset($videoInfo['length'])) {
                $validated['video_duration'] = $videoInfo['length'];
            }
        }

        // Si cambió de tipo de video, limpiar campos anteriores
        if ($validated['video_type'] !== $lesson->video_type) {
            if ($lesson->video_type === 'local' && $lesson->video_path) {
                Storage::disk('public')->delete($lesson->video_path);
            } elseif ($lesson->video_type === 'bunny' && $lesson->bunny_video_id) {
                $bunnyService = new BunnyService();
                $bunnyService->deleteVideo($lesson->bunny_video_id);
            }
        }

        $tags = $validated['tags'] ?? [];
        unset($validated['tags']);

        $lesson->update($validated);

        if (isset($request->tags)) {
            $lesson->tags()->sync($tags);
        }

        $lesson->load(['instructor', 'tags']);

        return response()->json([
            'success' => true,
            'message' => 'Lección actualizada exitosamente',
            'lesson' => $lesson
        ]);
    }

    /**
     * Remove the specified lesson.
     */
    public function destroy($id)
    {
        $lesson = Lesson::findOrFail($id);

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

        return response()->json([
            'success' => true,
            'message' => 'Lección eliminada exitosamente'
        ]);
    }

    /**
     * Move lesson up in order
     */
    public function moveUp($id)
    {
        $lesson = Lesson::findOrFail($id);
        $previousLesson = Lesson::where('module_id', $lesson->module_id)
            ->where('order', '<', $lesson->order)
            ->orderBy('order', 'desc')
            ->first();

        if ($previousLesson) {
            $tempOrder = $lesson->order;
            $lesson->update(['order' => $previousLesson->order]);
            $previousLesson->update(['order' => $tempOrder]);

            return response()->json([
                'success' => true,
                'message' => 'Lección movida hacia arriba'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No se puede mover más arriba'
        ], 400);
    }

    /**
     * Move lesson down in order
     */
    public function moveDown($id)
    {
        $lesson = Lesson::findOrFail($id);
        $nextLesson = Lesson::where('module_id', $lesson->module_id)
            ->where('order', '>', $lesson->order)
            ->orderBy('order', 'asc')
            ->first();

        if ($nextLesson) {
            $tempOrder = $lesson->order;
            $lesson->update(['order' => $nextLesson->order]);
            $nextLesson->update(['order' => $tempOrder]);

            return response()->json([
                'success' => true,
                'message' => 'Lección movida hacia abajo'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No se puede mover más abajo'
        ], 400);
    }

    /**
     * Toggle trial status
     */
    public function toggleTrial($id)
    {
        $lesson = Lesson::findOrFail($id);
        $lesson->update(['is_trial' => !$lesson->is_trial]);

        $status = $lesson->is_trial ? 'trial' : 'premium';

        return response()->json([
            'success' => true,
            'message' => "Lección marcada como {$status}",
            'lesson' => $lesson
        ]);
    }

    /**
     * Upload thumbnail image
     */
    public function uploadThumbnail(Request $request)
    {
        $request->validate([
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120'
        ]);

        try {
            $path = $request->file('thumbnail')->store('lessons/thumbnails', 'public');

            return response()->json([
                'success' => true,
                'message' => 'Imagen subida correctamente',
                'path' => $path
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al subir la imagen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get video duration from Bunny.net
     */
    public function getBunnyDuration(Request $request)
    {
        try {
            $videoId = $request->input('video_id');

            if (!$videoId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Video ID is required'
                ], 400);
            }

            $bunnyService = new BunnyService();
            $videoInfo = $bunnyService->getVideoInfo($videoId);

            if ($videoInfo && isset($videoInfo['length'])) {
                // Convert seconds to minutes
                $durationInMinutes = ceil($videoInfo['length'] / 60);

                return response()->json([
                    'success' => true,
                    'duration' => $durationInMinutes,
                    'duration_seconds' => $videoInfo['length']
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Could not retrieve video duration'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Error getting Bunny video duration: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
