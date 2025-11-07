# Sistema de Gestión de Lecciones

## Arquitectura Actual

El sistema de lecciones usa una **arquitectura híbrida**:
- **Frontend**: Formulario tradicional con JavaScript (`admin/lesson-form.blade.php`)
- **Backend**: API RESTful (`App\Http\Controllers\Api\LessonController`)
- **Gestión de listado**: Livewire Component (`ModuleManagement`)

## Flujo de Trabajo

### 1. Ver Lecciones
```
admin/courses/{id}/modules
  └─> ModuleManagement (Livewire)
      └─> Muestra módulos con botón "Lecciones" para cada módulo
```

### 2. Crear Nueva Lección
```
Click "Nueva Lección" en ModuleManagement
  └─> admin/modules/{id}/lessons/create
      └─> Renderiza: resources/views/admin/lesson-form.blade.php
          └─> Al guardar: POST a admin/api/lessons/store
              └─> Redirige a: admin/courses/{courseId}/modules
```

### 3. Editar Lección
```
Click "Editar" en lista de lecciones
  └─> admin/lessons/{id}/edit
      └─> Renderiza: resources/views/admin/lesson-form.blade.php
          └─> Al guardar: PUT a admin/api/lessons/{id}
              └─> Redirige a: admin/courses/{courseId}/modules
```

## Rutas Importantes

### Vistas (GET)
| Ruta | Propósito | Vista/Componente |
|------|-----------|------------------|
| `admin/courses/{id}/modules` | Lista de módulos y lecciones | `ModuleManagement.php` (Livewire) |
| `admin/modules/{id}/lessons/create` | Crear nueva lección | `admin/lesson-form.blade.php` |
| `admin/lessons/{id}/edit` | Editar lección existente | `admin/lesson-form.blade.php` |
| `admin/modules/{id}/lessons` | Redirige a módulos (ruta legacy) | Redirect |

### API Endpoints (POST/PUT/DELETE)
| Ruta | Método | Controlador | Propósito |
|------|--------|-------------|-----------|
| `admin/api/lessons` | POST | `LessonController@store` | Crear lección |
| `admin/api/lessons/{id}` | PUT | `LessonController@update` | Actualizar lección |
| `admin/api/lessons/{id}` | DELETE | `LessonController@destroy` | Eliminar lección |
| `admin/api/lessons/{id}/move-up` | POST | `LessonController@moveUp` | Mover orden arriba |
| `admin/api/lessons/{id}/move-down` | POST | `LessonController@moveDown` | Mover orden abajo |
| `admin/api/lessons/{id}/toggle-trial` | POST | `LessonController@toggleTrial` | Toggle gratuita/premium |
| `admin/api/upload-thumbnail` | POST | `LessonController@uploadThumbnail` | Subir thumbnail |
| `admin/api/lessons/bunny/duration` | POST | `LessonController@getBunnyDuration` | **Auto-detectar duración** |

### Bunny.net Upload
| Ruta | Método | Controlador | Propósito |
|------|--------|-------------|-----------|
| `admin/lessons/bunny/init-upload` | POST | `BunnyUploadController@initUpload` | Inicializar subida |
| `admin/lessons/bunny/confirm-upload` | POST | `BunnyUploadController@confirmUpload` | Confirmar subida |

## Características Especiales

### Detección Automática de Duración
Cuando se sube un video a Bunny.net, el sistema **automáticamente detecta la duración**:

1. **Durante la subida**:
   - Usuario selecciona video
   - JavaScript sube directamente a Bunny.net
   - Al completar, llama a `getBunnyDuration()` API
   - Actualiza campo oculto `lesson-duration`

2. **Al editar lección existente**:
   - Al cargar el formulario, si existe `bunny_video_id`
   - Automáticamente llama a `getBunnyDuration()` API
   - Muestra mensaje: "Duración detectada automáticamente: X minutos"

**Código relevante:**
- Vista: `resources/views/admin/lesson-form.blade.php` líneas 194-213, 555-582
- API: `app/Http/Controllers/Api/LessonController.php` líneas 293-331
- JavaScript: `LessonFormManager.fetchBunnyVideoDuration()`

### Tipos de Video Soportados
1. **Bunny.net** (recomendado): CDN con auto-detección de duración
2. **YouTube**: Requiere YouTube ID manualmente
3. **Local**: Deshabilitado actualmente

## Archivos Clave

### Frontend
- `resources/views/admin/lesson-form.blade.php` - Formulario de crear/editar lección
- `resources/views/livewire/admin/module-management.blade.php` - Lista de módulos/lecciones

### Backend
- `app/Http/Controllers/Api/LessonController.php` - CRUD de lecciones
- `app/Http/Controllers/Admin/BunnyUploadController.php` - Subida de videos
- `app/Livewire/Admin/ModuleManagement.php` - Componente de gestión

### Configuración
- `routes/web.php` líneas 54-135 - Todas las rutas de admin
- `config/bunny.php` - Configuración de Bunny.net CDN

## Mantenimiento

### Si necesitas cambiar el flujo:
1. **Cambiar vista de lista**: Editar `ModuleManagement.php` y su blade
2. **Cambiar formulario**: Editar `admin/lesson-form.blade.php`
3. **Cambiar lógica de guardado**: Editar `LessonController@store` y `LessonController@update`
4. **Agregar campos**:
   - Agregar en migración/modelo
   - Agregar en formulario blade
   - Agregar en validación del controller
   - Agregar en JavaScript `saveLesson()`

### Debugging
- **Errores de subida**: Ver logs en `storage/logs/laravel.log`
- **Problemas de duración**: Verificar `getBunnyDuration()` en consola del navegador
- **Errores de API**: Usar Network tab en DevTools

## Notas Importantes
- ❌ **NO existe** un componente `LessonManagement.php` (fue eliminado)
- ✅ La gestión de lecciones usa formulario tradicional + API
- ✅ El listado de lecciones está dentro de `ModuleManagement`
- ✅ La duración se detecta automáticamente para videos de Bunny.net
- ✅ El campo de duración está oculto en el formulario
