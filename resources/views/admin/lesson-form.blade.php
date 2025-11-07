@extends('layouts.admin')

@section('title', $lesson ? 'Editar Lección' : 'Nueva Lección')

@section('content')
<div id="app" class="pb-20 lg:pb-0">
    <!-- Header -->
    <div class="mb-4 md:mb-6">
        <div class="flex items-center justify-between mb-2">
            <h2 class="font-display text-xl md:text-2xl text-cream">{{ $lesson ? 'Editar Lección' : 'Nueva Lección' }}</h2>
            <a href="{{ route('admin.modules.lessons', $module->id) }}" class="text-pink-vibrant hover:text-pink-light text-xs md:text-sm flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="hidden md:inline">Volver a Lecciones</span>
                <span class="md:hidden">Volver</span>
            </a>
        </div>
        <p class="text-cream/60 text-xs md:text-sm">
            <a href="{{ route('admin.courses.modules', $module->course_id) }}" class="hover:text-pink-vibrant">
                {{ $module->course->title }}
            </a>
            • {{ $module->title }}
        </p>
    </div>

    <!-- Alert Messages -->
    <div id="alert-container" class="hidden mb-4 md:mb-6"></div>

    <!-- Form -->
    <form id="lesson-form" onsubmit="LessonFormManager.saveLesson(event)" class="space-y-4 md:space-y-6">
            <!-- Title -->
            <div>
                <label class="block text-cream text-xs md:text-sm font-bold mb-1.5 md:mb-2">Título *</label>
                <input type="text" id="lesson-title" required
                    value="{{ $lesson ? $lesson->title : '' }}"
                    class="w-full bg-purple-deeper border border-pink-vibrant/20 rounded-lg px-3 md:px-4 py-2 text-sm md:text-base text-cream placeholder-cream/40 focus:outline-none focus:border-pink-vibrant transition">
            </div>

            <!-- Description -->
            <div>
                <label class="block text-cream text-xs md:text-sm font-bold mb-1.5 md:mb-2">Descripción *</label>
                <textarea id="lesson-description" rows="3" required
                    class="w-full bg-purple-deeper border border-pink-vibrant/20 rounded-lg px-3 md:px-4 py-2 text-sm md:text-base text-cream placeholder-cream/40 focus:outline-none focus:border-pink-vibrant transition resize-none">{{ $lesson ? $lesson->description : '' }}</textarea>
            </div>

            <!-- Instructor -->
            <div>
                <label class="block text-cream text-xs md:text-sm font-bold mb-1.5 md:mb-2">Instructor</label>
                <select id="lesson-instructor" class="w-full bg-purple-deeper border border-pink-vibrant/20 rounded-lg px-3 md:px-4 py-2 text-sm md:text-base text-cream focus:outline-none focus:border-pink-vibrant transition">
                    <option value="">Sin instructor asignado</option>
                    @foreach($instructors as $instructor)
                        <option value="{{ $instructor->id }}" {{ $lesson && $lesson->instructor_id == $instructor->id ? 'selected' : '' }}>
                            {{ $instructor->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tags -->
            <div>
                <label class="block text-cream text-xs md:text-sm font-bold mb-1.5 md:mb-2">Tags</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($tags as $tag)
                        <label class="inline-flex items-center cursor-pointer group">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                {{ $lesson && $lesson->tags->contains($tag->id) ? 'checked' : '' }}
                                class="hidden peer">
                            <span class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-200
                                peer-checked:bg-gradient-to-r peer-checked:from-purple-primary peer-checked:to-purple-light peer-checked:text-white peer-checked:shadow-lg
                                bg-purple-deep/50 text-cream/70 border border-pink-vibrant/20
                                hover:border-pink-vibrant/50 hover:text-cream
                                peer-checked:border-transparent">
                                {{ $tag->name }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Thumbnail/Image -->
            <div>
                <label class="block text-cream text-xs md:text-sm font-bold mb-1.5 md:mb-2">Imagen/Thumbnail</label>
                <div class="border-2 border-dashed border-pink-vibrant/30 rounded-lg p-3 md:p-4 text-center hover:border-pink-vibrant/50 transition-colors bg-purple-deep">
                    <input type="file" id="lesson-thumbnail-input" accept="image/*" onchange="LessonFormManager.handleThumbnailUpload(event)" class="hidden">
                    <button type="button" onclick="document.getElementById('lesson-thumbnail-input').click()" class="px-4 md:px-6 py-2 md:py-3 bg-purple-primary hover:bg-purple-dark text-white text-sm md:text-base font-bold rounded-lg shadow-sm transition-all">
                        Seleccionar imagen
                    </button>
                    <p class="text-cream/50 text-xs mt-2">JPG, PNG, WebP (1280x720px)</p>

                    <!-- Preview -->
                    <div id="thumbnail-preview" class="{{ $lesson && $lesson->thumbnail ? '' : 'hidden' }} mt-4">
                        <img id="thumbnail-preview-img"
                            src="{{ $lesson && $lesson->thumbnail ? asset('storage/' . $lesson->thumbnail) : '' }}"
                            alt="Preview"
                            class="max-w-full h-40 mx-auto rounded-lg object-cover">
                        <button type="button" onclick="LessonFormManager.removeThumbnail()" class="text-red-400 hover:text-red-300 text-sm mt-2 font-medium">
                            Eliminar imagen
                        </button>
                    </div>

                    <input type="hidden" id="lesson-thumbnail" value="{{ $lesson ? $lesson->thumbnail : '' }}">
                </div>
            </div>

            <!-- Video Type -->
            <div>
                <label class="block text-cream text-xs md:text-sm font-bold mb-1.5 md:mb-2">Tipo de Video</label>
                <select id="lesson-video-type" onchange="LessonFormManager.updateVideoFields()" class="w-full bg-purple-deeper border border-pink-vibrant/20 rounded-lg px-3 md:px-4 py-2 text-sm md:text-base text-cream focus:outline-none focus:border-pink-vibrant transition">
                    <option value="bunny" {{ $lesson && $lesson->video_type == 'bunny' ? 'selected' : '' }}>Bunny.net (CDN)</option>
                    <option value="youtube" {{ $lesson && $lesson->video_type == 'youtube' ? 'selected' : '' }}>YouTube</option>
                    <option value="local" {{ $lesson && $lesson->video_type == 'local' ? 'selected' : '' }}>Local</option>
                </select>
            </div>

            <!-- YouTube ID -->
            <div id="youtube-field" class="hidden">
                <label class="block text-cream text-xs md:text-sm font-bold mb-1.5 md:mb-2">YouTube Video ID</label>
                <input type="text" id="lesson-youtube-id"
                    value="{{ $lesson ? $lesson->youtube_id : '' }}"
                    class="w-full bg-purple-deeper border border-pink-vibrant/20 rounded-lg px-3 md:px-4 py-2 text-sm md:text-base text-cream placeholder-cream/40 focus:outline-none focus:border-pink-vibrant transition">
                <p class="text-cream/50 text-xs mt-2">ID del video (ej: dQw4w9WgXcQ)</p>
            </div>

            <!-- Bunny Upload -->
            <div id="bunny-field" class="hidden">
                <label class="block text-cream text-xs md:text-sm font-bold mb-1.5 md:mb-2">Video de Bunny.net</label>

                <!-- Video Preview (when editing) -->
                @if($lesson && $lesson->video_type == 'bunny' && $lesson->bunny_video_id)
                <div id="bunny-video-preview" class="mb-4">
                    <div class="bg-black rounded-lg overflow-hidden" style="position: relative; padding-top: 56.25%;">
                        <iframe id="bunny-video-iframe"
                                src="https://iframe.mediadelivery.net/embed/{{ config('bunny.library_id') }}/{{ $lesson->bunny_video_id }}"
                                style="border: none; position: absolute; top: 0; height: 100%; width: 100%;"
                                allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;"
                                allowfullscreen="true">
                        </iframe>
                    </div>
                    <p class="text-cream/50 text-sm mt-2">Video actual - ID: <span id="bunny-video-id-display">{{ $lesson->bunny_video_id }}</span></p>
                </div>
                @else
                <div id="bunny-video-preview" class="hidden mb-4">
                    <div class="bg-black rounded-lg overflow-hidden" style="position: relative; padding-top: 56.25%;">
                        <iframe id="bunny-video-iframe"
                                style="border: none; position: absolute; top: 0; height: 100%; width: 100%;"
                                allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;"
                                allowfullscreen="true">
                        </iframe>
                    </div>
                    <p class="text-cream/50 text-sm mt-2">Video actual - ID: <span id="bunny-video-id-display"></span></p>
                </div>
                @endif

                <div class="border-2 border-dashed border-pink-vibrant/30 rounded-lg p-3 md:p-6 text-center hover:border-pink-vibrant/50 transition-colors bg-purple-deep">
                    <input type="file" id="bunny-file-input" accept="video/*" onchange="LessonFormManager.handleBunnyUpload(event)" class="hidden">
                    <button type="button" onclick="document.getElementById('bunny-file-input').click()" class="px-4 md:px-6 py-2 md:py-3 bg-purple-primary hover:bg-purple-dark text-white text-sm md:text-base font-bold rounded-lg shadow-sm transition-all" id="bunny-select-button">
                        <span id="bunny-button-text">{{ $lesson && $lesson->bunny_video_id ? 'Cambiar video' : 'Seleccionar archivo de video' }}</span>
                    </button>
                    <p class="text-cream/50 text-xs mt-2">MP4, MOV, AVI, WMV</p>

                    <!-- Upload Progress -->
                    <div id="upload-progress-container" class="hidden mt-4">
                        <div class="w-full bg-purple-darker rounded-full h-2 mb-2">
                            <div id="upload-progress-bar" class="bg-gradient-to-r from-purple-primary to-purple-light h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                        <p class="text-cream text-sm">
                            <span id="upload-percentage">0%</span> -
                            <span id="upload-speed">0 MB/s</span> -
                            <span id="upload-eta">Calculando...</span>
                        </p>
                        <button type="button" onclick="LessonFormManager.cancelUpload()" class="text-red-400 hover:text-red-300 text-sm mt-2 font-medium">
                            Cancelar subida
                        </button>
                    </div>

                    <!-- Upload Success -->
                    <div id="upload-success" class="hidden mt-4 p-3 bg-green-500/10 border border-green-500/30 rounded-lg">
                        <p class="text-green-400 text-sm flex items-center justify-center font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Video subido exitosamente
                        </p>
                    </div>

                    <input type="hidden" id="lesson-bunny-video-id" value="{{ $lesson ? $lesson->bunny_video_id : '' }}">
                </div>
            </div>

            <!-- Local File (disabled for now) -->
            <div id="local-field" class="hidden">
                <label class="block text-cream text-xs md:text-sm font-bold mb-1.5 md:mb-2">Archivo Local</label>
                <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-3 md:p-4">
                    <p class="text-yellow-400 text-xs md:text-sm">Funcionalidad en desarrollo. Usa Bunny.net.</p>
                </div>
            </div>

            <!-- Duration (hidden, auto-detected) & Order -->
            <div class="grid grid-cols-1 gap-3 md:gap-4">
                <!-- Duration is hidden and auto-detected from Bunny.net (stored in seconds) -->
                <input type="hidden" id="lesson-duration" value="{{ $lesson ? $lesson->duration : 0 }}">

                <div>
                    <label class="block text-cream text-xs md:text-sm font-bold mb-1.5 md:mb-2">Orden</label>
                    <input type="number" id="lesson-order" min="1" required value="{{ $lesson ? $lesson->order : $nextOrder }}"
                        class="w-full bg-purple-deeper border border-pink-vibrant/20 rounded-lg px-3 md:px-4 py-2 text-sm md:text-base text-cream focus:outline-none focus:border-pink-vibrant transition">
                </div>

                <!-- Duration info message (shown when auto-detected from Bunny.net) -->
                <p id="duration-info" class="text-xs text-cream/60 hidden">
                    <svg class="w-4 h-4 inline text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-green-600 font-medium">Duración detectada automáticamente:</span>
                    <span id="duration-display" class="font-bold">0</span> minutos
                </p>
            </div>

            <!-- Is Trial -->
            <div class="flex items-center bg-purple-deep p-3 md:p-4 rounded-lg border border-pink-vibrant/20">
                <input type="checkbox" id="lesson-is-trial" {{ $lesson && $lesson->is_trial ? 'checked' : '' }}
                    class="w-4 md:w-5 h-4 md:h-5 text-purple-primary bg-purple-deeper border-pink-vibrant/20 rounded focus:ring-purple-primary focus:ring-2">
                <label for="lesson-is-trial" class="ml-2 md:ml-3 text-cream text-xs md:text-sm font-medium cursor-pointer">Lección gratuita (Trial)</label>
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col md:flex-row justify-end gap-2 md:gap-3 pt-4 md:pt-6 border-t border-pink-vibrant/20">
                <a href="{{ route('admin.modules.lessons', $module->id) }}" class="px-4 md:px-6 py-2.5 md:py-3 bg-purple-deep text-cream text-sm md:text-base font-medium rounded-lg hover:bg-purple-darker transition-colors text-center">
                    Cancelar
                </a>
                <button type="submit" id="submit-button" class="px-4 md:px-6 py-2.5 md:py-3 bg-purple-primary hover:bg-purple-dark text-white text-sm md:text-base font-bold rounded-lg shadow-sm hover:shadow transition-all">
                    {{ $lesson ? 'Actualizar Lección' : 'Crear Lección' }}
                </button>
            </div>
    </form>
</div>

@push('scripts')
<script>
/**
 * Lesson Form Manager - Gestión de formulario de lección
 */

// ============================================================================
// CONFIGURACIÓN
// ============================================================================
window.LessonFormConfig = {
    moduleId: {{ $module->id }},
    lessonId: {{ $lesson ? $lesson->id : 'null' }},
    csrfToken: '{{ csrf_token() }}',
    bunnyCdnHostname: '{{ config('bunny.cdn_hostname') }}',
    bunnyLibraryId: '{{ config('bunny.library_id') }}',
    routes: {
        lessons: {
            store: '{{ route('admin.api.lessons.store') }}',
            update: '{{ $lesson ? route('admin.api.lessons.update', ['id' => $lesson->id]) : '' }}',
        },
        bunny: {
            init: '{{ route('admin.lessons.bunny.init') }}',
            confirm: '{{ route('admin.lessons.bunny.confirm') }}'
        },
        uploadThumbnail: '/admin/api/upload-thumbnail',
        backToLessons: '{{ route('admin.modules.lessons', $module->id) }}'
    }
};

const CONFIG = window.LessonFormConfig;

// ============================================================================
// LESSON FORM MANAGER
// ============================================================================
window.LessonFormManager = {
    thumbnailFile: null,
    uploadXhr: null,
    uploadStartTime: 0,
    uploadStartLoaded: 0,

    // ========================================================================
    // INICIALIZACIÓN
    // ========================================================================
    init() {
        this.updateVideoFields();
    },

    // ========================================================================
    // GESTIÓN DE CAMPOS DE VIDEO
    // ========================================================================
    updateVideoFields() {
        const videoType = document.getElementById('lesson-video-type').value;

        document.getElementById('youtube-field').classList.toggle('hidden', videoType !== 'youtube');
        document.getElementById('bunny-field').classList.toggle('hidden', videoType !== 'bunny');
        document.getElementById('local-field').classList.toggle('hidden', videoType !== 'local');

        // Actualizar campos requeridos
        document.getElementById('lesson-youtube-id').required = videoType === 'youtube';
    },

    // ========================================================================
    // GUARDAR LECCIÓN
    // ========================================================================
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
                    this.showAlert('success', 'Imagen subida correctamente');
                } catch (error) {
                    this.showAlert('error', 'Error al subir la imagen: ' + error.message);
                    submitButton.disabled = false;
                    submitButton.textContent = CONFIG.lessonId ? 'Actualizar Lección' : 'Crear Lección';
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

            if (CONFIG.lessonId) {
                url = CONFIG.routes.lessons.update;
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
                setTimeout(() => {
                    window.location.href = CONFIG.routes.backToLessons;
                }, 1000);
            } else {
                // Manejar errores de validación
                let errorMessage = data.message || 'Error al guardar la lección';

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

                this.showAlert('error', errorMessage);
            }
        } catch (error) {
            console.error('Error saving lesson:', error);
            this.showAlert('error', 'Error de conexión al guardar la lección');
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = CONFIG.lessonId ? 'Actualizar Lección' : 'Crear Lección';
        }
    },

    // ========================================================================
    // FUNCIONES DE SUBIDA A BUNNY.NET
    // ========================================================================
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
                            this.showBunnyVideoPreview(initData.video_id);
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

    // ========================================================================
    // FUNCIONES DE THUMBNAIL
    // ========================================================================
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

    async uploadThumbnailToServer() {
        if (!this.thumbnailFile) {
            return null;
        }

        try {
            const formData = new FormData();
            formData.append('thumbnail', this.thumbnailFile);

            const response = await fetch(CONFIG.routes.uploadThumbnail, {
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
    showAlert(type, message) {
        const container = document.getElementById('alert-container');

        if (!container) return;

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
    }
};

// ============================================================================
// INICIALIZACIÓN
// ============================================================================
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.LessonFormManager.init();
    });
} else {
    window.LessonFormManager.init();
}
</script>
@endpush
@endsection
