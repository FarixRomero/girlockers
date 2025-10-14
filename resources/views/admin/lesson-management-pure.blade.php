@extends('layouts.admin')

@section('title', 'Gestión de Lecciones - Admin')

@section('content')
<div id="app">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-display text-2xl text-cream" id="module-title">Cargando...</h2>
                <p class="text-cream/60 text-sm mt-1">
                    <a href="{{ route('admin.courses.modules', ':course_id') }}" id="back-link" class="hover:text-pink-vibrant">
                        <span id="course-title">...</span>
                    </a>
                    • <span id="lessons-count">0</span> lecciones
                </p>
            </div>
            <a href="{{ route('admin.courses.modules', ':course_id') }}" id="back-button" class="text-pink-vibrant hover:text-pink-light text-sm flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver a Módulos
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    <div id="alert-container" class="hidden mb-6"></div>

    <!-- Module Info Card -->
    <div class="card-premium mb-6" id="module-info">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <p class="text-cream/70 mb-2" id="module-description">Cargando...</p>
                <div class="flex items-center space-x-4 text-sm text-cream/60">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span id="lessons-count-detail">0</span> lecciones
                    </span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span id="total-duration">0</span> min total
                    </span>
                    <span class="px-2 py-1 bg-pink-vibrant/20 text-pink-vibrant text-xs rounded-full font-bold">
                        Orden: <span id="module-order">-</span>
                    </span>
                </div>
            </div>
            <button onclick="LessonManager.openCreateModal()" class="btn-primary ml-4">
                + Nueva Lección
            </button>
        </div>
    </div>

    <!-- Lessons List -->
    <div id="lessons-container" class="space-y-4">
        <div class="text-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-pink-vibrant mx-auto"></div>
            <p class="text-cream/60 mt-4">Cargando lecciones...</p>
        </div>
    </div>

    <!-- Modal -->
    <div id="lesson-modal" class="fixed inset-0 bg-dark/80 backdrop-blur-sm hidden items-center justify-center z-50" style="display: none;">
        <div class="bg-gradient-to-br from-dark via-purple-deep to-dark border border-pink-vibrant/30 rounded-xl p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-display text-2xl text-cream" id="modal-title">Nueva Lección</h3>
                <button onclick="LessonManager.closeModal()" class="text-cream/60 hover:text-cream">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="lesson-form" onsubmit="LessonManager.saveLesson(event)" class="space-y-4">
                <!-- Title -->
                <div>
                    <label class="block text-cream/80 text-sm font-medium mb-2">Título</label>
                    <input type="text" id="lesson-title" required
                        class="w-full px-4 py-2 bg-dark/50 border border-pink-vibrant/30 rounded-lg text-cream focus:border-pink-vibrant focus:outline-none">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-cream/80 text-sm font-medium mb-2">Descripción</label>
                    <textarea id="lesson-description" rows="3" required
                        class="w-full px-4 py-2 bg-dark/50 border border-pink-vibrant/30 rounded-lg text-cream focus:border-pink-vibrant focus:outline-none"></textarea>
                </div>

                <!-- Video Type -->
                <div>
                    <label class="block text-cream/80 text-sm font-medium mb-2">Tipo de Video</label>
                    <select id="lesson-video-type" onchange="LessonManager.updateVideoFields()"
                        class="w-full px-4 py-2 bg-dark/50 border border-pink-vibrant/30 rounded-lg text-cream focus:border-pink-vibrant focus:outline-none">
                        <option value="youtube">YouTube</option>
                        <option value="bunny">Bunny.net (CDN)</option>
                        <option value="local">Local</option>
                    </select>
                </div>

                <!-- YouTube ID -->
                <div id="youtube-field" class="hidden">
                    <label class="block text-cream/80 text-sm font-medium mb-2">YouTube Video ID</label>
                    <input type="text" id="lesson-youtube-id"
                        class="w-full px-4 py-2 bg-dark/50 border border-pink-vibrant/30 rounded-lg text-cream focus:border-pink-vibrant focus:outline-none">
                    <p class="text-cream/50 text-xs mt-1">ID del video (ej: dQw4w9WgXcQ de youtube.com/watch?v=dQw4w9WgXcQ)</p>
                </div>

                <!-- Bunny Upload -->
                <div id="bunny-field" class="hidden">
                    <label class="block text-cream/80 text-sm font-medium mb-2">Video de Bunny.net</label>
                    <div class="border-2 border-dashed border-pink-vibrant/30 rounded-lg p-6 text-center hover:border-pink-vibrant/50 transition-colors">
                        <input type="file" id="bunny-file-input" accept="video/*" onchange="LessonManager.handleBunnyUpload(event)" class="hidden">
                        <button type="button" onclick="document.getElementById('bunny-file-input').click()" class="btn-primary" id="bunny-select-button">
                            Seleccionar archivo de video
                        </button>
                        <p class="text-cream/50 text-xs mt-2">Soporta: MP4, MOV, AVI, WMV</p>

                        <!-- Upload Progress -->
                        <div id="upload-progress-container" class="hidden mt-4">
                            <div class="w-full bg-dark/50 rounded-full h-2 mb-2">
                                <div id="upload-progress-bar" class="bg-gradient-pink h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                            <p class="text-cream/70 text-sm">
                                <span id="upload-percentage">0%</span> -
                                <span id="upload-speed">0 MB/s</span> -
                                <span id="upload-eta">Calculando...</span>
                            </p>
                            <button type="button" onclick="LessonManager.cancelUpload()" class="text-red-400 hover:text-red-300 text-sm mt-2">
                                Cancelar subida
                            </button>
                        </div>

                        <!-- Upload Success -->
                        <div id="upload-success" class="hidden mt-4 p-3 bg-green-500/10 border border-green-500/30 rounded-lg">
                            <p class="text-green-400 text-sm flex items-center justify-center">
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
                    <label class="block text-cream/80 text-sm font-medium mb-2">Archivo Local</label>
                    <p class="text-yellow-400 text-sm">Funcionalidad en desarrollo. Usa Bunny.net para mejor rendimiento.</p>
                </div>

                <!-- Duration & Order -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-cream/80 text-sm font-medium mb-2">Duración (minutos)</label>
                        <input type="number" id="lesson-duration" min="0" value="0"
                            class="w-full px-4 py-2 bg-dark/50 border border-pink-vibrant/30 rounded-lg text-cream focus:border-pink-vibrant focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-cream/80 text-sm font-medium mb-2">Orden</label>
                        <input type="number" id="lesson-order" min="1" required
                            class="w-full px-4 py-2 bg-dark/50 border border-pink-vibrant/30 rounded-lg text-cream focus:border-pink-vibrant focus:outline-none">
                    </div>
                </div>

                <!-- Is Trial -->
                <div class="flex items-center">
                    <input type="checkbox" id="lesson-is-trial" class="w-4 h-4 text-pink-vibrant bg-dark border-pink-vibrant/30 rounded focus:ring-pink-vibrant">
                    <label for="lesson-is-trial" class="ml-2 text-cream/80 text-sm">Lección gratuita (Trial)</label>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="LessonManager.closeModal()" class="px-6 py-2 bg-dark/50 text-cream rounded-lg hover:bg-dark/70 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" id="submit-button" class="btn-primary">
                        Guardar Lección
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Configuration
const CONFIG = {
    moduleId: {{ $moduleId }},
    csrfToken: '{{ csrf_token() }}',
    routes: {
        lessons: {
            index: '{{ route('admin.api.lessons.index', ['moduleId' => ':moduleId']) }}',
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

// Lesson Manager
const LessonManager = {
    currentModule: null,
    lessons: [],
    editingLessonId: null,
    uploadXhr: null,
    uploadStartTime: 0,
    uploadStartLoaded: 0,

    async init() {
        console.log('Initializing Lesson Manager for module:', CONFIG.moduleId);
        await this.loadLessons();
    },

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
            console.log('Loaded lessons:', data);

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

    renderModule() {
        const module = this.currentModule;
        document.getElementById('module-title').textContent = `Lecciones de ${module.title}`;
        document.getElementById('course-title').textContent = module.course.title;
        document.getElementById('module-description').textContent = module.description;
        document.getElementById('lessons-count').textContent = module.lessons.length;
        document.getElementById('lessons-count-detail').textContent = module.lessons.length;
        document.getElementById('module-order').textContent = module.order;

        const totalDuration = module.lessons.reduce((sum, lesson) => sum + (lesson.duration || 0), 0);
        document.getElementById('total-duration').textContent = totalDuration;

        // Update back links
        const courseId = module.course_id;
        const backUrl = '{{ route('admin.courses.modules', ['courseId' => ':courseId']) }}'.replace(':courseId', courseId);
        document.getElementById('back-link').href = backUrl;
        document.getElementById('back-button').href = backUrl;
    },

    renderLessons() {
        const container = document.getElementById('lessons-container');

        if (this.lessons.length === 0) {
            container.innerHTML = `
                <div class="card-premium text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-cream/30 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-cream/60">No hay lecciones aún</p>
                    <button onclick="LessonManager.openCreateModal()" class="btn-primary mt-4">+ Crear primera lección</button>
                </div>
            `;
            return;
        }

        container.innerHTML = this.lessons.map(lesson => this.renderLessonCard(lesson)).join('');
    },

    renderLessonCard(lesson) {
        const videoTypeLabel = {
            youtube: 'YouTube',
            bunny: 'Bunny.net',
            local: 'Local'
        }[lesson.video_type] || lesson.video_type;

        return `
            <div class="card-premium">
                <div class="flex items-start justify-between">
                    <div class="flex items-start flex-1">
                        <div class="w-12 h-12 rounded-lg bg-gradient-pink flex items-center justify-center text-cream font-bold text-lg mr-4">
                            ${lesson.order}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-1">
                                <h3 class="font-display text-lg text-cream">${this.escapeHtml(lesson.title)}</h3>
                                ${lesson.is_trial
                                    ? '<span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs rounded-full font-bold">✓ Trial</span>'
                                    : '<span class="px-2 py-1 bg-orange-500/20 text-orange-400 text-xs rounded-full font-bold">Premium</span>'
                                }
                            </div>
                            <p class="text-cream/60 text-sm mb-2">${this.escapeHtml(lesson.description)}</p>
                            <div class="flex items-center space-x-3 text-xs text-cream/50">
                                <span class="flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                    </svg>
                                    ${videoTypeLabel}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    ${lesson.duration} min
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2 ml-4">
                        ${lesson.order > 1
                            ? `<button onclick="LessonManager.moveUp(${lesson.id})" class="p-2 text-cream/60 hover:text-cream transition-colors" title="Mover arriba">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            </button>`
                            : '<div class="w-9"></div>'
                        }
                        ${lesson.order < this.lessons.length
                            ? `<button onclick="LessonManager.moveDown(${lesson.id})" class="p-2 text-cream/60 hover:text-cream transition-colors" title="Mover abajo">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>`
                            : '<div class="w-9"></div>'
                        }
                        <button onclick="LessonManager.toggleTrial(${lesson.id})" class="p-2 text-cream/60 hover:text-green-400 transition-colors" title="Toggle trial">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </button>
                        <button onclick="LessonManager.openEditModal(${lesson.id})" class="p-2 text-cream/60 hover:text-pink-vibrant transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <button onclick="LessonManager.deleteLesson(${lesson.id}, '${this.escapeHtml(lesson.title)}')" class="p-2 text-cream/60 hover:text-red-400 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        `;
    },

    openCreateModal() {
        this.editingLessonId = null;
        document.getElementById('modal-title').textContent = 'Nueva Lección';
        document.getElementById('lesson-form').reset();
        document.getElementById('lesson-order').value = this.lessons.length + 1;
        document.getElementById('lesson-video-type').value = 'youtube';
        this.updateVideoFields();
        this.showModal();
    },

    async openEditModal(lessonId) {
        this.editingLessonId = lessonId;
        const lesson = this.lessons.find(l => l.id === lessonId);

        if (!lesson) {
            this.showAlert('error', 'Lección no encontrada');
            return;
        }

        document.getElementById('modal-title').textContent = 'Editar Lección';
        document.getElementById('lesson-title').value = lesson.title;
        document.getElementById('lesson-description').value = lesson.description;
        document.getElementById('lesson-video-type').value = lesson.video_type;
        document.getElementById('lesson-youtube-id').value = lesson.youtube_id || '';
        document.getElementById('lesson-bunny-video-id').value = lesson.bunny_video_id || '';
        document.getElementById('lesson-duration').value = lesson.duration || 0;
        document.getElementById('lesson-order').value = lesson.order;
        document.getElementById('lesson-is-trial').checked = lesson.is_trial;

        this.updateVideoFields();
        this.showModal();
    },

    showModal() {
        document.getElementById('lesson-modal').style.display = 'flex';
    },

    closeModal() {
        document.getElementById('lesson-modal').style.display = 'none';
        document.getElementById('lesson-form').reset();
        this.resetUploadUI();
    },

    updateVideoFields() {
        const videoType = document.getElementById('lesson-video-type').value;

        document.getElementById('youtube-field').classList.toggle('hidden', videoType !== 'youtube');
        document.getElementById('bunny-field').classList.toggle('hidden', videoType !== 'bunny');
        document.getElementById('local-field').classList.toggle('hidden', videoType !== 'local');

        // Update required fields
        document.getElementById('lesson-youtube-id').required = videoType === 'youtube';
    },

    async saveLesson(event) {
        event.preventDefault();

        const formData = {
            title: document.getElementById('lesson-title').value,
            description: document.getElementById('lesson-description').value,
            video_type: document.getElementById('lesson-video-type').value,
            youtube_id: document.getElementById('lesson-youtube-id').value || null,
            bunny_video_id: document.getElementById('lesson-bunny-video-id').value || null,
            duration: parseInt(document.getElementById('lesson-duration').value) || 0,
            order: parseInt(document.getElementById('lesson-order').value),
            is_trial: document.getElementById('lesson-is-trial').checked,
            module_id: CONFIG.moduleId
        };

        console.log('Saving lesson:', formData);

        try {
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
            console.log('Save response:', data);

            if (data.success) {
                this.showAlert('success', data.message);
                this.closeModal();
                await this.loadLessons();
            } else {
                this.showAlert('error', data.message || 'Error al guardar la lección');
            }
        } catch (error) {
            console.error('Error saving lesson:', error);
            this.showAlert('error', 'Error de conexión al guardar la lección');
        }
    },

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

    async moveUp(lessonId) {
        await this.callAction('moveUp', lessonId);
    },

    async moveDown(lessonId) {
        await this.callAction('moveDown', lessonId);
    },

    async toggleTrial(lessonId) {
        await this.callAction('toggleTrial', lessonId);
    },

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

    // Bunny Upload Functions
    async handleBunnyUpload(event) {
        const file = event.target.files[0];
        if (!file) return;

        console.log('Starting Bunny upload for file:', file.name);

        // Get title
        const title = document.getElementById('lesson-title').value || 'Video de lección';

        try {
            // Step 1: Initialize upload
            const initResponse = await fetch(CONFIG.routes.bunny.init, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CONFIG.csrfToken
                },
                body: JSON.stringify({ title: title })
            });

            const initData = await initResponse.json();
            console.log('Init response:', initData);

            if (!initData.success) {
                throw new Error(initData.message || 'Error al inicializar subida');
            }

            // Show upload UI
            this.showUploadProgress();

            // Step 2: Upload to Bunny
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
                console.log('Upload complete - Status:', this.uploadXhr.status);

                if (this.uploadXhr.status === 200 || this.uploadXhr.status === 201) {
                    // Step 3: Confirm upload
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

            console.log('Uploading to:', initData.upload_url);
            this.uploadXhr.open('PUT', initData.upload_url);
            this.uploadXhr.setRequestHeader('AccessKey', initData.api_key);
            this.uploadXhr.send(file);

        } catch (error) {
            console.error('Error in handleBunnyUpload:', error);
            this.showAlert('error', 'Error al iniciar subida: ' + error.message);
            this.resetUploadUI();
        }
    },

    showUploadProgress() {
        document.getElementById('bunny-select-button').disabled = true;
        document.getElementById('upload-progress-container').classList.remove('hidden');
        document.getElementById('upload-success').classList.add('hidden');
    },

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

    showUploadSuccess() {
        document.getElementById('upload-progress-container').classList.add('hidden');
        document.getElementById('upload-success').classList.remove('hidden');
        document.getElementById('submit-button').disabled = false;
    },

    resetUploadUI() {
        document.getElementById('bunny-select-button').disabled = false;
        document.getElementById('upload-progress-container').classList.add('hidden');
        document.getElementById('upload-success').classList.add('hidden');
        document.getElementById('upload-progress-bar').style.width = '0%';
        document.getElementById('lesson-bunny-video-id').value = '';
        document.getElementById('bunny-file-input').value = '';
    },

    cancelUpload() {
        if (this.uploadXhr) {
            this.uploadXhr.abort();
            this.uploadXhr = null;
        }
        this.resetUploadUI();
        this.showAlert('info', 'Subida cancelada');
    },

    showAlert(type, message) {
        const container = document.getElementById('alert-container');
        const colors = {
            success: { bg: 'bg-green-500/10', border: 'border-green-500/30', text: 'text-green-400' },
            error: { bg: 'bg-red-500/10', border: 'border-red-500/30', text: 'text-red-400' },
            info: { bg: 'bg-blue-500/10', border: 'border-blue-500/30', text: 'text-blue-400' }
        };

        const color = colors[type] || colors.info;

        container.innerHTML = `
            <div class="p-4 ${color.bg} border ${color.border} rounded-lg">
                <p class="${color.text} flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    ${message}
                </p>
            </div>
        `;
        container.classList.remove('hidden');

        setTimeout(() => {
            container.classList.add('hidden');
        }, 5000);
    },

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
};

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    LessonManager.init();
});
</script>
@endpush
@endsection
