<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BunnyService
{
    protected $libraryId;
    protected $apiKey;
    protected $cdnHostname;
    protected $streamUrl;

    public function __construct()
    {
        $this->libraryId = config('bunny.library_id');
        $this->apiKey = config('bunny.api_key');
        $this->cdnHostname = config('bunny.cdn_hostname');
        $this->streamUrl = config('bunny.stream_url');
    }

    /**
     * Genera una firma de autenticación para upload directo
     *
     * @param string $videoId ID del video
     * @param int $expirationTime Tiempo de expiración en segundos (default: 1 hora)
     * @return array
     */
    public function generateUploadSignature($videoId, $expirationTime = 3600)
    {
        // Timestamp de expiración
        $expirationTimestamp = time() + $expirationTime;

        // Datos para la firma
        $libraryId = $this->libraryId;
        $apiKey = $this->apiKey;

        // Crear la firma: SHA256(library_id + api_key + expiration_time + video_id)
        $signatureString = $libraryId . $apiKey . $expirationTimestamp . $videoId;
        $signature = hash('sha256', $signatureString);

        return [
            'signature' => $signature,
            'expiration_time' => $expirationTimestamp,
            'video_id' => $videoId,
            'library_id' => $libraryId,
        ];
    }

    /**
     * Crea un video en Bunny.net y genera firma para upload seguro
     * Retorna el video ID y la firma para subida directa desde el cliente
     *
     * @param string $title Título del video
     * @return array|null
     */
    public function createVideo($title)
    {
        try {
            $createResponse = Http::withHeaders([
                'AccessKey' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->streamUrl}/library/{$this->libraryId}/videos", [
                'title' => $title,
            ]);

            if (!$createResponse->successful()) {
                Log::error('Error al crear video en Bunny.net', [
                    'status' => $createResponse->status(),
                    'response' => $createResponse->body(),
                ]);
                return null;
            }

            $videoData = $createResponse->json();
            $videoId = $videoData['guid'];

            // Generar firma de autenticación
            $signature = $this->generateUploadSignature($videoId);

            return [
                'video_id' => $videoId,
                'title' => $title,
                'library_id' => $this->libraryId,
                'upload_url' => "{$this->streamUrl}/library/{$this->libraryId}/videos/{$videoId}",
                'signature' => $signature['signature'],
                'expiration_time' => $signature['expiration_time'],
                // Mantener api_key para compatibilidad (pero ahora usaremos firma)
                'api_key' => $this->apiKey,
            ];
        } catch (\Exception $e) {
            Log::error('Excepción al crear video en Bunny.net: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Sube un video directamente desde Laravel a Bunny.net
     *
     * @param string $videoId ID del video creado
     * @param string $filePath Ruta del archivo temporal
     * @return bool
     */
    public function uploadVideo($videoId, $filePath)
    {
        try {
            if (!file_exists($filePath)) {
                Log::error('Archivo no encontrado: ' . $filePath);
                return false;
            }

            $fileContent = file_get_contents($filePath);
            $uploadUrl = "{$this->streamUrl}/library/{$this->libraryId}/videos/{$videoId}";

            $response = Http::withHeaders([
                'AccessKey' => $this->apiKey,
            ])
            ->withBody($fileContent, 'video/mp4')
            ->put($uploadUrl);

            if ($response->successful()) {
                Log::info("Video {$videoId} subido exitosamente");
                return true;
            }

            Log::error('Error al subir video', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('Excepción al subir video: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene información de un video
     *
     * @param string $videoId
     * @return array|null
     */
    public function getVideoInfo($videoId)
    {
        try {
            $response = Http::withHeaders([
                'AccessKey' => $this->apiKey,
            ])->get("{$this->streamUrl}/library/{$this->libraryId}/videos/{$videoId}");

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Error al obtener info del video: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Elimina un video de Bunny.net
     *
     * @param string $videoId
     * @return bool
     */
    public function deleteVideo($videoId)
    {
        try {
            $response = Http::withHeaders([
                'AccessKey' => $this->apiKey,
            ])->delete("{$this->streamUrl}/library/{$this->libraryId}/videos/{$videoId}");

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Error al eliminar video: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene la URL de reproducción del video
     *
     * @param string $videoId
     * @return string
     */
    public function getVideoUrl($videoId)
    {
        return "https://{$this->cdnHostname}/{$videoId}/playlist.m3u8";
    }

    /**
     * Obtiene la URL de la thumbnail del video
     *
     * @param string $videoId
     * @return string
     */
    public function getThumbnailUrl($videoId)
    {
        return "https://{$this->cdnHostname}/{$videoId}/thumbnail.jpg";
    }

    /**
     * Obtiene el código embed del video
     *
     * @param string $videoId
     * @param array $options
     * @return string
     */
    public function getEmbedCode($videoId, $options = [])
    {
        $width = $options['width'] ?? '100%';
        $height = $options['height'] ?? '100%';
        $autoplay = $options['autoplay'] ?? false;
        $loop = $options['loop'] ?? false;
        $muted = $options['muted'] ?? false;

        $params = [];
        if ($autoplay) $params[] = 'autoplay=true';
        if ($loop) $params[] = 'loop=true';
        if ($muted) $params[] = 'muted=true';

        $queryString = !empty($params) ? '?' . implode('&', $params) : '';

        return sprintf(
            '<iframe src="https://iframe.mediadelivery.net/embed/%s/%s%s" loading="lazy" style="border: none; position: absolute; top: 0; height: %s; width: %s;" allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;" allowfullscreen="true"></iframe>',
            $this->libraryId,
            $videoId,
            $queryString,
            $height,
            $width
        );
    }

    /**
     * Lista todos los videos de la biblioteca
     *
     * @param int $page
     * @param int $perPage
     * @return array|null
     */
    public function listVideos($page = 1, $perPage = 100)
    {
        try {
            $response = Http::withHeaders([
                'AccessKey' => $this->apiKey,
            ])->get("{$this->streamUrl}/library/{$this->libraryId}/videos", [
                'page' => $page,
                'itemsPerPage' => $perPage,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Error al listar videos: ' . $e->getMessage());
            return null;
        }
    }
}
