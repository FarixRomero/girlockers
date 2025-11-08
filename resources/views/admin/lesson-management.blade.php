@extends('layouts.admin')

@section('title', 'Gestión de Lecciones - Admin')

@section('content')
<div id="app" class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-3xl mx-auto px-4 py-4">
            <a href="{{ route('admin.courses.modules', ':course_id') }}" id="back-link" class="inline-flex items-center text-purple-500 hover:text-purple-600 text-sm font-medium mb-3">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Volver
            </a>
            <h1 class="font-bold text-xl md:text-2xl text-gray-900 mb-1" id="module-title">Cargando...</h1>
            <p class="text-gray-600 text-sm">
                <span id="course-title">...</span> • <span id="lessons-count">0</span> lecciones
            </p>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 py-6 pb-24">
        <!-- Alert Messages -->
        <div id="alert-container" class="hidden mb-4"></div>

        <!-- Lessons List -->
        <div id="lessons-container" class="space-y-3">
            <div class="text-center py-12 bg-white rounded-2xl">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-500 mx-auto"></div>
                <p class="text-gray-500 mt-4">Cargando lecciones...</p>
            </div>
        </div>

        <!-- Nueva Lección Button (Fixed Bottom) -->
        <div class="fixed bottom-4 left-0 right-0 px-4 z-40">
            <div class="max-w-3xl mx-auto">
                <a href="{{ route('admin.lessons.create', $moduleId) }}" class="w-full flex items-center justify-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-2xl shadow-lg font-semibold transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nueva Lección
                </a>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="lesson-modal" class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden items-center justify-center z-50" style="display: none;">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-hidden">
            <div class="bg-gradient-to-r from-purple-primary to-purple-light px-8 py-6 flex items-center justify-between">
                <h3 class="font-display text-2xl font-bold text-white" id="modal-title">Nueva Lección</h3>
                <button onclick="LessonManager.closeModal()" class="text-white/80 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Alert Messages dentro del Modal -->
            <div id="modal-alert-container" class="hidden mx-8 mt-6"></div>

            <form id="lesson-form" onsubmit="LessonManager.saveLesson(event)" class="px-8 py-6 space-y-5 bg-gray-ultralight/30 overflow-y-auto" style="max-height: calc(90vh - 200px);">
                <!-- Title -->
                <div>
                    <label class="block text-gray-dark text-sm font-bold mb-2">Título *</label>
                    <input type="text" id="lesson-title" required class="w-full">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-gray-dark text-sm font-bold mb-2">Descripción *</label>
                    <textarea id="lesson-description" rows="3" required class="w-full resize-none"></textarea>
                </div>

                <!-- Instructor -->
                <div>
                    <label class="block text-gray-dark text-sm font-bold mb-2">Instructor</label>
                    <select id="lesson-instructor" class="w-full">
                        <option value="">Sin instructor asignado</option>
                        @foreach($instructors as $instructor)
                            <option value="{{ $instructor->id }}">{{ $instructor->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tags -->
                <div>
                    <label class="block text-gray-dark text-sm font-bold mb-2">Tags</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($tags as $tag)
                            <label class="flex items-center p-3 bg-white border border-gray-light rounded-lg hover:border-purple-primary/50 cursor-pointer transition-colors">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="w-4 h-4 text-purple-primary bg-white border-gray-light rounded focus:ring-purple-primary focus:ring-2">
                                <span class="ml-2 text-gray-dark text-sm">{{ $tag->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Thumbnail/Image -->
                <div>
                    <label class="block text-gray-dark text-sm font-bold mb-2">Imagen/Thumbnail</label>
                    <div class="border-2 border-dashed border-purple-primary/30 rounded-lg p-4 text-center hover:border-purple-primary/50 transition-colors bg-white">
                        <input type="file" id="lesson-thumbnail-input" accept="image/*" onchange="LessonManager.handleThumbnailUpload(event)" class="hidden">
                        <button type="button" onclick="document.getElementById('lesson-thumbnail-input').click()" class="px-6 py-3 bg-purple-primary hover:bg-purple-dark text-white font-bold rounded-lg shadow-sm transition-all">
                            Seleccionar imagen
                        </button>
                        <p class="text-gray-medium text-xs mt-2">Formatos: JPG, PNG, WebP (Recomendado: 1280x720px)</p>

                        <!-- Preview -->
                        <div id="thumbnail-preview" class="hidden mt-4">
                            <img id="thumbnail-preview-img" src="" alt="Preview" class="max-w-full h-40 mx-auto rounded-lg object-cover">
                            <button type="button" onclick="LessonManager.removeThumbnail()" class="text-red-600 hover:text-red-700 text-sm mt-2 font-medium">
                                Eliminar imagen
                            </button>
                        </div>

                        <input type="hidden" id="lesson-thumbnail">
                    </div>
                </div>

                <!-- Video Type -->
                <div>
                    <label class="block text-gray-dark text-sm font-bold mb-2">Tipo de Video</label>
                    <select id="lesson-video-type" onchange="LessonManager.updateVideoFields()" class="w-full">
                        <option value="bunny">Bunny.net (CDN)</option>
                        <option value="youtube">YouTube</option>
                        <option value="local">Local</option>
                    </select>
                </div>

                <!-- YouTube ID -->
                <div id="youtube-field" class="hidden">
                    <label class="block text-gray-dark text-sm font-bold mb-2">YouTube Video ID</label>
                    <input type="text" id="lesson-youtube-id" class="w-full">
                    <p class="text-gray-medium text-xs mt-2">ID del video (ej: dQw4w9WgXcQ de youtube.com/watch?v=dQw4w9WgXcQ)</p>
                </div>

                <!-- Bunny Upload -->
                <div id="bunny-field" class="hidden">
                    <label class="block text-gray-dark text-sm font-bold mb-2">Video de Bunny.net</label>

                    <!-- Video Preview (when editing) -->
                    <div id="bunny-video-preview" class="hidden mb-4">
                        <div class="bg-black rounded-lg overflow-hidden" style="position: relative; padding-top: 56.25%;">
                            <iframe id="bunny-video-iframe"
                                    style="border: none; position: absolute; top: 0; height: 100%; width: 100%;"
                                    allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;"
                                    allowfullscreen="true">
                            </iframe>
                        </div>
                        <p class="text-gray-medium text-sm mt-2">Video actual - ID: <span id="bunny-video-id-display"></span></p>
                    </div>

                    <div class="border-2 border-dashed border-purple-primary/30 rounded-lg p-6 text-center hover:border-purple-primary/50 transition-colors bg-white">
                        <input type="file" id="bunny-file-input" accept="video/*" onchange="LessonManager.handleBunnyUpload(event)" class="hidden">
                        <button type="button" onclick="document.getElementById('bunny-file-input').click()" class="px-6 py-3 bg-purple-primary hover:bg-purple-dark text-white font-bold rounded-lg shadow-sm transition-all" id="bunny-select-button">
                            <span id="bunny-button-text">Seleccionar archivo de video</span>
                        </button>
                        <p class="text-gray-medium text-xs mt-2">Soporta: MP4, MOV, AVI, WMV</p>

                        <!-- Upload Progress -->
                        <div id="upload-progress-container" class="hidden mt-4">
                            <div class="w-full bg-gray-light rounded-full h-2 mb-2">
                                <div id="upload-progress-bar" class="bg-gradient-to-r from-purple-primary to-purple-light h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                            <p class="text-gray-dark text-sm">
                                <span id="upload-percentage">0%</span> -
                                <span id="upload-speed">0 MB/s</span> -
                                <span id="upload-eta">Calculando...</span>
                            </p>
                            <button type="button" onclick="LessonManager.cancelUpload()" class="text-red-600 hover:text-red-700 text-sm mt-2 font-medium">
                                Cancelar subida
                            </button>
                        </div>

                        <!-- Upload Success -->
                        <div id="upload-success" class="hidden mt-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-green-700 text-sm flex items-center justify-center font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Video subido exitosamente
                            </p>
                        </div>

                        <input type="hidden" id="lesson-bunny-video-id">
                    </div>
                </div>

                <!-- Local File (disabled for now) -->
                <div id="local-field" class="hidden">
                    <label class="block text-gray-dark text-sm font-bold mb-2">Archivo Local</label>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-yellow-700 text-sm">Funcionalidad en desarrollo. Usa Bunny.net para mejor rendimiento.</p>
                    </div>
                </div>

                <!-- Duration (hidden, auto-detected) & Order -->
                <div class="grid grid-cols-1 gap-4">
                    <!-- Duration is hidden and auto-detected from Bunny.net -->
                    <input type="hidden" id="lesson-duration" value="0">

                    <div>
                        <label class="block text-gray-dark text-sm font-bold mb-2">Orden</label>
                        <input type="number" id="lesson-order" min="1" required class="w-full">
                    </div>

                    <!-- Duration info message (shown when auto-detected) -->
                    <p id="duration-info" class="text-xs text-gray-600 hidden">
                        <svg class="w-4 h-4 inline text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-green-600 font-medium">Duración detectada automáticamente:</span>
                        <span id="duration-display" class="font-bold">0</span> minutos
                    </p>
                </div>

                <!-- Is Trial -->
                <div class="flex items-center bg-white p-4 rounded-lg border border-gray-light">
                    <input type="checkbox" id="lesson-is-trial" class="w-5 h-5 text-purple-primary bg-white border-gray-light rounded focus:ring-purple-primary focus:ring-2">
                    <label for="lesson-is-trial" class="ml-3 text-gray-dark text-sm font-medium cursor-pointer">Lección gratuita (Trial)</label>
                </div>
            </form>

            <!-- Form Actions -->
            <div class="px-8 py-6 border-t border-gray-light bg-white flex justify-end space-x-3">
                <button type="button" onclick="LessonManager.closeModal()" class="px-6 py-3 bg-gray-ultralight text-gray-dark font-medium rounded-lg hover:bg-gray-light transition-colors">
                    Cancelar
                </button>
                <button type="submit" form="lesson-form" id="submit-button" class="px-6 py-3 bg-purple-primary hover:bg-purple-dark text-white font-bold rounded-lg shadow-sm hover:shadow transition-all">
                    Guardar Lección
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
/**
 * Lesson Manager - Gestión de lecciones con JavaScript puro
 * Soporta navegación Livewire y carga directa de página
 * Wrapped in IIFE to avoid redeclaration errors on SPA navigation
 */
(function() {
    'use strict';

// ============================================================================
// CONFIGURACIÓN
// ============================================================================
window.LessonManagerConfig = {
    moduleId: {{ $moduleId }},
    csrfToken: '{{ csrf_token() }}',
    bunnyCdnHostname: '{{ config('bunny.cdn_hostname') }}',
    bunnyLibraryId: '{{ config('bunny.library_id') }}',
    routes: {
        lessons: {
            index: '/admin/api/modules/:moduleId/lessons',
            create: '{{ route('admin.lessons.create', ['moduleId' => ':moduleId']) }}',
            store: '{{ route('admin.api.lessons.store') }}',
            show: '{{ route('admin.api.lessons.show', ['id' => ':id']) }}',
            update: '{{ route('admin.api.lessons.update', ['id' => ':id']) }}',
            destroy: '{{ route('admin.api.lessons.destroy', ['id' => ':id']) }}',
            moveUp: '{{ route('admin.api.lessons.move-up', ['id' => ':id']) }}',
            moveDown: '{{ route('admin.api.lessons.move-down', ['id' => ':id']) }}',
            toggleTrial: '{{ route('admin.api.lessons.toggle-trial', ['id' => ':id']) }}'
        },
        bunny: {
            init: '{{ route('admin.lessons.bunny.init') }}',
            confirm: '{{ route('admin.lessons.bunny.confirm') }}'
        }
    }
};

const CONFIG = window.LessonManagerConfig;

// ============================================================================
// LESSON MANAGER
// ============================================================================
window.LessonManager = window.LessonManager || {
    // Estado
    currentModule: null,
    lessons: [],
    editingLessonId: null,
    uploadXhr: null,
    uploadStartTime: 0,
    uploadStartLoaded: 0,

    // ========================================================================
    // INICIALIZACIÓN Y CARGA DE DATOS
    // ========================================================================

    /**
     * Inicializa el gestor de lecciones
     */
    async init() {
        await this.loadLessons();
    },

    /**
     * Carga las lecciones del módulo desde la API
     */
    async loadLessons() {
        try {
            const url = CONFIG.routes.lessons.index.replace(':moduleId', CONFIG.moduleId);
            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CONFIG.csrfToken
                }
            });

            const data = await response.json();

            if (data.success) {
                this.currentModule = data.module;
                this.lessons = data.module.lessons || [];
                this.renderModule();
                this.renderLessons();
            } else {
                this.showAlert('error', 'Error al cargar las lecciones');
            }
        } catch (error) {
            console.error('Error loading lessons:', error);
            this.showAlert('error', 'Error de conexión al cargar las lecciones');
        }
    },

    // ========================================================================
    // RENDERIZADO DE INTERFAZ
    // ========================================================================

    /**
     * Renderiza la información del módulo en el encabezado
     */
    renderModule() {
        const module = this.currentModule;
        const courseId = module.course_id;
        const backUrl = '{{ route('admin.courses.modules', ['courseId' => ':courseId']) }}'.replace(':courseId', courseId);

        // Actualizar títulos
        document.getElementById('module-title').textContent = `Lecciones de ${module.title}`;
        document.getElementById('course-title').textContent = module.course.title;

        // Actualizar contadores
        document.getElementById('lessons-count').textContent = module.lessons.length;

        // Actualizar enlace de navegación
        document.getElementById('back-link').href = backUrl;
    },

    /**
     * Renderiza la lista de lecciones
     */
    renderLessons() {
        const container = document.getElementById('lessons-container');

        if (this.lessons.length === 0) {
            container.innerHTML = `
                <div class="bg-white rounded-2xl text-center py-12">
                    <p class="text-gray-500 text-sm mb-4">No hay lecciones aún</p>
                    <a href="${CONFIG.routes.lessons.create.replace(':moduleId', CONFIG.moduleId)}" class="inline-flex items-center px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg text-sm font-medium transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Crear primera lección
                    </a>
                </div>
            `;
            return;
        }

        container.innerHTML = this.lessons.map(lesson => this.renderLessonCard(lesson)).join('');
    },

    /**
     * Renderiza una tarjeta de lección individual
     */
    renderLessonCard(lesson) {
        const videoTypeLabel = {
            youtube: 'YouTube',
            bunny: 'Bunny.net',
            local: 'Local'
        }[lesson.video_type] || lesson.video_type;

        const thumbnailUrl = lesson.thumbnail
            ? `/storage/${lesson.thumbnail}`
            : 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="160" height="90" viewBox="0 0 160 90"%3E%3Crect fill="%23e5e7eb" width="160" height="90"/%3E%3Ctext fill="%236b7280" font-family="Arial" font-size="12" x="50%25" y="50%25" text-anchor="middle" dominant-baseline="middle"%3ESin Imagen%3C/text%3E%3C/svg%3E';

        return `
            <div class="bg-white rounded-2xl p-4 relative group">
                <div class="flex items-center gap-3 relative z-10">
                    <!-- Lesson Info (Clickable to edit) -->
                    <a href="/admin/lessons/${lesson.id}/edit" class="flex-1 min-w-0 flex items-center gap-3">
                        <!-- Thumbnail -->
                        <div class="w-20 h-14 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                            <img src="${thumbnailUrl}" alt="${this.escapeHtml(lesson.title)}"
                                class="w-full h-full object-cover"
                                onerror="this.style.display='none';this.parentElement.style.backgroundColor='#e5e7eb';this.parentElement.innerHTML='<div class=\\'flex items-center justify-center h-full text-gray-400 text-xs\\'>Sin Imagen</div>'">
                        </div>

                        <!-- Title & Info -->
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-base text-gray-900 line-clamp-1">
                                ${this.escapeHtml(lesson.title)}
                            </h3>
                            <p class="text-sm text-gray-500 line-clamp-1">
                                ${lesson.is_trial ? 'Gratis' : 'Premium'}
                                ${lesson.duration > 0 ? ' • ' + Math.ceil((lesson.duration || 0) / 60) + ' min' : ''}
                            </p>
                        </div>
                    </a>

                    <!-- Actions -->
                    <div class="flex items-center gap-0.5 md:gap-1">
                        <!-- Move Up -->
                        ${lesson.order > 1
                            ? `<button onclick="event.stopPropagation(); LessonManager.moveUp(${lesson.id})" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            </button>`
                            : ''
                        }

                        <!-- Move Down -->
                        ${lesson.order < this.lessons.length
                            ? `<button onclick="event.stopPropagation(); LessonManager.moveDown(${lesson.id})" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>`
                            : ''
                        }

                        <!-- Toggle Trial -->
                        <button onclick="event.stopPropagation(); LessonManager.toggleTrial(${lesson.id})" class="p-2 text-${lesson.is_trial ? 'green' : 'orange'}-500 hover:bg-gray-50 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </button>

                        <!-- Delete -->
                        <button onclick="event.stopPropagation(); LessonManager.deleteLesson(${lesson.id}, '${this.escapeHtml(lesson.title)}')" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        `;
    },

    // ========================================================================
    // GESTIÓN DE MODALES
    // ========================================================================

    /**
     * Abre el modal para crear una nueva lección
     */
    openCreateModal() {
        this.editingLessonId = null;
        this.thumbnailFile = null;
        document.getElementById('modal-title').textContent = 'Nueva Lección';
        document.getElementById('lesson-form').reset();
        document.getElementById('lesson-order').value = this.lessons.length + 1;
        document.getElementById('lesson-video-type').value = 'bunny';
        this.removeThumbnail();
        this.hideBunnyVideoPreview();
        this.updateVideoFields();
        this.showModal();
    },

    /**
     * Abre el modal para editar una lección existente
     */
    async openEditModal(lessonId) {
        this.editingLessonId = lessonId;
        this.thumbnailFile = null;
        const lesson = this.lessons.find(l => l.id === lessonId);

        if (!lesson) {
            this.showAlert('error', 'Lección no encontrada');
            return;
        }

        document.getElementById('modal-title').textContent = 'Editar Lección';
        document.getElementById('lesson-title').value = lesson.title;
        document.getElementById('lesson-description').value = lesson.description;
        document.getElementById('lesson-instructor').value = lesson.instructor_id || '';
        document.getElementById('lesson-video-type').value = lesson.video_type;
        document.getElementById('lesson-youtube-id').value = lesson.youtube_id || '';
        document.getElementById('lesson-bunny-video-id').value = lesson.bunny_video_id || '';
        document.getElementById('lesson-duration').value = lesson.duration || 0;
        document.getElementById('lesson-order').value = lesson.order;
        document.getElementById('lesson-is-trial').checked = lesson.is_trial;

        // Limpiar todos los checkboxes primero
        document.querySelectorAll('input[name="tags[]"]').forEach(checkbox => {
            checkbox.checked = false;
        });

        // Marcar los tags seleccionados
        if (lesson.tags && lesson.tags.length > 0) {
            lesson.tags.forEach(tag => {
                const checkbox = document.querySelector(`input[name="tags[]"][value="${tag.id}"]`);
                if (checkbox) {
                    checkbox.checked = true;
                }
            });
        }

        // Cargar thumbnail si existe
        if (lesson.thumbnail) {
            document.getElementById('lesson-thumbnail').value = lesson.thumbnail;
            document.getElementById('thumbnail-preview-img').src = '/storage/' + lesson.thumbnail;
            document.getElementById('thumbnail-preview').classList.remove('hidden');
        } else {
            this.removeThumbnail();
        }

        this.updateVideoFields();

        // Mostrar preview del video de Bunny si existe
        if (lesson.video_type === 'bunny' && lesson.bunny_video_id) {
            this.showBunnyVideoPreview(lesson.bunny_video_id);
        } else {
            this.hideBunnyVideoPreview();
        }

        this.showModal();
    },

    /**
     * Muestra el modal
     */
    showModal() {
        // Limpiar alertas del modal
        const modalAlertContainer = document.getElementById('modal-alert-container');
        if (modalAlertContainer) {
            modalAlertContainer.classList.add('hidden');
            modalAlertContainer.innerHTML = '';
        }
        document.getElementById('lesson-modal').style.display = 'flex';
    },

    /**
     * Cierra el modal y resetea el formulario
     */
    closeModal() {
        document.getElementById('lesson-modal').style.display = 'none';
        document.getElementById('lesson-form').reset();

        // Limpiar tags checkboxes
        document.querySelectorAll('input[name="tags[]"]').forEach(checkbox => {
            checkbox.checked = false;
        });

        this.resetUploadUI();
        this.removeThumbnail();
    },

    /**
     * Actualiza los campos visibles según el tipo de video seleccionado
     */
    updateVideoFields() {
        const videoType = document.getElementById('lesson-video-type').value;

        document.getElementById('youtube-field').classList.toggle('hidden', videoType !== 'youtube');
        document.getElementById('bunny-field').classList.toggle('hidden', videoType !== 'bunny');
        document.getElementById('local-field').classList.toggle('hidden', videoType !== 'local');

        // Actualizar campos requeridos
        document.getElementById('lesson-youtube-id').required = videoType === 'youtube';
    },

    // ========================================================================
    // OPERACIONES CRUD
    // ========================================================================

    /**
     * Guarda una lección (crear o actualizar)
     */
    async saveLesson(event) {
        event.preventDefault();

        const submitButton = document.getElementById('submit-button');
        submitButton.disabled = true;
        submitButton.textContent = 'Guardando...';

        try {
            // Subir thumbnail si hay un archivo nuevo
            let thumbnailPath = document.getElementById('lesson-thumbnail').value || null;

            if (this.thumbnailFile) {
                try {
                    thumbnailPath = await this.uploadThumbnailToServer();
                    this.showAlert('success', 'Imagen subida correctamente', true);
                } catch (error) {
                    this.showAlert('error', 'Error al subir la imagen: ' + error.message, true);
                    submitButton.disabled = false;
                    submitButton.textContent = 'Guardar Lección';
                    return;
                }
            }

            // Obtener tags seleccionados
            const selectedTags = [];
            document.querySelectorAll('input[name="tags[]"]:checked').forEach(checkbox => {
                selectedTags.push(parseInt(checkbox.value));
            });

            const instructorId = document.getElementById('lesson-instructor').value;

            const formData = {
                title: document.getElementById('lesson-title').value,
                description: document.getElementById('lesson-description').value,
                instructor_id: instructorId ? parseInt(instructorId) : null,
                tags: selectedTags,
                video_type: document.getElementById('lesson-video-type').value,
                youtube_id: document.getElementById('lesson-youtube-id').value || null,
                bunny_video_id: document.getElementById('lesson-bunny-video-id').value || null,
                thumbnail: thumbnailPath,
                duration: parseInt(document.getElementById('lesson-duration').value) || 0,
                order: parseInt(document.getElementById('lesson-order').value),
                is_trial: document.getElementById('lesson-is-trial').checked,
                module_id: CONFIG.moduleId
            };

            let url, method;

            if (this.editingLessonId) {
                url = CONFIG.routes.lessons.update.replace(':id', this.editingLessonId);
                method = 'PUT';
            } else {
                url = CONFIG.routes.lessons.store;
                method = 'POST';
            }

            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CONFIG.csrfToken
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (data.success) {
                this.showAlert('success', data.message);
                this.closeModal();
                await this.loadLessons();
            } else {
                // Manejar errores de validación
                let errorMessage = data.message || 'Error al guardar la lección';

                // Si hay errores de validación específicos, mostrarlos
                if (data.errors) {
                    const errorMessages = [];
                    for (const field in data.errors) {
                        if (data.errors.hasOwnProperty(field)) {
                            errorMessages.push(...data.errors[field]);
                        }
                    }
                    if (errorMessages.length > 0) {
                        errorMessage = errorMessages.join('<br>');
                    }
                }

                this.showAlert('error', errorMessage, true);
            }
        } catch (error) {
            console.error('Error saving lesson:', error);
            this.showAlert('error', 'Error de conexión al guardar la lección', true);
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = 'Guardar Lección';
        }
    },

    /**
     * Elimina una lección
     */
    async deleteLesson(lessonId, lessonTitle) {
        if (!confirm(`¿Estás seguro de eliminar la lección "${lessonTitle}"?`)) {
            return;
        }

        try {
            const url = CONFIG.routes.lessons.destroy.replace(':id', lessonId);
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CONFIG.csrfToken
                }
            });

            const data = await response.json();

            if (data.success) {
                this.showAlert('success', data.message);
                await this.loadLessons();
            } else {
                this.showAlert('error', data.message || 'Error al eliminar la lección');
            }
        } catch (error) {
            console.error('Error deleting lesson:', error);
            this.showAlert('error', 'Error de conexión al eliminar la lección');
        }
    },

    /**
     * Mueve una lección hacia arriba en el orden
     */
    async moveUp(lessonId) {
        await this.callAction('moveUp', lessonId);
    },

    /**
     * Mueve una lección hacia abajo en el orden
     */
    async moveDown(lessonId) {
        await this.callAction('moveDown', lessonId);
    },

    /**
     * Alterna el estado trial/premium de una lección
     */
    async toggleTrial(lessonId) {
        await this.callAction('toggleTrial', lessonId);
    },

    /**
     * Ejecuta una acción en el API
     */
    async callAction(action, lessonId) {
        try {
            const url = CONFIG.routes.lessons[action].replace(':id', lessonId);
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CONFIG.csrfToken
                }
            });

            const data = await response.json();

            if (data.success) {
                this.showAlert('success', data.message);
                await this.loadLessons();
            } else {
                this.showAlert('error', data.message || 'Error al realizar la acción');
            }
        } catch (error) {
            console.error(`Error calling ${action}:`, error);
            this.showAlert('error', 'Error de conexión');
        }
    },

    // ========================================================================
    // FUNCIONES DE SUBIDA A BUNNY.NET
    // ========================================================================

    /**
     * Maneja la subida de un video a Bunny.net CDN
     */
    async handleBunnyUpload(event) {
        const file = event.target.files[0];
        if (!file) return;

        const title = document.getElementById('lesson-title').value || 'Video de lección';

        try {
            // Paso 1: Inicializar la subida
            const initResponse = await fetch(CONFIG.routes.bunny.init, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CONFIG.csrfToken
                },
                body: JSON.stringify({ title: title })
            });

            const initData = await initResponse.json();

            if (!initData.success) {
                throw new Error(initData.message || 'Error al inicializar subida');
            }

            this.showUploadProgress();

            // Paso 2: Subir el archivo a Bunny.net
            this.uploadStartTime = Date.now();
            this.uploadStartLoaded = 0;
            this.uploadXhr = new XMLHttpRequest();

            this.uploadXhr.upload.addEventListener('progress', (e) => {
                if (e.lengthComputable) {
                    const percentComplete = Math.round((e.loaded / e.total) * 100);
                    this.updateProgress(percentComplete, e.loaded, e.total);
                }
            });

            this.uploadXhr.addEventListener('load', async () => {
                if (this.uploadXhr.status === 200 || this.uploadXhr.status === 201) {
                    // Paso 3: Confirmar la subida
                    try {
                        const confirmResponse = await fetch(CONFIG.routes.bunny.confirm, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': CONFIG.csrfToken
                            },
                            body: JSON.stringify({ video_id: initData.video_id })
                        });

                        const confirmData = await confirmResponse.json();

                        if (confirmData.success) {
                            document.getElementById('lesson-bunny-video-id').value = initData.video_id;
                            this.showUploadSuccess();
                        } else {
                            throw new Error(confirmData.message);
                        }
                    } catch (error) {
                        this.showAlert('error', 'Error al confirmar upload: ' + error.message);
                        this.resetUploadUI();
                    }
                } else {
                    this.showAlert('error', 'Error al subir a Bunny.net: ' + this.uploadXhr.status);
                    this.resetUploadUI();
                }
            });

            this.uploadXhr.addEventListener('error', () => {
                this.showAlert('error', 'Error de conexión con Bunny.net');
                this.resetUploadUI();
            });

            this.uploadXhr.open('PUT', initData.upload_url);
            this.uploadXhr.setRequestHeader('AccessKey', initData.api_key);
            this.uploadXhr.send(file);

        } catch (error) {
            console.error('Error in handleBunnyUpload:', error);
            this.showAlert('error', 'Error al iniciar subida: ' + error.message);
            this.resetUploadUI();
        }
    },

    /**
     * Muestra la UI de progreso de subida
     */
    showUploadProgress() {
        document.getElementById('bunny-select-button').disabled = true;
        document.getElementById('upload-progress-container').classList.remove('hidden');
        document.getElementById('upload-success').classList.add('hidden');
    },

    /**
     * Actualiza la barra de progreso de subida
     */
    updateProgress(percentage, loaded, total) {
        document.getElementById('upload-progress-bar').style.width = percentage + '%';
        document.getElementById('upload-percentage').textContent = percentage + '%';

        const elapsed = (Date.now() - this.uploadStartTime) / 1000;
        const loadedSinceStart = loaded - this.uploadStartLoaded;
        const speed = loadedSinceStart / elapsed / (1024 * 1024);
        document.getElementById('upload-speed').textContent = speed.toFixed(2) + ' MB/s';

        const remaining = total - loaded;
        const eta = remaining / (loadedSinceStart / elapsed);
        document.getElementById('upload-eta').textContent = eta > 0 ? Math.ceil(eta) + 's restantes' : 'Finalizando...';
    },

    /**
     * Muestra el mensaje de subida exitosa
     */
    showUploadSuccess() {
        document.getElementById('upload-progress-container').classList.add('hidden');
        document.getElementById('upload-success').classList.remove('hidden');
        document.getElementById('submit-button').disabled = false;
    },

    /**
     * Resetea la UI de subida a su estado inicial
     */
    resetUploadUI() {
        document.getElementById('bunny-select-button').disabled = false;
        document.getElementById('upload-progress-container').classList.add('hidden');
        document.getElementById('upload-success').classList.add('hidden');
        document.getElementById('upload-progress-bar').style.width = '0%';
        document.getElementById('lesson-bunny-video-id').value = '';
        document.getElementById('bunny-file-input').value = '';
    },

    /**
     * Cancela la subida en progreso
     */
    cancelUpload() {
        if (this.uploadXhr) {
            this.uploadXhr.abort();
            this.uploadXhr = null;
        }
        this.resetUploadUI();
        this.showAlert('info', 'Subida cancelada');
    },

    /**
     * Muestra el preview del video de Bunny.net
     */
    showBunnyVideoPreview(videoId) {
        const libraryId = CONFIG.bunnyLibraryId;

        if (!libraryId) {
            console.error('Bunny library ID no configurado');
            return;
        }

        const iframeUrl = `https://iframe.mediadelivery.net/embed/${libraryId}/${videoId}`;

        document.getElementById('bunny-video-iframe').src = iframeUrl;
        document.getElementById('bunny-video-id-display').textContent = videoId;
        document.getElementById('bunny-video-preview').classList.remove('hidden');
        document.getElementById('bunny-button-text').textContent = 'Cambiar video';
    },

    /**
     * Oculta el preview del video de Bunny.net
     */
    hideBunnyVideoPreview() {
        document.getElementById('bunny-video-preview').classList.add('hidden');
        document.getElementById('bunny-video-iframe').src = '';
        document.getElementById('bunny-button-text').textContent = 'Seleccionar archivo de video';
    },

    // ========================================================================
    // FUNCIONES DE THUMBNAIL
    // ========================================================================

    thumbnailFile: null,

    /**
     * Maneja la selección de thumbnail
     */
    async handleThumbnailUpload(event) {
        const file = event.target.files[0];
        if (!file) return;

        // Validar tipo de archivo
        if (!file.type.startsWith('image/')) {
            this.showAlert('error', 'Por favor selecciona un archivo de imagen válido');
            event.target.value = '';
            return;
        }

        // Validar tamaño (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            this.showAlert('error', 'La imagen no debe superar los 5MB');
            event.target.value = '';
            return;
        }

        // Guardar archivo para subir después
        this.thumbnailFile = file;

        // Mostrar preview
        const reader = new FileReader();
        reader.onload = (e) => {
            document.getElementById('thumbnail-preview-img').src = e.target.result;
            document.getElementById('thumbnail-preview').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    },

    /**
     * Sube el thumbnail al servidor
     */
    async uploadThumbnailToServer() {
        if (!this.thumbnailFile) {
            return null;
        }

        try {
            const formData = new FormData();
            formData.append('thumbnail', this.thumbnailFile);

            const response = await fetch('/admin/api/upload-thumbnail', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CONFIG.csrfToken
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                return data.path;
            } else {
                throw new Error(data.message || 'Error al subir imagen');
            }
        } catch (error) {
            console.error('Error uploading thumbnail:', error);
            throw error;
        }
    },

    /**
     * Elimina el thumbnail seleccionado
     */
    removeThumbnail() {
        this.thumbnailFile = null;
        document.getElementById('lesson-thumbnail-input').value = '';
        document.getElementById('lesson-thumbnail').value = '';
        document.getElementById('thumbnail-preview').classList.add('hidden');
        document.getElementById('thumbnail-preview-img').src = '';
    },

    // ========================================================================
    // UTILIDADES
    // ========================================================================

    /**
     * Muestra una alerta temporal al usuario
     */
    showAlert(type, message, inModal = false) {
        const containerId = inModal ? 'modal-alert-container' : 'alert-container';
        const container = document.getElementById(containerId);

        if (!container) return;

        const colors = {
            success: { bg: 'bg-green-50', border: 'border-green-200', text: 'text-green-700' },
            error: { bg: 'bg-red-50', border: 'border-red-200', text: 'text-red-700' },
            info: { bg: 'bg-blue-50', border: 'border-blue-200', text: 'text-blue-700' }
        };

        const color = colors[type] || colors.info;

        container.innerHTML = `
            <div class="p-3 ${color.bg} border ${color.border} rounded-lg">
                <p class="${color.text} text-sm">${message}</p>
            </div>
        `;
        container.classList.remove('hidden');

        setTimeout(() => {
            container.classList.add('hidden');
        }, 5000);
    },

    /**
     * Escapa HTML para prevenir XSS
     */
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
};

// ============================================================================
// INICIALIZACIÓN
// ============================================================================

/**
 * Inicializa el Lesson Manager
 * Soporta tanto carga directa de página como navegación SPA con Livewire
 */
function initializeLessonManager() {
    if (document.readyState === 'loading') {
        // DOM aún cargando, esperar al evento
        document.addEventListener('DOMContentLoaded', () => {
            window.LessonManager.init();
        });
    } else {
        // DOM ya está listo, inicializar inmediatamente
        window.LessonManager.init();
    }
}

// Inicializar ahora
initializeLessonManager();

// Escuchar navegación Livewire para reinicializar
// IMPORTANTE: Esto resuelve el problema cuando se navega desde una página Livewire
// hacia esta página no-Livewire usando wire:navigate
document.addEventListener('livewire:navigated', () => {
    window.LessonManager.init();
});

/**
 * Función global para obtener duración desde Bunny.net
 * (Se llama automáticamente después de subir el video)
 */
async function fetchDurationFromBunny() {
    const bunnyVideoId = document.getElementById('lesson-bunny-video-id').value;
    const videoType = document.getElementById('lesson-video-type').value;

    if (videoType !== 'bunny' || !bunnyVideoId) {
        return;
    }

    const durationInput = document.getElementById('lesson-duration');
    const durationInfo = document.getElementById('duration-info');
    const durationDisplay = document.getElementById('duration-display');

    try {
        // Llamar a la API para obtener la duración
        const response = await fetch('{{ route('admin.api.lessons.bunny.duration') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                video_id: bunnyVideoId
            })
        });

        const data = await response.json();

        if (data.success && data.duration > 0) {
            // Store duration in seconds (from Bunny API)
            durationInput.value = data.duration;

            // Display duration in minutes (for user)
            const durationMinutes = Math.ceil(data.duration / 60);
            durationDisplay.textContent = durationMinutes;

            // Show success message
            durationInfo.classList.remove('hidden');
        }

    } catch (error) {
        console.error('Error al obtener duración:', error);
    }
}

})(); // End of IIFE
</script>
@endpush
@endsection
