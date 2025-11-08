# Flujo de Subida de Video a Bunny.net

## 📹 Proceso Automático de Subida

Cuando un admin sube un video al crear una lección, el siguiente proceso ocurre automáticamente:

```
Usuario selecciona video
         ↓
1. POST /admin/lessons/bunny/init-upload
   - Crea video en Bunny.net
   - Devuelve video_id y upload_url
         ↓
2. PUT a Bunny.net (directo desde navegador)
   - Sube el archivo de video
   - Muestra barra de progreso (0-100%)
         ↓
3. POST /admin/lessons/bunny/confirm-upload
   - Confirma que la subida finalizó
         ↓
4. POST /admin/lessons/bunny/duration (NUEVO ✨)
   - Obtiene duración del video desde Bunny.net
   - Auto-llena el campo "duration" en el formulario
         ↓
Usuario hace click en "Publicar" o "Guardar borrador"
   - Lección se guarda con video_id y duration
```

---

## 🎯 Endpoints Utilizados

### 1. **Init Upload** (Inicializar)
```http
POST /admin/lessons/bunny/init-upload
```
**Request:**
```json
{
  "title": "nombre-del-video.mp4"
}
```
**Response:**
```json
{
  "success": true,
  "video_id": "abc123-def456-ghi789",
  "library_id": "12345",
  "upload_url": "https://video.bunnycdn.com/library/12345/videos/abc123",
  "api_key": "bunny-api-key"
}
```

---

### 2. **Upload Video** (Subida directa a Bunny.net)
```http
PUT https://video.bunnycdn.com/library/{library_id}/videos/{video_id}
Headers:
  AccessKey: {api_key}
Body: [binary video file]
```
**Progress:** Se monitorea con `xhr.upload.addEventListener('progress')`

---

### 3. **Confirm Upload** (Confirmar)
```http
POST /admin/lessons/bunny/confirm-upload
```
**Request:**
```json
{
  "video_id": "abc123-def456-ghi789"
}
```
**Response:**
```json
{
  "success": true,
  "message": "Upload confirmed"
}
```

---

### 4. **Get Duration** (Obtener duración) ✨ NUEVO
```http
POST /admin/lessons/bunny/duration
```
**Request:**
```json
{
  "video_id": "abc123-def456-ghi789"
}
```
**Response:**
```json
{
  "success": true,
  "duration": 245  // En segundos (4:05)
}
```

---

## 💾 Datos Guardados en BD

Cuando se guarda la lección, estos campos se almacenan:

```php
Lesson::create([
    'bunny_video_id' => 'abc123-def456-ghi789',  // Del paso 1
    'duration' => 245,                            // Del paso 4 (automático)
    'video_type' => 'bunny',
    'is_published' => true/false,
    // ... otros campos
]);
```

---

## 🔄 Auto-llenado de Duración

**Antes:**
- ❌ El admin tenía que ingresar manualmente la duración
- ❌ Podía haber errores o inconsistencias

**Ahora:**
- ✅ La duración se obtiene automáticamente de Bunny.net
- ✅ Se llena el campo `form.duration` usando `@this.set()`
- ✅ El admin solo necesita revisar y publicar

---

## 🛠️ Implementación Técnica

### Frontend (lesson-create.blade.php)
```javascript
// Después de confirmar la subida
const durationResponse = await fetch('/admin/lessons/bunny/duration', {
    method: 'POST',
    body: JSON.stringify({ video_id: currentVideoId })
});

const durationData = await durationResponse.json();

if (durationData.success && durationData.duration) {
    // Auto-llenar campo duration en Livewire
    @this.set('form.duration', durationData.duration);
}
```

### Backend (BunnyUploadController.php)
```php
public function getBunnyDuration(Request $request)
{
    $videoId = $request->input('video_id');
    $bunnyService = new BunnyService();
    $videoInfo = $bunnyService->getVideoInfo($videoId);

    return response()->json([
        'success' => true,
        'duration' => $videoInfo['length'] // En segundos
    ]);
}
```

### BunnyService (BunnyService.php)
```php
public function getVideoInfo($videoId)
{
    $response = Http::withHeaders([
        'AccessKey' => config('bunny.api_key')
    ])->get("https://video.bunnycdn.com/library/{$libraryId}/videos/{$videoId}");

    return $response->json();
}
```

---

## 📊 Mejoras Implementadas

| Aspecto | Antes | Ahora |
|---------|-------|-------|
| **Duración del video** | Manual | ✅ Automática |
| **Precisión** | Puede tener errores | ✅ Exacta (desde Bunny.net) |
| **UX** | Admin debe buscar duración | ✅ Auto-llenado |
| **Endpoint usado** | 2 de 3 | ✅ 3 de 3 |

---

## 🧪 Testing

Para probar el flujo completo:

1. Ir a http://127.0.0.1:8001/admin/lessons/create
2. Seleccionar un video
3. Esperar a que suba (ver barra de progreso)
4. Ver mensaje "✓ Video subido exitosamente"
5. **Verificar que el campo `duration` se llenó automáticamente**
6. Completar otros campos
7. Click en "Publicar" o "Guardar borrador"

---

## 🔍 Logs y Debugging

Para ver si la duración se obtuvo correctamente:

```javascript
// En la consola del navegador verás:
// "Duración obtenida: 245 segundos"
```

Si hay error:
```javascript
// "No se pudo obtener la duración automáticamente: [error]"
// (No es crítico, la lección se puede guardar sin duración)
```

---

**Última actualización:** 2025-11-08
**Implementado por:** Claude (Anthropic)
