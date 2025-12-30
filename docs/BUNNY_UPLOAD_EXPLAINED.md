# Bunny.net Upload Flow - Explicación Detallada

## 🎯 Pregunta: ¿Qué hace initUpload?

**Respuesta:** Sí, exactamente. `initUpload` **crea un "placeholder" vacío** en Bunny.net, y luego el archivo de video se sube para **"llenar" ese placeholder**.

---

## 📊 Flujo Completo Paso a Paso

### Paso 1: `initUpload` - Crear Video Vacío (Placeholder)

**Llamada a Laravel:**
```javascript
POST /admin/lessons/bunny/init-upload
Body: { "title": "mi-video.mp4" }
```

**Laravel llama a Bunny.net API:**
```http
POST https://video.bunnycdn.com/library/12345/videos
Headers:
  AccessKey: bunny-api-key
  Content-Type: application/json
Body:
{
  "title": "mi-video.mp4"
}
```

**Bunny.net responde:**
```json
{
  "guid": "abc123-def456-ghi789",  // ← Video ID creado
  "title": "mi-video.mp4",
  "videoLibraryId": 12345,
  "length": 0,                      // ← Sin contenido aún
  "status": 0,                      // ← Procesando/Esperando
  // ... más campos
}
```

**Lo que Laravel devuelve al frontend:**
```json
{
  "success": true,
  "video_id": "abc123-def456-ghi789",
  "library_id": "12345",
  "upload_url": "https://video.bunnycdn.com/library/12345/videos/abc123-def456-ghi789",
  "api_key": "bunny-api-key"
}
```

**En este momento:**
- ✅ Video creado en Bunny.net
- ❌ Pero está VACÍO (sin archivo)
- ❌ No tiene duración
- ❌ No se puede reproducir
- ✅ Tiene un ID único (GUID)

---

### Paso 2: Upload - Subir el Archivo de Video (Rellenar Placeholder)

**Llamada directa desde el navegador a Bunny.net:**
```javascript
xhr.open('PUT', 'https://video.bunnycdn.com/library/12345/videos/abc123-def456-ghi789');
xhr.setRequestHeader('AccessKey', 'bunny-api-key');
xhr.send(videoFile);  // ← Archivo binario del video
```

**Características:**
- 📤 El archivo NO pasa por Laravel (sube directo a Bunny.net)
- 📊 Se monitorea el progreso en tiempo real (0% → 100%)
- 🔒 Usa el `upload_url` y `api_key` del paso 1
- ⚡ Más rápido porque es directo navegador → Bunny.net

**Respuesta de Bunny.net:**
```
200 OK
(El video ahora tiene contenido)
```

**En este momento:**
- ✅ Video tiene archivo
- ⏳ Bunny.net está procesando el video
- ⏳ Generando thumbnails, diferentes resoluciones, etc.

---

### Paso 3: `confirmUpload` - Confirmar que Subió

**Llamada a Laravel:**
```javascript
POST /admin/lessons/bunny/confirm-upload
Body: { "video_id": "abc123-def456-ghi789" }
```

**Función:**
- ✅ Confirma que la subida terminó
- ✅ Registra log
- ℹ️ Puede hacer validaciones adicionales si es necesario

**En este momento:**
- ✅ Subida confirmada
- ✅ Video procesándose en Bunny.net

---

### Paso 4: `getBunnyDuration` - Obtener Duración (NUEVO ✨)

**Llamada a Laravel:**
```javascript
POST /admin/lessons/bunny/duration
Body: { "video_id": "abc123-def456-ghi789" }
```

**Laravel llama a Bunny.net API:**
```http
GET https://video.bunnycdn.com/library/12345/videos/abc123-def456-ghi789
Headers:
  AccessKey: bunny-api-key
```

**Bunny.net responde:**
```json
{
  "guid": "abc123-def456-ghi789",
  "title": "mi-video.mp4",
  "length": 245,                    // ← Duración en segundos (4:05)
  "status": 4,                      // ← Procesado
  "thumbnailFileName": "thumbnail.jpg",
  // ... más info
}
```

**Laravel extrae y devuelve:**
```json
{
  "success": true,
  "duration": 245
}
```

**Frontend auto-llena:**
```javascript
@this.set('form.duration', 245);  // ← Campo duration = 4:05 minutos
```

**En este momento:**
- ✅ Video completamente procesado
- ✅ Duración conocida
- ✅ Listo para guardar en BD

---

## 🔄 Resumen Visual

```
┌─────────────────────────────────────────────────────────────────┐
│ PASO 1: initUpload - Crear Placeholder                         │
├─────────────────────────────────────────────────────────────────┤
│ Frontend → Laravel → Bunny.net                                  │
│                                                                 │
│ Bunny.net crea:                                                 │
│   {                                                             │
│     "guid": "abc123",                                           │
│     "title": "video.mp4",                                       │
│     "length": 0,           ← SIN CONTENIDO                     │
│     "status": 0            ← ESPERANDO                          │
│   }                                                             │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ PASO 2: Upload - Rellenar con Archivo                          │
├─────────────────────────────────────────────────────────────────┤
│ Frontend → Bunny.net (DIRECTO, sin pasar por Laravel)          │
│                                                                 │
│ PUT [archivo de video 50MB]                                    │
│ Progreso: ████████████████░░ 85%                               │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ PASO 3: confirmUpload - Confirmar                              │
├─────────────────────────────────────────────────────────────────┤
│ Frontend → Laravel                                              │
│                                                                 │
│ Log: "Upload confirmed for video abc123"                       │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ PASO 4: getBunnyDuration - Obtener Info                        │
├─────────────────────────────────────────────────────────────────┤
│ Frontend → Laravel → Bunny.net                                  │
│                                                                 │
│ Bunny.net ahora tiene:                                          │
│   {                                                             │
│     "guid": "abc123",                                           │
│     "title": "video.mp4",                                       │
│     "length": 245,         ← CON DURACIÓN                      │
│     "status": 4            ← PROCESADO ✓                       │
│   }                                                             │
│                                                                 │
│ Auto-llena: form.duration = 245                                 │
└─────────────────────────────────────────────────────────────────┘
```

---

## 🤔 ¿Por qué este flujo?

### Ventajas del flujo "placeholder primero":

1. **Upload Directo a CDN**
   - ✅ El video NO pasa por tu servidor Laravel
   - ✅ Ahorra ancho de banda de tu servidor
   - ✅ Más rápido (navegador → Bunny.net directamente)

2. **ID Temprano**
   - ✅ Tienes el `video_id` antes de subir
   - ✅ Puedes mostrar progreso con el ID
   - ✅ Si la subida falla, ya tienes el ID para reintentar

3. **Escalabilidad**
   - ✅ Tu servidor no procesa archivos grandes
   - ✅ Bunny.net maneja todo el procesamiento
   - ✅ Múltiples usuarios pueden subir simultáneamente

4. **Seguridad**
   - ✅ Laravel valida y autoriza primero
   - ✅ Genera credenciales temporales para la subida
   - ✅ El usuario solo puede subir a SU video

---

## 🔒 Flujo de Seguridad

```
1. Usuario autenticado en Laravel (debe ser admin)
   ↓
2. Laravel crea video en Bunny.net
   ↓
3. Laravel devuelve upload_url + api_key TEMPORAL
   ↓
4. Usuario solo puede subir a ESE video específico
   ↓
5. Laravel confirma la subida
```

**Sin pasar por Laravel, el usuario:**
- ❌ No tiene acceso directo al API de Bunny.net
- ❌ No conoce las credenciales permanentes
- ❌ Solo puede subir a videos que Laravel creó para él

---

## 📝 Comparación: Flujo Alternativo (menos eficiente)

### Flujo alternativo (NO usado):
```
Usuario selecciona video
    ↓
Frontend → Laravel (upload 50MB)
    ↓
Laravel → Bunny.net (upload 50MB)
    ↓
Bunny.net procesa
    ↓
Laravel devuelve video_id
```

**Problemas:**
- ❌ El video pasa 2 veces por la red
- ❌ Laravel debe manejar archivos grandes
- ❌ Más lento
- ❌ Más costoso en ancho de banda

### Flujo actual (usado):
```
Usuario selecciona video
    ↓
Frontend → Laravel (solo metadata)
    ↓
Frontend → Bunny.net (upload directo 50MB)
    ↓
Frontend → Laravel (confirmar)
```

**Ventajas:**
- ✅ Video viaja solo 1 vez
- ✅ Laravel solo maneja JSON
- ✅ Más rápido
- ✅ Más barato

---

## 🎬 Analogía

**Imagina que Bunny.net es un almacén de videos:**

1. **initUpload** = Reservar un casillero vacío
   - Te dan el número del casillero: `abc123`
   - El casillero existe pero está vacío

2. **Upload** = Llenar el casillero con tu video
   - Vas directamente al almacén
   - Pones tu video en el casillero `abc123`

3. **confirmUpload** = Avisar que ya llenaste el casillero
   - "Ya puse el video en el casillero abc123"

4. **getBunnyDuration** = Preguntar info del casillero
   - "¿Cuánto dura el video del casillero abc123?"
   - Respuesta: "4 minutos y 5 segundos"

---

**Última actualización:** 2025-11-08
**Autor:** Claude (Anthropic)
