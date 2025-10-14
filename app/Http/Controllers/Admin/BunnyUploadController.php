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
}
