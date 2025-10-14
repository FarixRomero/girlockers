# Sistema de Upload de Videos con Bunny.net

## Arquitectura

El sistema implementa un upload directo y seguro desde el navegador a Bunny.net CDN usando firmas de autenticación generadas en Laravel.

## Flujo de Upload

### 1. **Crear Video (Backend Laravel)**
```php
// app/Services/BunnyService.php
$bunnyService = new BunnyService();
$result = $bunnyService->createVideo('Título del Video');

// Retorna:
[
    'video_id' => 'abc-123-def',
    'library_id' => '510152',
    'upload_url' => 'https://video.bunnycdn.com/library/510152/videos/abc-123-def',
    'signature' => 'hash_sha256_generado',
    'expiration_time' => 1234567890,
    'api_key' => 'backup_fallback'
]
```

### 2. **Generar Firma de Autenticación**
Laravel genera una firma SHA256 única para cada upload:

```php
// Fórmula de la firma
$signature = hash('sha256', $libraryId . $apiKey . $expirationTime . $videoId);
```

**Ventajas:**
- ✅ No expone la API Key en el navegador
- ✅ Tiempo de expiración (1 hora por defecto)
- ✅ Firma única por video
- ✅ Más seguro que enviar la API Key directamente

### 3. **Upload desde el Navegador (JavaScript)**
```javascript
const xhr = new XMLHttpRequest();

// Headers de autenticación con firma
xhr.setRequestHeader('AuthorizationSignature', signature);
xhr.setRequestHeader('AuthorizationExpire', expirationTime);
xhr.setRequestHeader('LibraryId', libraryId);

xhr.send(videoFile);
```

### 4. **Verificación en Bunny.net**
Bunny.net recibe:
- `AuthorizationSignature`: Firma generada
- `AuthorizationExpire`: Timestamp de expiración
- `LibraryId`: ID de la biblioteca

Bunny verifica que:
1. La firma sea válida
2. No haya expirado
3. El video pertenezca a la biblioteca correcta

### 5. **Respuesta del Upload**
- **200/201**: Video subido exitosamente
- **401**: Firma inválida o expirada
- **400**: Error en los datos

## Métodos Disponibles en BunnyService

### `createVideo($title)`
Crea un video vacío en Bunny.net y genera la firma de autenticación.

### `generateUploadSignature($videoId, $expirationTime = 3600)`
Genera una firma SHA256 para autenticación segura.

### `uploadVideo($videoId, $filePath)`
Sube un video directamente desde Laravel (alternativa al upload desde navegador).

### `getVideoInfo($videoId)`
Obtiene información del video (duración, estado, etc.).

### `deleteVideo($videoId)`
Elimina un video de Bunny.net.

## Configuración

### .env
```env
BUNNY_LIBRARY_ID=510152
BUNNY_API_KEY=tu-api-key-aqui
BUNNY_CDN_HOSTNAME=vz-c4d26094-bf6.b-cdn.net
BUNNY_STREAM_URL=https://video.bunnycdn.com
```

### config/bunny.php
```php
return [
    'library_id' => env('BUNNY_LIBRARY_ID'),
    'api_key' => env('BUNNY_API_KEY'),
    'cdn_hostname' => env('BUNNY_CDN_HOSTNAME'),
    'stream_url' => env('BUNNY_STREAM_URL', 'https://video.bunnycdn.com'),
];
```

## Ejemplo de Uso Completo

### Frontend (Livewire + JavaScript)
```javascript
// 1. Crear video y obtener firma
const createResult = await @this.call('createBunnyVideo');

// 2. Upload directo con firma
const xhr = new XMLHttpRequest();
xhr.open('PUT', createResult.upload_url, true);
xhr.setRequestHeader('AuthorizationSignature', createResult.signature);
xhr.setRequestHeader('AuthorizationExpire', createResult.expiration_time);
xhr.setRequestHeader('LibraryId', createResult.library_id);

xhr.addEventListener('load', async () => {
    if (xhr.status === 200 || xhr.status === 201) {
        // 3. Sincronizar video_id con Livewire ANTES de habilitar botón
        await @this.set('bunny_video_id', createResult.video_id);

        // 4. Habilitar botón de submit
        submitBtn.disabled = false;
    }
});

xhr.send(videoFile);
```

### Backend (Laravel)
```php
// LessonManagement.php
public function createBunnyVideo()
{
    $bunnyService = new BunnyService();
    $result = $bunnyService->createVideo($this->title);

    if ($result) {
        $this->bunny_video_id = $result['video_id'];
        return $result;
    }

    return ['error' => 'Error al crear el video'];
}

// NO hay validación de bunny_video_id en rules()
// El botón de submit está deshabilitado en el frontend hasta que termine el upload
```

## Seguridad

### ✅ Implementado
- Firmas SHA256 únicas por video
- Expiración de firmas (1 hora)
- API Key nunca se expone al cliente
- Upload directo a CDN (no pasa por Laravel)

### 🔒 Mejoras Implementadas
- ✅ Botón de submit deshabilitado hasta que se complete el upload
- ✅ Sincronización con Livewire usando `@this.set()` antes de habilitar botón
- ✅ Validación de formulario para prevenir envío prematuro
- ✅ Mensajes de advertencia en UI

### 🔒 Mejoras Futuras
- [ ] Rate limiting por IP
- [ ] Validación de tipo de archivo antes de crear video
- [ ] Webhook de Bunny.net para verificar upload exitoso
- [ ] Limpieza automática de videos no completados

## Troubleshooting

### Error: "El campo bunny video id es obligatorio"
**Causa**: El formulario se envió antes de que el video completara la subida o antes de que Livewire sincronizara el ID.

**Solución implementada**:
1. El botón de submit se deshabilita automáticamente cuando se selecciona tipo Bunny
2. El botón permanece deshabilitado durante todo el upload
3. Se usa `await @this.set()` para sincronizar el video_id con Livewire
4. El botón se habilita SOLO después de sincronizar exitosamente
5. Se agregó validación en `onsubmit` para prevenir envío si el botón está deshabilitado

**Debug**:
```javascript
// Abrir la consola del navegador (F12) y verificar:
// - "Video creado en Bunny.net: [video_id]"
// - "Video subido exitosamente. Sincronizando con Livewire..."
// - "bunny_video_id sincronizado: [video_id]"
// - "Botón de submit habilitado"
```

### Error 401: Unauthorized
- Verificar que la firma se genera correctamente
- Verificar que no haya expirado (1 hora máximo)
- Verificar que library_id sea correcto

### Error 400: Bad Request
- Verificar tipo de archivo (debe ser video)
- Verificar que el video_id exista en Bunny.net

### Upload Lento
- El upload es directo al CDN de Bunny.net
- La velocidad depende de la conexión del usuario
- Mostrar barra de progreso para mejor UX

## Referencias
- [Bunny.net Stream API Documentation](https://docs.bunny.net/reference/video_library)
- [Upload Signature Authentication](https://docs.bunny.net/docs/stream-authentication)
