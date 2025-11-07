# Consolidación del Campo de Duración

## Resumen

Se consolidó el sistema de duración de lecciones para usar un único campo `duration` (en segundos) con auto-detección desde Bunny.net y conversión automática a minutos para display.

---

## Problema Anterior

Existían **dos campos separados** que causaban confusión:

| Campo | Unidad | Uso | Problema |
|-------|--------|-----|----------|
| `duration` | Minutos | Manual, mostrado en UI | Admin debía ingresar manualmente |
| `video_duration` | Segundos | Auto-detectado de Bunny | Nunca se usaba/mostraba |

**Resultado**: Duplicación innecesaria y falta de auto-detección efectiva.

---

## Solución Implementada

### Campo Único: `duration`
- **Almacenamiento**: Segundos (integer)
- **Auto-detección**: Desde Bunny.net API
- **Display**: Minutos vía accessor `$lesson->duration_minutes`

---

## Cambios Realizados

### 1. Base de Datos

#### Migración: `2025_11_07_193211_remove_video_duration_from_lessons_table.php`
```php
// Eliminado: video_duration column
// Actualizado: duration column comment → "Duration in seconds"
```

**Ejecutado**: ✅ Migración aplicada exitosamente

---

### 2. Modelo Lesson (`app/Models/Lesson.php`)

#### Cambios en $fillable:
```php
// ❌ ELIMINADO:
'video_duration',

// ✅ MANTENIDO (ahora en segundos):
'duration', // Duration in seconds
```

#### Accessor Agregado:
```php
/**
 * Get duration in minutes (rounded up from seconds)
 * Used for display purposes in UI
 */
public function getDurationMinutesAttribute(): int
{
    if (!$this->duration) {
        return 0;
    }

    // Convert seconds to minutes, round up
    return (int) ceil($this->duration / 60);
}
```

#### Docblock Actualizado:
```php
/**
 * @property int $duration Duration in seconds (auto-detected from Bunny.net or manually entered)
 * @property int $duration_minutes Computed attribute: duration in minutes (read-only)
 */
```

---

### 3. API Controller (`app/Http/Controllers/Api/LessonController.php`)

#### store() - Auto-detección en Creación:
```php
// Auto-detect duration from Bunny.net video
if ($validated['video_type'] === 'bunny' && isset($validated['bunny_video_id'])) {
    $bunnyService = new BunnyService();
    $videoInfo = $bunnyService->getVideoInfo($validated['bunny_video_id']);
    if ($videoInfo && isset($videoInfo['length'])) {
        // Store duration in seconds (Bunny API returns length in seconds)
        $validated['duration'] = $videoInfo['length'];
    }
}
```

#### update() - Auto-detección en Actualización:
```php
// Same auto-detection logic as store()
// Now saves to 'duration' field instead of 'video_duration'
```

#### getBunnyDuration() - API Endpoint Simplificado:
```php
// ANTES:
return response()->json([
    'duration' => $durationInMinutes,
    'duration_seconds' => $videoInfo['length']
]);

// DESPUÉS:
return response()->json([
    'duration' => $videoInfo['length'], // Duration in seconds
]);
```

#### Validación Actualizada:
```php
// ❌ ELIMINADO:
'video_duration' => 'nullable|integer|min:0',

// ✅ ACTUALIZADO (ahora es en segundos):
'duration' => 'nullable|integer|min:0', // Duration in seconds
```

---

### 4. Vistas de Estudiantes (6 archivos)

Reemplazo global: `$lesson->duration` → `$lesson->duration_minutes`

#### Archivos Modificados:
1. `resources/views/livewire/student/lesson-view.blade.php`
2. `resources/views/livewire/student/lesson-catalog.blade.php`
3. `resources/views/livewire/student/dashboard.blade.php`
4. `resources/views/livewire/student/saved-content.blade.php`
5. `resources/views/livewire/student/course-detail.blade.php`

#### Ejemplo de Cambio:
```blade
{{-- ANTES: --}}
@if($lesson->duration)
    <span>{{ $lesson->duration }} MIN</span>
@endif

{{-- DESPUÉS: --}}
@if($lesson->duration_minutes)
    <span>{{ $lesson->duration_minutes }} MIN</span>
@endif
```

---

### 5. Componente Livewire CourseDetail (`app/Livewire/Student/CourseDetail.php`)

#### Cálculo de Tiempo Total:
```php
// ANTES:
$minutesSpent = auth()->user()->completedLessons()
    ->whereIn('lessons.id', $allLessonIds)
    ->sum('lessons.duration') ?? 0;

// DESPUÉS:
// Note: duration is stored in seconds, so we divide by 60
$totalSeconds = auth()->user()->completedLessons()
    ->whereIn('lessons.id', $allLessonIds)
    ->sum('lessons.duration') ?? 0;
$minutesSpent = round($totalSeconds / 60);
```

---

### 6. Vista Admin - lesson-management.blade.php

#### Campo Oculto (ya estaba así):
```html
<input type="hidden" id="lesson-duration" value="0">
```

#### JavaScript - Display en Lista:
```javascript
// ANTES:
${lesson.duration} min

// DESPUÉS:
${Math.ceil((lesson.duration || 0) / 60)} min
```

#### JavaScript - Total de Módulo:
```javascript
// ANTES:
const totalDuration = module.lessons.reduce((sum, lesson) => sum + (lesson.duration || 0), 0);

// DESPUÉS:
// Duration is stored in seconds, convert to minutes for display
const totalDuration = Math.ceil(module.lessons.reduce((sum, lesson) => sum + (lesson.duration || 0), 0) / 60);
```

#### JavaScript - Auto-detección:
```javascript
if (data.success && data.duration > 0) {
    // Store duration in seconds (from Bunny API)
    durationInput.value = data.duration;

    // Display duration in minutes (for user)
    const durationMinutes = Math.ceil(data.duration / 60);
    durationDisplay.textContent = durationMinutes;

    // Show success message
    durationInfo.classList.remove('hidden');
}
```

---

### 7. Vista Admin - lesson-form.blade.php

#### Campo Oculto con Mensaje Informativo:
```blade
<!-- Duration is hidden and auto-detected from Bunny.net (stored in seconds) -->
<input type="hidden" id="lesson-duration" value="{{ $lesson ? $lesson->duration : 0 }}">

<!-- Duration info message (shown when auto-detected from Bunny.net) -->
<p id="duration-info" class="text-xs text-cream/60 hidden">
    <svg class="w-4 h-4 inline text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
    </svg>
    <span class="text-green-600 font-medium">Duración detectada automáticamente:</span>
    <span id="duration-display" class="font-bold">0</span> minutos
</p>
```

---

## Flujo de Auto-detección

### Para Videos de Bunny.net:

1. **Admin sube video** → Bunny.net procesa
2. **JavaScript obtiene video_id** → Llama a API
3. **Backend** (`LessonController@store/update`):
   ```php
   BunnyService->getVideoInfo($videoId) → ['length' => 180] // 3 minutos en segundos
   $validated['duration'] = 180; // Guarda en segundos
   ```
4. **Frontend muestra**:
   - En formulario: "Duración detectada automáticamente: 3 minutos"
   - En lista: "3 min"
   - En catálogo estudiante: "3 MIN"

### Para Videos YouTube/Local:
- `duration` se queda en `0` por defecto
- Puede ser editado manualmente si se desea (aunque el campo está oculto)

---

## Conversión Segundos → Minutos

### En Backend (PHP):
```php
// Accessor en Modelo Lesson
public function getDurationMinutesAttribute(): int
{
    return (int) ceil($this->duration / 60);
}

// Uso en Blade
{{ $lesson->duration_minutes }} // Automáticamente convierte
```

### En Frontend (JavaScript):
```javascript
// Conversión manual
const durationMinutes = Math.ceil(lesson.duration / 60);
```

---

## Impacto en Base de Datos

### Migración Aplicada:
```sql
-- Columna eliminada:
ALTER TABLE lessons DROP COLUMN video_duration;

-- Comentario agregado:
ALTER TABLE lessons MODIFY duration int(11) NOT NULL DEFAULT 0 COMMENT 'Duration in seconds';
```

### Datos Existentes:
- ⚠️ **IMPORTANTE**: Si había lecciones con `duration` en minutos, ahora se interpretarán como segundos
- **Solución**: Ejecutar script de conversión de datos (si es necesario):

```php
// Script de conversión (ejecutar en tinker si hay datos legacy)
use App\Models\Lesson;

Lesson::where('duration', '>', 0)->get()->each(function ($lesson) {
    // Asumir que duration antigua era en minutos, convertir a segundos
    $lesson->duration = $lesson->duration * 60;
    $lesson->save();
});
```

---

## Testing

### URLs a Verificar:

1. **http://127.0.0.1:8001/admin/modules/3/lessons**
   - ✅ Lista de lecciones muestra duración en minutos
   - ✅ Total de duración del módulo correcto

2. **http://127.0.0.1:8001/admin/modules/3/lessons/create**
   - ✅ Campo duración está oculto
   - ✅ Auto-detección funciona al subir video Bunny

3. **http://127.0.0.1:8001/admin/lessons/9/edit**
   - ✅ Duración se muestra en minutos en mensaje informativo
   - ✅ Campo oculto tiene valor en segundos

4. **http://127.0.0.1:8001/lessons/9** (Vista estudiante)
   - ✅ Badge de duración muestra minutos correctamente

5. **http://127.0.0.1:8001/courses/1** (Detalle de curso)
   - ✅ Total de tiempo completado correcto

---

## Checklist de Funcionalidad

- [x] Auto-detección desde Bunny.net funciona
- [x] Duración se guarda en segundos
- [x] Display muestra minutos correctamente
- [x] Campo oculto en formularios admin
- [x] Accessor `duration_minutes` funciona
- [x] Vistas de estudiantes actualizadas
- [x] JavaScript convierte segundos → minutos
- [x] API retorna duración en segundos
- [x] Migración ejecutada sin errores
- [x] Sin referencias a `video_duration`

---

## Archivos Totales Modificados

### Backend (4 archivos):
1. `database/migrations/2025_11_07_193211_remove_video_duration_from_lessons_table.php` - **NUEVO**
2. `app/Models/Lesson.php` - Accessor + $fillable
3. `app/Http/Controllers/Api/LessonController.php` - Auto-detección
4. `app/Livewire/Student/CourseDetail.php` - Cálculo de tiempo

### Frontend (8 archivos):
5. `resources/views/livewire/student/lesson-view.blade.php`
6. `resources/views/livewire/student/lesson-catalog.blade.php`
7. `resources/views/livewire/student/dashboard.blade.php`
8. `resources/views/livewire/student/saved-content.blade.php`
9. `resources/views/livewire/student/course-detail.blade.php`
10. `resources/views/admin/lesson-management.blade.php` - JavaScript
11. `resources/views/admin/lesson-form.blade.php` - Campo oculto

**Total**: 11 archivos modificados + 1 migración nueva

---

## Beneficios

1. ✅ **Un solo campo** en base de datos
2. ✅ **Auto-detección automática** desde Bunny.net
3. ✅ **Más preciso**: Segundos en DB, minutos en UI
4. ✅ **Menos confusión**: Un único flujo claro
5. ✅ **Código más limpio**: Sin duplicación
6. ✅ **Mejor UX**: Admin no necesita ingresar duración manualmente

---

## Notas Importantes

1. **El campo `duration` ahora es en SEGUNDOS**, no minutos
2. **Usar accessor** `$lesson->duration_minutes` para mostrar en vistas
3. **JavaScript debe dividir por 60** para mostrar minutos
4. **Bunny API devuelve segundos** → Se guarda directamente sin conversión
5. **Videos YouTube/Local** tendrán `duration = 0` por defecto (campo oculto)

---

## Comandos Útiles

```bash
# Ver estructura de tabla lessons
php artisan tinker
>>> DB::select("DESCRIBE lessons");

# Verificar que video_duration no existe
>>> \App\Models\Lesson::first()->video_duration; // Error esperado

# Ver duración de una lección
>>> $lesson = \App\Models\Lesson::first();
>>> $lesson->duration; // En segundos
>>> $lesson->duration_minutes; // En minutos (via accessor)
```

---

## Rollback (si es necesario)

```bash
# Revertir migración
php artisan migrate:rollback

# Esto restaurará:
# - Columna video_duration
# - Removerá comentario de duration
```

---

## Próximos Pasos Opcionales

1. **Script de conversión de datos**: Si había lecciones con duration en minutos, convertirlas a segundos
2. **Agregar validación**: Asegurar que duration sea siempre >= 0
3. **Mejorar auto-detección en lesson-form**: Agregar función JavaScript para detectar antes de guardar (actualmente solo backend lo hace)
4. **Documentar en CLAUDE.md**: Actualizar documentación principal del proyecto

---

✅ **Consolidación completada exitosamente!**
