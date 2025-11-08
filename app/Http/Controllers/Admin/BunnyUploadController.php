<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BunnyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BunnyUploadController extends Controller
{
    /**
     * Inicializar subida directa a Bunny.net (Paso 1)
     */
    public function initUpload(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
            ]);

            $bunnyService = new BunnyService();

            // Crear video en Bunny.net
            $videoData = $bunnyService->createVideo($validated['title']);

            Log::info('Respuesta de createVideo:', ['videoData' => $videoData]);

            if (!$videoData || !isset($videoData['video_id'])) {
                Log::error('Error al crear video en Bunny.net', [
                    'videoData' => $videoData
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear video en Bunny.net'
                ], 500);
            }

            $videoId = $videoData['video_id'];

            return response()->json([
                'success' => true,
                'video_id' => $videoId,
                'library_id' => config('bunny.library_id'),
                'upload_url' => $videoData['upload_url'],
                'api_key' => config('bunny.api_key')
            ]);

        } catch (\Exception $e) {
            Log::error('Error en initUpload: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al inicializar subida: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Confirmar subida directa (Paso 3)
     */
    public function confirmUpload(Request $request)
    {
        try {
            $validated = $request->validate([
                'video_id' => 'required|string',
            ]);

            // Aquí solo confirmamos que el video_id es válido
            // El guardado en la BD lo hace el componente Livewire al guardar la lección

            return response()->json([
                'success' => true,
                'message' => 'Video confirmado correctamente',
                'video_id' => $validated['video_id']
            ]);

        } catch (\Exception $e) {
            Log::error('Error en confirmUpload: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al confirmar subida: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get video duration from Bunny.net
     * Returns duration in seconds (to be stored in DB)
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
                return response()->json([
                    'success' => true,
                    'duration' => $videoInfo['length'], // Duration in seconds
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
}
