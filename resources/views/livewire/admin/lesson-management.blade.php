<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-display text-2xl text-cream">Lecciones de {{ $module->title }}</h2>
                <p class="text-cream/60 text-sm mt-1">
                    <a href="{{ route('admin.courses.modules', $module->course_id) }}" wire:navigate class="hover:text-pink-vibrant">
                        {{ $module->course->title }}
                    </a>
                    • {{ $module->lessons->count() }} lecciones
                </p>
            </div>
            <a href="{{ route('admin.courses.modules', $module->course_id) }}" wire:navigate class="text-pink-vibrant hover:text-pink-light text-sm flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver a Módulos
            </a>
        </div>
    </x-slot>

    <!-- Success/Error Messages -->
    @if(session()->has('success'))
        <div class="mb-6 p-4 bg-green-500/10 border border-green-500/30 rounded-lg">
            <p class="text-green-400 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('success') }}
            </p>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="mb-6 p-4 bg-red-500/10 border border-red-500/30 rounded-lg">
            <p class="text-red-400 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                {{ session('error') }}
            </p>
        </div>
    @endif

    <!-- Module Info Card -->
    <div class="card-premium mb-6">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <p class="text-cream/70 mb-2">{{ $module->description }}</p>
                <div class="flex items-center space-x-4 text-sm text-cream/60">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $module->lessons->count() }} lecciones
                    </span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $module->lessons->sum('duration') }} min total
                    </span>
                    <span class="px-2 py-1 bg-pink-vibrant/20 text-pink-vibrant text-xs rounded-full font-bold">
                        Orden: {{ $module->order }}
                    </span>
                </div>
            </div>
            <button wire:click="openCreateModal" class="btn-primary ml-4">
                + Nueva Lección
            </button>
        </div>
    </div>

    <!-- Lessons List -->
    <div class="space-y-4">
        @forelse($module->lessons as $lesson)
            <div class="card-premium" wire:key="lesson-{{ $lesson->id }}">
                <div class="flex items-start justify-between">
                    <div class="flex items-start flex-1">
                        <!-- Order Badge -->
                        <div class="w-12 h-12 rounded-lg bg-gradient-pink flex items-center justify-center text-cream font-bold text-lg mr-4">
                            {{ $lesson->order }}
                        </div>

                        <!-- Lesson Info -->
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-1">
                                <h3 class="font-display text-lg text-cream">{{ $lesson->title }}</h3>
                                @if($lesson->is_trial)
                                    <span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs rounded-full font-bold">
                                        ✓ Trial
                                    </span>
                                @else
                                    <span class="px-2 py-1 bg-orange-500/20 text-orange-400 text-xs rounded-full font-bold">
                                        Premium
                                    </span>
                                @endif
                            </div>

                            <p class="text-cream/70 text-sm mb-3">{{ $lesson->description }}</p>

                            <div class="flex items-center space-x-4 text-xs text-cream/60">
                                @if($lesson->video_type === 'youtube')
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-red-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                        </svg>
                                        YouTube: {{ $lesson->youtube_id }}
                                    </span>
                                @else
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                                        </svg>
                                        Video local
                                    </span>
                                @endif

                                @if($lesson->duration)
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $lesson->duration }} min
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center space-x-2 ml-4">
                        <!-- Toggle Trial -->
                        <button
                            wire:click="toggleTrial({{ $lesson->id }})"
                            class="p-2 {{ $lesson->is_trial ? 'text-green-400' : 'text-cream/40' }} hover:bg-purple-deep rounded-lg transition"
                            title="{{ $lesson->is_trial ? 'Marcar como Premium' : 'Marcar como Trial' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </button>

                        <!-- Move Up -->
                        @if(!$loop->first)
                            <button
                                wire:click="moveUp({{ $lesson->id }})"
                                class="p-2 text-cream/70 hover:text-cream hover:bg-purple-deep rounded-lg transition"
                                title="Mover arriba">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            </button>
                        @endif

                        <!-- Move Down -->
                        @if(!$loop->last)
                            <button
                                wire:click="moveDown({{ $lesson->id }})"
                                class="p-2 text-cream/70 hover:text-cream hover:bg-purple-deep rounded-lg transition"
                                title="Mover abajo">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        @endif

                        <!-- Edit -->
                        <button
                            wire:click="openEditModal({{ $lesson->id }})"
                            class="p-2 text-pink-vibrant hover:bg-purple-deep rounded-lg transition"
                            title="Editar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>

                        <!-- Delete -->
                        <button
                            wire:click="deleteLesson({{ $lesson->id }})"
                            wire:confirm="¿Estás seguro de eliminar la lección '{{ $lesson->title }}'?"
                            class="p-2 text-red-400 hover:bg-purple-deep rounded-lg transition"
                            title="Eliminar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="card-premium text-center py-12">
                <svg class="w-16 h-16 text-cream/30 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-cream/70 mb-4">Este módulo aún no tiene lecciones</p>
                <button wire:click="openCreateModal" class="btn-primary">
                    Crear Primera Lección
                </button>
            </div>
        @endforelse
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showModal') }">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 transition-opacity bg-black/80" wire:click="closeModal"></div>

                <!-- Modal panel -->
                <div class="inline-block overflow-hidden text-left align-bottom transition-all transform card-premium sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <form wire:submit="saveLesson">
                        <div class="px-6 py-4 border-b border-pink-vibrant/20">
                            <h3 class="text-xl font-display text-cream">
                                {{ $isEditing ? 'Editar Lección' : 'Crear Nueva Lección' }}
                            </h3>
                        </div>

                        <div class="px-6 py-4 space-y-4">
                            <!-- Title -->
                            <div>
                                <label class="block text-cream text-sm mb-2 font-medium">Título *</label>
                                <input
                                    type="text"
                                    wire:model="title"
                                    class="w-full bg-purple-deeper border border-pink-vibrant/20 rounded-lg px-4 py-2 text-cream placeholder:text-cream/40 focus:outline-none focus:border-pink-vibrant transition"
                                    placeholder="Ej: Pasos básicos de Hip-Hop">
                                @error('title') <p class="mt-1 text-red-400 text-sm">{{ $message }}</p> @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-cream text-sm mb-2 font-medium">Descripción *</label>
                                <textarea
                                    wire:model="description"
                                    rows="3"
                                    class="w-full bg-purple-deeper border border-pink-vibrant/20 rounded-lg px-4 py-2 text-cream placeholder:text-cream/40 focus:outline-none focus:border-pink-vibrant transition resize-none"
                                    placeholder="Describe el contenido de esta lección..."></textarea>
                                @error('description') <p class="mt-1 text-red-400 text-sm">{{ $message }}</p> @enderror
                            </div>

                            <!-- Video Type -->
                            <div>
                                <label class="block text-cream text-sm mb-2 font-medium">Tipo de Video *</label>
                                <div class="flex space-x-4">
                                    <label class="flex items-center cursor-pointer">
                                        <input
                                            type="radio"
                                            wire:model.live="video_type"
                                            value="youtube"
                                            class="w-4 h-4 text-pink-vibrant bg-purple-deeper border-pink-vibrant/20 focus:ring-pink-vibrant focus:ring-2">
                                        <span class="ml-2 text-cream font-medium">YouTube</span>
                                    </label>
                                    <label class="flex items-center cursor-pointer">
                                        <input
                                            type="radio"
                                            wire:model.live="video_type"
                                            value="local"
                                            class="w-4 h-4 text-pink-vibrant bg-purple-deeper border-pink-vibrant/20 focus:ring-pink-vibrant focus:ring-2">
                                        <span class="ml-2 text-cream font-medium">Video Local</span>
                                    </label>
                                </div>
                            </div>

                            <!-- YouTube ID -->
                            @if($video_type === 'youtube')
                                <div>
                                    <label class="block text-cream text-sm mb-2 font-medium">YouTube ID *</label>
                                    <input
                                        type="text"
                                        wire:model="youtube_id"
                                        class="w-full bg-purple-deeper border border-pink-vibrant/20 rounded-lg px-4 py-2 text-cream placeholder:text-cream/40 focus:outline-none focus:border-pink-vibrant transition"
                                        placeholder="dQw4w9WgXcQ">
                                    @error('youtube_id') <p class="mt-1 text-red-400 text-sm">{{ $message }}</p> @enderror
                                    <p class="mt-1 text-cream/50 text-xs">El ID del video de YouTube (ej: dQw4w9WgXcQ de https://www.youtube.com/watch?v=dQw4w9WgXcQ)</p>
                                </div>
                            @endif

                            <!-- Video Upload -->
                            @if($video_type === 'local')
                                <div>
                                    <label class="block text-cream text-sm mb-2 font-medium">
                                        Archivo de Video {{ !$isEditing ? '*' : '(opcional para actualizar)' }}
                                    </label>
                                    <input
                                        type="file"
                                        wire:model="video_file"
                                        accept="video/*"
                                        class="w-full bg-purple-deeper border border-pink-vibrant/20 rounded-lg px-4 py-2 text-cream focus:outline-none focus:border-pink-vibrant transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-pink-vibrant file:text-cream file:cursor-pointer">
                                    @error('video_file') <p class="mt-1 text-red-400 text-sm">{{ $message }}</p> @enderror
                                    <p class="mt-1 text-cream/50 text-xs">Formatos: MP4, MOV, AVI, WMV (máx 500MB)</p>

                                    @if ($video_file)
                                        <div class="mt-2">
                                            <p class="text-green-400 text-sm font-medium">✓ Video seleccionado: {{ $video_file->getClientOriginalName() }}</p>
                                        </div>
                                    @elseif ($video_path)
                                        <div class="mt-2">
                                            <p class="text-cream text-sm">Video actual: {{ basename($video_path) }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Duration and Order -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-cream text-sm mb-2 font-medium">Duración (minutos)</label>
                                    <input
                                        type="number"
                                        wire:model="duration"
                                        min="0"
                                        class="w-full bg-purple-deeper border border-pink-vibrant/20 rounded-lg px-4 py-2 text-cream placeholder:text-cream/40 focus:outline-none focus:border-pink-vibrant transition"
                                        placeholder="0">
                                    @error('duration') <p class="mt-1 text-red-400 text-sm">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-cream text-sm mb-2 font-medium">Orden *</label>
                                    <input
                                        type="number"
                                        wire:model="order"
                                        min="1"
                                        class="w-full bg-purple-deeper border border-pink-vibrant/20 rounded-lg px-4 py-2 text-cream placeholder:text-cream/40 focus:outline-none focus:border-pink-vibrant transition"
                                        placeholder="1">
                                    @error('order') <p class="mt-1 text-red-400 text-sm">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <!-- Is Trial -->
                            <div class="flex items-center">
                                <input
                                    type="checkbox"
                                    wire:model="is_trial"
                                    id="is_trial"
                                    class="w-4 h-4 text-pink-vibrant bg-purple-deeper border-pink-vibrant/20 rounded focus:ring-pink-vibrant focus:ring-2">
                                <label for="is_trial" class="ml-2 text-cream text-sm font-medium">
                                    Marcar como lección de prueba (accesible sin suscripción)
                                </label>
                            </div>
                        </div>

                        <div class="px-6 py-4 border-t border-pink-vibrant/20 flex justify-end space-x-3">
                            <button
                                type="button"
                                wire:click="closeModal"
                                class="px-4 py-2 bg-purple-deep text-cream/70 rounded-lg hover:bg-purple-deeper transition">
                                Cancelar
                            </button>
                            <button type="submit" class="btn-primary">
                                {{ $isEditing ? 'Actualizar' : 'Crear' }} Lección
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
