# Integración Completa con Bunny.net CDN

Esta documentación explica de manera detallada cómo funciona la integración completa entre el frontend y backend con Bunny.net para la gestión y streaming de videos en la plataforma.

## Tabla de Contenido

1. [Arquitectura General](#arquitectura-general)
2. [Backend: Configuración y Servicios](#backend-configuración-y-servicios)
3. [Frontend: Subida y Reproducción](#frontend-subida-y-reproducción)
4. [Flujos de Trabajo Completos](#flujos-de-trabajo-completos)
5. [Base de Datos](#base-de-datos)
6. [Métodos de Subida](#métodos-de-subida)

---

## Arquitectura General

La integración con Bunny.net se basa en una **arquitectura híbrida** que soporta dos modos de almacenamiento:

```
┌─────────────────┐
│   Frontend      │
│   (Blade/JS)    │
└────────┬────────┘
         │
         ▼
┌─────────────────────────────────────┐
│   VideoStorageService (Selector)    │
│   Decide: Local o Bunny.net         │
└──────────┬─────────────┬────────────┘
           │             │
    LOCAL  │             │  BUNNY
           ▼             ▼
   ┌──────────┐   ┌──────────────┐
   │ Storage  │   │ BunnyNetClient│
   │  Local   │   │   (HTTP API)  │
   └──────────┘   └───────┬───────┘
                          │
                          ▼
                  ┌──────────────────┐
                  │  Bunny.net CDN   │
                  │  (Video Stream)  │
                  └──────────────────┘
```

### Componentes Principales

1. **VideoStorageService**: Servicio que abstrae la lógica de almacenamiento
2. **BunnyNetClient**: Cliente HTTP para comunicación con la API de Bunny.net
3. **Video Player Component**: Componente Blade para reproducir videos
4. **JavaScript Frontend**: Lógica de subida directa y progreso en tiempo real

---

## Backend: Configuración y Servicios

### 1. Archivo de Configuración

**Ubicación**: `config/video.php`

```php
return [
    // Driver principal: 'local' o 'bunny'
    'driver' => env('VIDEO_STORAGE_DRIVER', 'local'),

    'bunny' => [
        'library_id' => env('BUNNY_LIBRARY_ID'),
        'api_key' => env('BUNNY_API_KEY'),
        'cdn_hostname' => env('BUNNY_CDN_HOSTNAME'),
        'api_url' => env('BUNNY_STREAM_API_URL', 'https://video.bunnycdn.com'),
        'pull_zone' => env('BUNNY_PULL_ZONE', 'vz-b4377dab-bde'),
        'collection_id' => env('BUNNY_COLLECTION_ID'),
    ],

    'local' => [
        'disk' => 'public',
        'path' => 'videos/courses',
    ],

    'max_upload_size' => [
        'local' => 256000, // 250MB en KB
        'bunny' => 512000, // 500MB en KB
    ],

    'allowed_mimes' => ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'],
];
```

**Variables de Entorno (.env)**:

```env
VIDEO_STORAGE_DRIVER=bunny

# Credenciales de Bunny.net
BUNNY_LIBRARY_ID=510939
BUNNY_API_KEY=9efef787-f484-4124-9ae239684c63-b4b3-4f4b
BUNNY_CDN_HOSTNAME=vz-b4377dab-bde.b-cdn.net
BUNNY_STREAM_API_URL=https://video.bunnycdn.com
```

---

### 2. BunnyNetClient - Cliente HTTP

**Ubicación**: `app/Services/BunnyNetClient.php`

Este servicio encapsula todas las operaciones con la API de Bunny.net.

#### Constructor

```php
public function __construct()
{
    $this->apiUrl = config('video.bunny.api_url');
    $this->apiKey = config('video.bunny.api_key');
    $this->libraryId = config('video.bunny.library_id');
    $this->cdnHostname = config('video.bunny.cdn_hostname');
}
```

#### Métodos Principales

##### createVideo()

Crea un objeto de video en Bunny.net **antes** de subir el archivo.

```php
public function createVideo(string $title, ?string $collectionId = null): array
{
    $response = Http::withHeaders([
        'AccessKey' => $this->apiKey,
        'Content-Type' => 'application/json',
    ])->post("{$this->apiUrl}/library/{$this->libraryId}/videos", [
        'title' => $title,
        'collectionId' => $collectionId
    ]);

    return $response->json();
    // Retorna: ['guid' => 'video-id-here', 'status' => 0, ...]
}
```

**Respuesta de Bunny.net**:

```json
{
  "videoLibraryId": 510939,
  "guid": "abc123-def456-ghi789",
  "title": "Video de Módulo 1",
  "dateUploaded": "2025-01-15T10:30:00Z",
  "status": 0
}
```

##### uploadVideo()

Sube el archivo de video al objeto creado previamente.

```php
public function uploadVideo(string $videoId, UploadedFile $file): array
{
    $response = Http::withHeaders([
        'AccessKey' => $this->apiKey,
    ])->attach(
        'file',
        file_get_contents($file->getRealPath()),
        $file->getClientOriginalName()
    )->put("{$this->apiUrl}/library/{$this->libraryId}/videos/{$videoId}");

    return $response->json();
}
```

**Endpoint Bunny.net**: `PUT https://video.bunnycdn.com/library/{library_id}/videos/{video_id}`

##### Métodos de URLs

```php
// URL del iframe para embeber el video
public function getEmbedUrl(string $videoId): string
{
    return "https://iframe.mediadelivery.net/embed/{$this->libraryId}/{$videoId}";
}

// URL del playlist HLS para reproductores personalizados
public function getPlaylistUrl(string $videoId): string
{
    return "https://{$this->cdnHostname}/{$videoId}/playlist.m3u8";
}

// URL directa del archivo MP4
public function getMp4Url(string $videoId): string
{
    return "https://{$this->cdnHostname}/{$videoId}/play_{$videoId}.mp4";
}

// URL del thumbnail generado automáticamente
public function getThumbnailUrl(string $videoId): string
{
    return "https://{$this->cdnHostname}/{$videoId}/thumbnail.jpg";
}
```

##### getVideo()

Obtiene información del video incluyendo su estado de procesamiento.

```php
public function getVideo(string $videoId): array
{
    $response = Http::withHeaders([
        'AccessKey' => $this->apiKey,
    ])->get("{$this->apiUrl}/library/{$this->libraryId}/videos/{$videoId}");

    return $response->json();
}
```

**Respuesta de ejemplo**:

```json
{
  "guid": "abc123-def456-ghi789",
  "title": "Video de Módulo 1",
  "status": 3,
  "availableResolutions": "240p,360p,480p,720p,1080p",
  "thumbnailFileName": "thumbnail.jpg",
  "length": 245
}
```

**Estados de video**:
- `0`: En cola
- `1`: Procesando
- `2`: Codificando
- `3`: Finalizado ✓
- `4`: Resoluciones finalizadas ✓
- `5`: Error

##### isVideoReady()

Verifica si el video está listo para reproducción.

```php
public function isVideoReady(string $videoId): bool
{
    $video = $this->getVideo($videoId);
    return isset($video['status']) && in_array($video['status'], [3, 4]);
}
```

##### deleteVideo()

Elimina un video de Bunny.net.

```php
public function deleteVideo(string $videoId): bool
{
    $response = Http::withHeaders([
        'AccessKey' => $this->apiKey,
    ])->delete("{$this->apiUrl}/library/{$this->libraryId}/videos/{$videoId}");

    return $response->successful();
}
```

---

### 3. VideoStorageService - Servicio de Almacenamiento

**Ubicación**: `app/Services/VideoStorageService.php`

Este servicio actúa como **capa de abstracción** que decide automáticamente si usar almacenamiento local o Bunny.net.

#### Constructor

```php
public function __construct()
{
    $this->driver = config('video.driver', 'local');

    if ($this->driver === 'bunny') {
        $this->bunnyClient = new BunnyNetClient();
    }
}
```

#### Método uploadVideo()

```php
public function uploadVideo(UploadedFile $file, string $title, int $courseId): array
{
    if ($this->driver === 'bunny') {
        return $this->uploadToBunny($file, $title);
    }

    return $this->uploadToLocal($file, $courseId);
}
```

**Retorno del método**:

```php
// Bunny.net
[
    'driver' => 'bunny',
    'video_id' => 'abc123-def456',
    'path' => null,
    'data' => [...] // Datos completos de Bunny
]

// Local
[
    'driver' => 'local',
    'path' => 'videos/courses/1/xyz.mp4',
    'video_id' => null
]
```

#### uploadToBunny() - Subida a Bunny.net

```php
protected function uploadToBunny(UploadedFile $file, string $title): array
{
    // Paso 1: Crear objeto de video
    $videoData = $this->bunnyClient->createVideo($title, config('video.bunny.collection_id'));
    $videoId = $videoData['guid'];

    // Paso 2: Subir archivo
    $this->bunnyClient->uploadVideo($videoId, $file);

    Log::info("Video uploaded to Bunny.net", ['video_id' => $videoId]);

    return [
        'driver' => 'bunny',
        'video_id' => $videoId,
        'path' => null,
        'data' => $videoData,
    ];
}
```

#### getVideoUrl() - Obtener URL de reproducción

```php
public function getVideoUrl(?string $videoPath, ?string $bunnyVideoId, string $urlType = 'stream'): ?string
{
    if ($this->driver === 'bunny' && $bunnyVideoId) {
        return $this->getBunnyVideoUrl($bunnyVideoId, $urlType);
    }

    if ($videoPath) {
        return $this->getLocalVideoUrl($videoPath, $urlType);
    }

    return null;
}

protected function getBunnyVideoUrl(string $videoId, string $urlType = 'stream'): string
{
    return match ($urlType) {
        'embed' => $this->bunnyClient->getEmbedUrl($videoId),
        'hls' => $this->bunnyClient->getPlaylistUrl($videoId),
        'mp4' => $this->bunnyClient->getMp4Url($videoId),
        default => $this->bunnyClient->getEmbedUrl($videoId),
    };
}
```

#### deleteVideo() - Eliminar video

```php
public function deleteVideo(?string $videoPath, ?string $bunnyVideoId): bool
{
    if ($this->driver === 'bunny' && $bunnyVideoId) {
        return $this->bunnyClient->deleteVideo($bunnyVideoId);
    }

    if ($videoPath && Storage::disk('public')->exists($videoPath)) {
        return Storage::disk('public')->delete($videoPath);
    }

    return true;
}
```

---

### 4. Controladores

#### Livewire - Módulos (Admin/Modules.php:131-179)

Gestiona la creación y edición de módulos de cursos.

```php
public function save()
{
    $validatedData = $this->validate();

    if ($this->video) {
        $videoService = new VideoStorageService();

        // Eliminar video anterior si se está editando
        if ($this->editing) {
            $videoService->deleteVideo($this->video_path, $this->bunny_video_id);
        }

        // Subir nuevo video
        $uploadResult = $videoService->uploadVideo(
            $this->video,
            $this->title,
            $this->course->id
        );

        // Asignar campos según el driver
        if ($uploadResult['driver'] === 'bunny') {
            $validatedData['bunny_video_id'] = $uploadResult['video_id'];
            $validatedData['video_path'] = null;
        } else {
            $validatedData['video_path'] = $uploadResult['path'];
            $validatedData['bunny_video_id'] = null;
        }
    }

    // Crear o actualizar módulo
    if ($this->editing) {
        Module::findOrFail($this->moduleId)->update($validatedData);
    } else {
        Module::create($validatedData);
    }

    $this->closeModal();
}
```

#### FolderController - Subida de Videos

**Método 1: Subida tradicional (a través del backend)**

**Ruta**: `POST /admin/folders/{folder}/upload-video`

```php
public function uploadVideo(Request $request, Folder $folder)
{
    $driver = config('video.driver');
    $maxSize = config("video.max_upload_size.{$driver}");

    $validated = $request->validate([
        'video_file' => "required|file|mimes:mp4,avi,mov,wmv,webm|max:{$maxSize}",
    ]);

    $videoService = new VideoStorageService();

    // Eliminar video anterior
    $videoService->deleteVideo($folder->video_path, $folder->bunny_video_id);

    // Subir nuevo video
    $uploadResult = $videoService->uploadVideo(
        $request->file('video_file'),
        $folder->video_title ?? $folder->name,
        0
    );

    // Actualizar carpeta
    if ($uploadResult['driver'] === 'bunny') {
        $folder->update([
            'bunny_video_id' => $uploadResult['video_id'],
            'video_path' => null,
        ]);
    } else {
        $folder->update([
            'video_path' => $uploadResult['path'],
            'bunny_video_id' => null,
        ]);
    }

    return response()->json([
        'success' => true,
        'message' => 'Video subido correctamente',
        'video_url' => $videoService->getVideoUrl(...),
    ]);
}
```

**Método 2: Subida directa a Bunny.net (sin pasar por el backend)**

**Paso 1 - Inicializar subida**: `POST /admin/folders/{folder}/init-direct-upload`

```php
public function initDirectUpload(Request $request, Folder $folder)
{
    if (config('video.driver') !== 'bunny') {
        return response()->json(['success' => false, 'message' => 'Solo disponible con Bunny.net'], 400);
    }

    $validated = $request->validate([
        'title' => 'required|string|max:255',
    ]);

    $bunnyClient = new BunnyNetClient();

    // Eliminar video anterior
    if ($folder->bunny_video_id) {
        $bunnyClient->deleteVideo($folder->bunny_video_id);
    }

    // Crear video en Bunny
    $videoData = $bunnyClient->createVideo($validated['title']);

    return response()->json([
        'success' => true,
        'video_id' => $videoData['guid'],
        'library_id' => config('video.bunny.library_id'),
        'upload_url' => "https://video.bunnycdn.com/library/" . config('video.bunny.library_id') . "/videos/" . $videoData['guid'],
        'api_key' => config('video.bunny.api_key')
    ]);
}
```

**Paso 2 - Confirmar subida**: `POST /admin/folders/{folder}/confirm-direct-upload`

```php
public function confirmDirectUpload(Request $request, Folder $folder)
{
    $validated = $request->validate([
        'video_id' => 'required|string',
    ]);

    $folder->update([
        'bunny_video_id' => $validated['video_id'],
        'video_path' => null,
    ]);

    $bunnyClient = new BunnyNetClient();
    $videoUrl = $bunnyClient->getEmbedUrl($validated['video_id']);

    return response()->json([
        'success' => true,
        'message' => 'Video vinculado correctamente',
        'video_url' => $videoUrl
    ]);
}
```

---

## Frontend: Subida y Reproducción

### 1. Componente Video Player

**Ubicación**: `resources/views/components/video-player.blade.php`

Este componente Blade renderiza el reproductor apropiado según el tipo de almacenamiento.

```blade
@props(['module' => null, 'folder' => null])

@php
    $videoObject = $module ?? $folder;
    $usesBunny = $videoObject->usesBunny();
    $videoUrl = $usesBunny ? $videoObject->video_embed_url : $videoObject->video_url;
    $hasVideo = !empty($videoObject->video_path) || !empty($videoObject->bunny_video_id);
@endphp

@if($hasVideo)
    @if($usesBunny)
        {{-- Bunny.net iframe player --}}
        <div class="relative aspect-video">
            <iframe
                src="{{ $videoUrl }}"
                loading="lazy"
                style="border: none; position: absolute; top: 0; height: 100%; width: 100%;"
                allow="accelerometer;gyroscope;autoplay;encrypted-media;picture-in-picture;"
                allowfullscreen="true">
            </iframe>
        </div>
    @else
        {{-- Local video player HTML5 --}}
        <div class="relative aspect-video">
            <video controls class="w-full h-full object-contain" preload="auto">
                <source src="{{ $videoUrl }}" type="video/mp4">
                Tu navegador no soporta videos HTML5.
            </video>
        </div>
    @endif
@else
    {{-- Placeholder cuando no hay video --}}
    <div class="relative aspect-video bg-gray-800 flex items-center justify-center">
        <p class="text-gray-400">Sin video disponible</p>
    </div>
@endif
```

**Uso**:

```blade
{{-- En un módulo de curso --}}
<x-video-player :module="$module" />

{{-- En una carpeta de documentos --}}
<x-video-player :folder="$folder" />
```

---

### 2. JavaScript: Subida de Videos con Progreso

**Ubicación**: `resources/views/admin/folders/edit.blade.php` (scripts en línea)

#### Configuración Inicial

```javascript
const IS_BUNNY = @json(config('video.driver') === 'bunny');
const MAX_SIZE_MB = @json(config("video.max_upload_size." . config('video.driver')) / 1024);
let uploadXhr = null;
let uploadStartTime = null;
let uploadStartLoaded = 0;
```

#### Función Principal: uploadVideoFile()

```javascript
function uploadVideoFile(input) {
    const file = input.files[0];
    if (!file) return;

    // Validar tipo de archivo
    const allowedTypes = ['video/mp4', 'video/avi', 'video/quicktime', 'video/x-ms-wmv', 'video/webm'];
    if (!allowedTypes.includes(file.type)) {
        showError('Tipo de archivo no válido...');
        return;
    }

    // Validar tamaño
    const maxSize = MAX_SIZE_MB * 1024 * 1024;
    if (file.size > maxSize) {
        showError(`Archivo demasiado grande. Máximo: ${MAX_SIZE_MB}MB`);
        return;
    }

    // Mostrar barra de progreso
    hideMessages();
    document.getElementById('upload-progress-container').classList.remove('hidden');

    // Decidir método de subida
    if (IS_BUNNY) {
        uploadDirectToBunny(file);
    } else {
        uploadToBackend(file);
    }
}
```

#### Subida Directa a Bunny.net

```javascript
function uploadDirectToBunny(file) {
    // Paso 1: Inicializar subida y obtener credenciales
    fetch('{{ route("admin.folders.init-direct-upload", $folder) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            title: document.getElementById('video_title').value || '{{ $folder->name }}'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            throw new Error(data.message || 'Error al inicializar subida');
        }

        // Paso 2: Subir directamente a Bunny.net con XMLHttpRequest
        uploadStartTime = Date.now();
        uploadXhr = new XMLHttpRequest();

        // Evento de progreso
        uploadXhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                const percentComplete = Math.round((e.loaded / e.total) * 100);
                updateProgress(percentComplete, e.loaded, e.total);
            }
        });

        // Evento de carga (éxito)
        uploadXhr.addEventListener('load', function() {
            if (uploadXhr.status === 200) {
                // Paso 3: Confirmar subida con backend
                fetch('{{ route("admin.folders.confirm-direct-upload", $folder) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        video_id: data.video_id
                    })
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        showSuccess();
                        setTimeout(() => window.location.reload(), 2000);
                    } else {
                        throw new Error(result.message);
                    }
                });
            } else {
                showError('Error al subir a Bunny.net: ' + uploadXhr.status);
            }
        });

        // Evento de error
        uploadXhr.addEventListener('error', function() {
            showError('Error de conexión con Bunny.net');
        });

        // Iniciar subida a Bunny.net
        uploadXhr.open('PUT', data.upload_url);
        uploadXhr.setRequestHeader('AccessKey', data.api_key);
        uploadXhr.send(file);
    })
    .catch(error => {
        showError('Error al iniciar subida: ' + error.message);
    });
}
```

**Flujo de subida directa**:

```
┌─────────────┐
│  Frontend   │
└──────┬──────┘
       │
       │ 1. POST /init-direct-upload (Laravel)
       ▼
┌─────────────────┐
│   Laravel       │ ─── 2. POST /library/{id}/videos (Bunny API)
│   Backend       │ ◄── 3. Retorna video_id, upload_url, api_key
└──────┬──────────┘
       │ 4. Retorna credenciales al frontend
       ▼
┌─────────────┐
│  Frontend   │ ─── 5. PUT {upload_url} + video file ────┐
└──────┬──────┘                                           │
       │                                                  ▼
       │                                          ┌───────────────┐
       │                                          │  Bunny.net    │
       │                                          │  CDN Storage  │
       │                                          └───────────────┘
       │ 6. Upload completo (200 OK)
       │
       │ 7. POST /confirm-direct-upload (Laravel)
       ▼
┌─────────────────┐
│   Laravel       │ ─── 8. Actualiza DB: bunny_video_id
│   Backend       │
└─────────────────┘
```

#### Actualización de Progreso

```javascript
function updateProgress(percentage, loaded, total) {
    // Actualizar barra de progreso
    document.getElementById('upload-progress-bar').style.width = percentage + '%';
    document.getElementById('upload-percentage').textContent = percentage + '%';

    // Calcular velocidad de subida
    const currentTime = Date.now();
    const elapsedTime = (currentTime - uploadStartTime) / 1000; // segundos
    const uploadedBytes = loaded - uploadStartLoaded;
    const speed = uploadedBytes / elapsedTime; // bytes por segundo

    // Mostrar velocidad
    document.getElementById('upload-speed').textContent = formatBytes(speed) + '/s';

    // Calcular tiempo estimado
    const remainingBytes = total - loaded;
    const remainingSeconds = remainingBytes / speed;
    document.getElementById('upload-eta').textContent = 'Tiempo Espera: ' + formatTime(remainingSeconds);

    // Actualizar para próximo cálculo
    uploadStartTime = currentTime;
    uploadStartLoaded = loaded;
}

function formatBytes(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

function formatTime(seconds) {
    if (seconds < 60) {
        return Math.round(seconds) + 's';
    } else if (seconds < 3600) {
        const minutes = Math.floor(seconds / 60);
        const secs = Math.round(seconds % 60);
        return minutes + 'm ' + secs + 's';
    } else {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        return hours + 'h ' + minutes + 'm';
    }
}
```

#### HTML de la Barra de Progreso

```html
<div id="upload-progress-container" class="hidden mb-4">
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-blue-900" id="upload-status">Subiendo video...</span>
            <span class="text-sm font-medium text-blue-900" id="upload-percentage">0%</span>
        </div>
        <div class="w-full bg-blue-200 rounded-full h-3 overflow-hidden">
            <div id="upload-progress-bar" class="bg-blue-600 h-3 rounded-full transition-all duration-300" style="width: 0%"></div>
        </div>
        <div class="mt-2 flex items-center justify-between text-xs text-blue-700">
            <span id="upload-speed">--</span>
            <span id="upload-eta">--</span>
        </div>
    </div>
</div>
```

---

## Flujos de Trabajo Completos

### Flujo 1: Subida de Video para Módulo de Curso (Bunny.net)

```
┌──────────────────────────────────────────────────────────────────────┐
│                      ADMIN: Crear/Editar Módulo                      │
└──────────────────────────────────────────────────────────────────────┘
                                   │
                                   ▼
┌──────────────────────────────────────────────────────────────────────┐
│  1. Admin sube video desde Livewire (Admin/Modules.php)             │
│     - Selecciona archivo de video                                    │
│     - Livewire valida tamaño y tipo                                  │
└──────────────────────────────────────────────────────────────────────┘
                                   │
                                   ▼
┌──────────────────────────────────────────────────────────────────────┐
│  2. VideoStorageService::uploadVideo()                               │
│     - Detecta driver: 'bunny'                                        │
│     - Llama a uploadToBunny()                                        │
└──────────────────────────────────────────────────────────────────────┘
                                   │
                                   ▼
┌──────────────────────────────────────────────────────────────────────┐
│  3. BunnyNetClient::createVideo($title)                              │
│     POST https://video.bunnycdn.com/library/510939/videos            │
│     Headers: AccessKey: {api_key}                                    │
│     Body: {"title": "Módulo 1 - Introducción"}                      │
│                                                                       │
│     Respuesta:                                                        │
│     {                                                                 │
│       "guid": "abc123-def456",                                       │
│       "status": 0,                                                   │
│       "videoLibraryId": 510939                                       │
│     }                                                                 │
└──────────────────────────────────────────────────────────────────────┘
                                   │
                                   ▼
┌──────────────────────────────────────────────────────────────────────┐
│  4. BunnyNetClient::uploadVideo($videoId, $file)                     │
│     PUT https://video.bunnycdn.com/library/510939/videos/abc123...   │
│     Headers: AccessKey: {api_key}                                    │
│     Body: [binary video file]                                        │
│                                                                       │
│     Respuesta: {"success": true, "message": "OK"}                    │
└──────────────────────────────────────────────────────────────────────┘
                                   │
                                   ▼
┌──────────────────────────────────────────────────────────────────────┐
│  5. Laravel guarda en base de datos:                                 │
│     modules.bunny_video_id = 'abc123-def456'                         │
│     modules.video_path = null                                        │
└──────────────────────────────────────────────────────────────────────┘
                                   │
                                   ▼
┌──────────────────────────────────────────────────────────────────────┐
│  6. Bunny.net procesa el video en segundo plano:                     │
│     - Convierte a múltiples resoluciones (240p, 360p, 720p, 1080p)  │
│     - Genera playlist HLS                                            │
│     - Crea thumbnail automático                                      │
│     - Cambia status: 0 → 1 → 2 → 3 (Listo)                          │
└──────────────────────────────────────────────────────────────────────┘
                                   │
                                   ▼
┌──────────────────────────────────────────────────────────────────────┐
│  7. Usuario visualiza el módulo:                                     │
│     - Blade renderiza <x-video-player :module="$module" />          │
│     - Componente detecta usesBunny() = true                          │
│     - Obtiene $module->video_embed_url                               │
│     - Renderiza iframe con URL:                                      │
│       https://iframe.mediadelivery.net/embed/510939/abc123-def456    │
└──────────────────────────────────────────────────────────────────────┘
                                   │
                                   ▼
┌──────────────────────────────────────────────────────────────────────┐
│  8. Bunny.net CDN entrega el video:                                  │
│     - Usuario recibe video desde el nodo CDN más cercano             │
│     - Streaming adaptativo según ancho de banda                      │
│     - Sin carga en el servidor Laravel                               │
└──────────────────────────────────────────────────────────────────────┘
```

---

### Flujo 2: Subida Directa de Video (Frontend → Bunny.net)

```
┌──────────────────────────────────────────────────────────────────────┐
│              ADMIN: Editar Carpeta con Subida Directa                │
└──────────────────────────────────────────────────────────────────────┘
                                   │
                                   ▼
┌──────────────────────────────────────────────────────────────────────┐
│  1. Admin selecciona archivo de video en el input file               │
│     - JavaScript valida tipo y tamaño                                │
│     - Detecta IS_BUNNY = true                                        │
│     - Llama a uploadDirectToBunny(file)                              │
└──────────────────────────────────────────────────────────────────────┘
                                   │
                                   ▼
┌──────────────────────────────────────────────────────────────────────┐
│  2. JavaScript: Inicializar subida                                   │
│     fetch('/admin/folders/{id}/init-direct-upload', {               │
│       method: 'POST',                                                │
│       body: JSON.stringify({title: 'Video de la carpeta'})          │
│     })                                                               │
└──────────────────────────────────────────────────────────────────────┘
                                   │
                                   ▼
┌──────────────────────────────────────────────────────────────────────┐
│  3. Laravel Backend (FolderController::initDirectUpload)             │
│     - Verifica VIDEO_STORAGE_DRIVER = 'bunny'                       │
│     - Crea video en Bunny: BunnyNetClient::createVideo()            │
│     - Retorna al frontend:                                           │
│       {                                                              │
│         "success": true,                                             │
│         "video_id": "abc123-def456",                                 │
│         "upload_url": "https://video.bunnycdn.com/library/...",     │
│         "api_key": "9efef787-..."                                    │
│       }                                                              │
└──────────────────────────────────────────────────────────────────────┘
                                   │
                                   ▼
┌──────────────────────────────────────────────────────────────────────┐
│  4. JavaScript: Subir archivo DIRECTAMENTE a Bunny.net              │
│     const xhr = new XMLHttpRequest();                                │
│     xhr.open('PUT', upload_url);                                     │
│     xhr.setRequestHeader('AccessKey', api_key);                      │
│     xhr.send(file);                                                  │
│                                                                       │
│     // El archivo NUNCA pasa por el servidor Laravel                │
│     // Va directamente del navegador a Bunny.net                    │
└──────────────────────────────────────────────────────────────────────┘
                                   │
                                   ▼
┌──────────────────────────────────────────────────────────────────────┐
│  5. JavaScript: Monitorear progreso de subida                        │
│     xhr.upload.addEventListener('progress', (e) => {                 │
│       const percent = (e.loaded / e.total) * 100;                   │
│       updateProgress(percent, e.loaded, e.total);                    │
│     });                                                              │
│                                                                       │
│     - Actualiza barra de progreso en tiempo real                     │
│     - Muestra velocidad de subida (MB/s)                             │
│     - Muestra tiempo estimado restante                               │
└──────────────────────────────────────────────────────────────────────┘
                                   │
                                   ▼
┌──────────────────────────────────────────────────────────────────────┐
│  6. JavaScript: Confirmar subida con Laravel                         │
│     fetch('/admin/folders/{id}/confirm-direct-upload', {            │
│       method: 'POST',                                                │
│       body: JSON.stringify({video_id: 'abc123-def456'})             │
│     })                                                               │
└──────────────────────────────────────────────────────────────────────┘
                                   │
                                   ▼
┌──────────────────────────────────────────────────────────────────────┐
│  7. Laravel Backend (FolderController::confirmDirectUpload)          │
│     - Actualiza base de datos:                                       │
│       folders.bunny_video_id = 'abc123-def456'                       │
│       folders.video_path = null                                      │
│     - Retorna confirmación al frontend                               │
└──────────────────────────────────────────────────────────────────────┘
                                   │
                                   ▼
┌──────────────────────────────────────────────────────────────────────┐
│  8. Frontend: Mostrar mensaje de éxito y recargar página            │
│     showSuccess();                                                   │
│     setTimeout(() => window.location.reload(), 2000);                │
└──────────────────────────────────────────────────────────────────────┘
```

**Ventajas de la subida directa**:
- No consume ancho de banda del servidor Laravel
- Más rápida (conexión directa a CDN)
- No requiere configuración especial de PHP
- Soporta archivos muy grandes (hasta 5GB)

---

### Flujo 3: Reproducción de Video (Usuario)

```
┌──────────────────────────────────────────────────────────────────────┐
│              USUARIO: Ver Módulo de Curso con Video                  │
└──────────────────────────────────────────────────────────────────────┘
                                   │
                                   ▼
┌──────────────────────────────────────────────────────────────────────┐
│  1. Usuario accede a la página del módulo                            │
│     GET /user/courses/{course}/modules/{module}                      │
└──────────────────────────────────────────────────────────────────────┘
                                   │
                                   ▼
┌──────────────────────────────────────────────────────────────────────┐
│  2. Controlador carga el módulo desde DB:                            │
│     $module = Module::find($moduleId);                               │
│     // $module->bunny_video_id = 'abc123-def456'                     │
│     // $module->video_path = null                                    │
└──────────────────────────────────────────────────────────────────────┘
                                   │
                                   ▼
┌──────────────────────────────────────────────────────────────────────┐
│  3. Vista Blade renderiza el componente:                             │
│     <x-video-player :module="$module" />                             │
└──────────────────────────────────────────────────────────────────────┘
                                   │
                                   ▼
┌──────────────────────────────────────────────────────────────────────┐
│  4. Componente video-player.blade.php ejecuta:                       │
│     $usesBunny = $module->usesBunny();  // true                      │
│     $videoUrl = $module->video_embed_url;                            │
└──────────────────────────────────────────────────────────────────────┘
                                   │
                                   ▼
┌──────────────────────────────────────────────────────────────────────┐
│  5. Model Module ejecuta accessor:                                   │
│     public function getVideoEmbedUrlAttribute()                      │
│     {                                                                 │
│         $videoService = app(VideoStorageService::class);             │
│         return $videoService->getVideoUrl(                           │
│             $this->video_path,                                       │
│             $this->bunny_video_id,                                   │
│             'embed'                                                  │
│         );                                                           │
│     }                                                                 │
└──────────────────────────────────────────────────────────────────────┘
                                   │
                                   ▼
┌──────────────────────────────────────────────────────────────────────┐
│  6. VideoStorageService retorna:                                     │
│     https://iframe.mediadelivery.net/embed/510939/abc123-def456      │
└──────────────────────────────────────────────────────────────────────┘
                                   │
                                   ▼
┌──────────────────────────────────────────────────────────────────────┐
│  7. HTML renderizado:                                                 │
│     <iframe                                                          │
│         src="https://iframe.mediadelivery.net/embed/510939/abc123"  │
│         allowfullscreen>                                             │
│     </iframe>                                                        │
└──────────────────────────────────────────────────────────────────────┘
                                   │
                                   ▼
┌──────────────────────────────────────────────────────────────────────┐
│  8. Navegador del usuario carga el iframe de Bunny.net              │
│     - Bunny.net detecta ubicación geográfica del usuario             │
│     - Selecciona el servidor CDN más cercano                         │
│     - Detecta ancho de banda disponible                              │
│     - Entrega resolución apropiada (720p, 1080p, etc.)              │
└──────────────────────────────────────────────────────────────────────┘
                                   │
                                   ▼
┌──────────────────────────────────────────────────────────────────────┐
│  9. Usuario reproduce el video:                                      │
│     - Streaming adaptativo (HLS)                                     │
│     - Cambio automático de calidad según conexión                    │
│     - Sin carga en el servidor Laravel                               │
│     - Controles del reproductor de Bunny.net                         │
└──────────────────────────────────────────────────────────────────────┘
```

---

## Base de Datos

### Migración: add_bunny_video_id_to_modules_and_folders_tables

**Ubicación**: `database/migrations/2025_10_14_000119_add_bunny_video_id_to_modules_and_folders_tables.php`

```php
Schema::table('modules', function (Blueprint $table) {
    $table->string('bunny_video_id')->nullable()->after('video_path');
});

Schema::table('folders', function (Blueprint $table) {
    $table->string('bunny_video_id')->nullable()->after('video_path');
});
```

### Estructura de Tablas

**modules**:

| Campo           | Tipo         | Descripción                              |
|-----------------|--------------|------------------------------------------|
| id              | bigint       | ID del módulo                            |
| course_id       | bigint       | ID del curso al que pertenece            |
| title           | varchar(255) | Título del módulo                        |
| description     | text         | Descripción                              |
| video_path      | varchar(255) | Ruta local del video (NULL si es Bunny)  |
| bunny_video_id  | varchar(255) | ID del video en Bunny.net (NULL si local)|
| order_index     | int          | Orden del módulo en el curso             |

**folders**:

| Campo              | Tipo         | Descripción                              |
|--------------------|--------------|------------------------------------------|
| id                 | bigint       | ID de la carpeta                         |
| parent_folder_id   | bigint       | ID de carpeta padre (NULL si es raíz)    |
| name               | varchar(255) | Nombre de la carpeta                     |
| type               | varchar(50)  | Tipo: 'iso' o 'law'                      |
| video_path         | varchar(255) | Ruta local del video (NULL si es Bunny)  |
| bunny_video_id     | varchar(255) | ID del video en Bunny.net (NULL si local)|
| video_title        | varchar(255) | Título del video                         |
| video_description  | text         | Descripción del video                    |
| order_index        | int          | Orden de la carpeta                      |

### Métodos del Modelo Module

**Ubicación**: `app/Models/Module.php`

```php
// Accessor: URL de streaming
public function getVideoUrlAttribute(): ?string
{
    $videoService = app(\App\Services\VideoStorageService::class);
    return $videoService->getVideoUrl($this->video_path, $this->bunny_video_id);
}

// Accessor: URL del iframe embed
public function getVideoEmbedUrlAttribute(): ?string
{
    $videoService = app(\App\Services\VideoStorageService::class);
    return $videoService->getVideoUrl($this->video_path, $this->bunny_video_id, 'embed');
}

// Accessor: URL del thumbnail
public function getVideoThumbnailUrlAttribute(): ?string
{
    $videoService = app(\App\Services\VideoStorageService::class);
    return $videoService->getThumbnailUrl($this->video_path, $this->bunny_video_id);
}

// Verificar si el video está listo
public function isVideoReady(): bool
{
    $videoService = app(\App\Services\VideoStorageService::class);
    return $videoService->isVideoReady($this->video_path, $this->bunny_video_id);
}

// Verificar si usa Bunny.net
public function usesBunny(): bool
{
    return !empty($this->bunny_video_id);
}
```

---

## Métodos de Subida

### Comparación de Métodos

| Característica             | Subida Tradicional (Backend) | Subida Directa (Frontend → Bunny) |
|----------------------------|------------------------------|-----------------------------------|
| Flujo de datos             | Cliente → Laravel → Bunny    | Cliente → Bunny (Laravel solo coordina) |
| Ancho de banda del servidor| Consume el doble (entrada + salida) | No consume (solo peticiones API) |
| Límite de tamaño PHP       | Afectado por upload_max_filesize | No afectado |
| Tiempo de espera PHP       | Afectado por max_execution_time | No afectado |
| Velocidad                  | Más lenta                     | Más rápida (conexión directa al CDN) |
| Progreso en tiempo real    | Sí                            | Sí |
| Configuración              | Requiere ajustes PHP          | No requiere ajustes especiales |
| Tamaño máximo práctico     | ~500MB (con ajustes PHP)      | ~5GB (límite de Bunny.net) |
| Seguridad                  | Laravel valida antes de subir | Laravel valida en frontend + confirma después |

### Cuándo Usar Cada Método

**Subida Tradicional (Backend)**:
- Videos pequeños (<100MB)
- Storage local
- Control total de validación antes de subir

**Subida Directa (Frontend → Bunny)**:
- Videos grandes (>100MB)
- Storage en Bunny.net
- Servidor con recursos limitados
- Mejor experiencia de usuario (más rápido)

---

## Configuración de Producción

### 1. Variables de Entorno

```env
# Activar modo Bunny.net
VIDEO_STORAGE_DRIVER=bunny

# Credenciales de Bunny.net
BUNNY_LIBRARY_ID=510939
BUNNY_API_KEY=tu-api-key-real
BUNNY_CDN_HOSTNAME=vz-xxxxx-xxx.b-cdn.net
BUNNY_STREAM_API_URL=https://video.bunnycdn.com
```

### 2. Configuración PHP (Solo para subida tradicional)

Si usas subida tradicional, ajusta `/etc/php/8.x/fpm/php.ini`:

```ini
upload_max_filesize = 500M
post_max_size = 500M
max_execution_time = 600
memory_limit = 512M
```

**Reiniciar PHP-FPM**:

```bash
sudo systemctl restart php8.2-fpm
```

### 3. Configuración Nginx (Solo para subida tradicional)

Si usas subida tradicional, ajusta `/etc/nginx/sites-available/tu-sitio`:

```nginx
server {
    # ...

    client_max_body_size 500M;
    client_body_timeout 600s;

    # ...
}
```

**Reiniciar Nginx**:

```bash
sudo systemctl restart nginx
```

### 4. Optimizaciones

**Cache de configuración**:

```bash
php artisan config:cache
php artisan route:cache
```

**Queue Workers** (opcional, para procesamiento asíncrono):

```bash
# Iniciar worker para procesar jobs en segundo plano
php artisan queue:work --tries=3
```

---

## Troubleshooting

### Problema: Video no se reproduce

**Síntomas**: El iframe de Bunny.net no carga o muestra error.

**Solución**:

1. Verificar que el video haya terminado de procesar:

```php
$module = Module::find($id);
dd($module->isVideoReady()); // Debe ser true
```

2. Verificar el estado del video en Bunny.net:

```php
$bunnyClient = new BunnyNetClient();
$videoData = $bunnyClient->getVideo($module->bunny_video_id);
dd($videoData['status']); // Debe ser 3 o 4
```

3. Verificar la URL generada:

```php
dd($module->video_embed_url);
// Debe ser: https://iframe.mediadelivery.net/embed/{library_id}/{video_id}
```

---

### Problema: Error al subir video grande

**Síntomas**: Error 413 (Request Entity Too Large) o timeout.

**Solución**:

1. **Usar subida directa a Bunny.net** (recomendado):

```env
VIDEO_STORAGE_DRIVER=bunny
```

La subida directa evita pasar por el servidor Laravel.

2. **Si usas subida tradicional**, aumentar límites:

```ini
# php.ini
upload_max_filesize = 1000M
post_max_size = 1000M
max_execution_time = 1200
```

```nginx
# nginx.conf
client_max_body_size 1000M;
```

---

### Problema: Credenciales inválidas

**Síntomas**: Error "Failed to create video in Bunny.net" o código 401.

**Solución**:

1. Verificar credenciales en `.env`:

```bash
php artisan tinker
>>> config('video.bunny.api_key')
>>> config('video.bunny.library_id')
```

2. Verificar que la API Key tiene permisos correctos en el panel de Bunny.net:
   - Ir a https://panel.bunny.net/stream
   - Seleccionar tu biblioteca de videos
   - Verificar API Key en "API" tab

---

### Problema: Video local no funciona después de cambiar a Bunny

**Síntomas**: Videos antiguos con `video_path` no se reproducen.

**Solución**:

Esto es normal. El sistema detecta automáticamente el tipo de almacenamiento:

```php
// Videos antiguos (local)
$module->video_path = 'videos/courses/1/video.mp4';
$module->bunny_video_id = null;
// Funcionan con Storage::url()

// Videos nuevos (Bunny)
$module->video_path = null;
$module->bunny_video_id = 'abc123-def456';
// Funcionan con Bunny.net CDN
```

No es necesario migrar videos antiguos si funcionan correctamente.

---

## Resumen de Endpoints

### Backend (Laravel)

| Método | Ruta | Controlador | Descripción |
|--------|------|-------------|-------------|
| POST | `/admin/folders/{folder}/upload-video` | FolderController@uploadVideo | Subida tradicional (backend) |
| POST | `/admin/folders/{folder}/init-direct-upload` | FolderController@initDirectUpload | Iniciar subida directa |
| POST | `/admin/folders/{folder}/confirm-direct-upload` | FolderController@confirmDirectUpload | Confirmar subida directa |
| DELETE | `/admin/folders/{folder}/delete-video` | FolderController@deleteVideo | Eliminar video |

### Bunny.net API

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| POST | `https://video.bunnycdn.com/library/{library_id}/videos` | Crear objeto de video |
| PUT | `https://video.bunnycdn.com/library/{library_id}/videos/{video_id}` | Subir archivo de video |
| GET | `https://video.bunnycdn.com/library/{library_id}/videos/{video_id}` | Obtener info del video |
| DELETE | `https://video.bunnycdn.com/library/{library_id}/videos/{video_id}` | Eliminar video |

### URLs de Reproducción

| Tipo | URL |
|------|-----|
| Iframe Embed | `https://iframe.mediadelivery.net/embed/{library_id}/{video_id}` |
| HLS Playlist | `https://{cdn_hostname}/{video_id}/playlist.m3u8` |
| MP4 Directo | `https://{cdn_hostname}/{video_id}/play_{video_id}.mp4` |
| Thumbnail | `https://{cdn_hostname}/{video_id}/thumbnail.jpg` |

---

## Conclusión

Esta integración ofrece:

✓ **Flexibilidad**: Soporte para almacenamiento local o Bunny.net CDN
✓ **Escalabilidad**: CDN global con streaming optimizado
✓ **Performance**: Subida directa sin pasar por el servidor
✓ **Experiencia de Usuario**: Progreso en tiempo real, streaming adaptativo
✓ **Facilidad de Migración**: Cambio transparente entre local y Bunny.net

Para más información, consulta:
- [Documentación de Bunny.net Stream](https://docs.bunny.net/docs/stream)
- [API Reference de Bunny.net](https://docs.bunny.net/reference/video-library-api)
