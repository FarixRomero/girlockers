<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-display text-2xl text-cream">Módulos de {{ $course->title }}</h2>
                <p class="text-cream/60 text-sm mt-1">{{ $course->level }} • {{ $course->modules->count() }} módulos</p>
            </div>
            <a href="{{ route('admin.courses.index') }}" wire:navigate class="text-pink-vibrant hover:text-pink-light text-sm flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver a Cursos
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

    <!-- Course Info Card -->
    <div class="card-premium mb-6">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <p class="text-cream/70 mb-2">{{ $course->description }}</p>
                <div class="flex items-center space-x-4 text-sm text-cream/60">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        {{ $course->modules->count() }} módulos
                    </span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $course->modules->sum('lessons_count') }} lecciones
                    </span>
                    @if($course->is_published)
                        <span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs rounded-full font-bold">
                            ✓ Publicado
                        </span>
                    @else
                        <span class="px-2 py-1 bg-orange-500/20 text-orange-400 text-xs rounded-full font-bold">
                            📝 Borrador
                        </span>
                    @endif
                </div>
            </div>
            <button wire:click="openCreateModal" class="btn-primary ml-4">
                + Nuevo Módulo
            </button>
        </div>
    </div>

    <!-- Modules List -->
    <div class="space-y-4">
        @forelse($course->modules as $module)
            <div class="card-premium" wire:key="module-{{ $module->id }}">
                <div class="flex items-start justify-between">
                    <div class="flex items-start flex-1">
                        <!-- Order Badge -->
                        <div class="w-12 h-12 rounded-lg bg-gradient-pink flex items-center justify-center text-cream font-bold text-lg mr-4">
                            {{ $module->order }}
                        </div>

                        <!-- Module Info -->
                        <div class="flex-1">
                            <h3 class="font-display text-lg text-cream mb-1">{{ $module->title }}</h3>
                            <p class="text-cream/70 text-sm mb-3">{{ $module->description }}</p>

                            <div class="flex items-center space-x-4 text-xs text-cream/60">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $module->lessons_count }} lecciones
                                </span>
                                <a href="{{ route('admin.modules.lessons', $module->id) }}" wire:navigate class="text-pink-vibrant hover:text-pink-light flex items-center">
                                    Gestionar lecciones →
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center space-x-2 ml-4">
                        <!-- Move Up -->
                        @if(!$loop->first)
                            <button
                                wire:click="moveUp({{ $module->id }})"
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
                                wire:click="moveDown({{ $module->id }})"
                                class="p-2 text-cream/70 hover:text-cream hover:bg-purple-deep rounded-lg transition"
                                title="Mover abajo">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        @endif

                        <!-- Edit -->
                        <button
                            wire:click="openEditModal({{ $module->id }})"
                            class="p-2 text-pink-vibrant hover:bg-purple-deep rounded-lg transition"
                            title="Editar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>

                        <!-- Delete -->
                        <button
                            wire:click="deleteModule({{ $module->id }})"
                            wire:confirm="¿Estás seguro de eliminar el módulo '{{ $module->title }}'?"
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-cream/70 mb-4">Este curso aún no tiene módulos</p>
                <button wire:click="openCreateModal" class="btn-primary">
                    Crear Primer Módulo
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
                    <form wire:submit="saveModule">
                        <div class="px-6 py-4 border-b border-pink-vibrant/20">
                            <h3 class="text-xl font-display text-cream">
                                {{ $isEditing ? 'Editar Módulo' : 'Crear Nuevo Módulo' }}
                            </h3>
                        </div>

                        <div class="px-6 py-4 space-y-4">
                            <!-- Title -->
                            <div>
                                <label class="block text-cream/70 text-sm mb-2">Título *</label>
                                <input
                                    type="text"
                                    wire:model="title"
                                    class="w-full bg-purple-deeper border border-pink-vibrant/20 rounded-lg px-4 py-2 text-cream focus:outline-none focus:border-pink-vibrant transition"
                                    placeholder="Ej: Introducción al Hip-Hop">
                                @error('title') <p class="mt-1 text-red-400 text-sm">{{ $message }}</p> @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-cream/70 text-sm mb-2">Descripción *</label>
                                <textarea
                                    wire:model="description"
                                    rows="4"
                                    class="w-full bg-purple-deeper border border-pink-vibrant/20 rounded-lg px-4 py-2 text-cream focus:outline-none focus:border-pink-vibrant transition resize-none"
                                    placeholder="Describe el contenido de este módulo..."></textarea>
                                @error('description') <p class="mt-1 text-red-400 text-sm">{{ $message }}</p> @enderror
                            </div>

                            <!-- Order -->
                            <div>
                                <label class="block text-cream/70 text-sm mb-2">Orden *</label>
                                <input
                                    type="number"
                                    wire:model="order"
                                    min="1"
                                    class="w-full bg-purple-deeper border border-pink-vibrant/20 rounded-lg px-4 py-2 text-cream focus:outline-none focus:border-pink-vibrant transition"
                                    placeholder="1">
                                @error('order') <p class="mt-1 text-red-400 text-sm">{{ $message }}</p> @enderror
                                <p class="mt-1 text-cream/50 text-xs">El orden determina la secuencia de los módulos en el curso.</p>
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
                                {{ $isEditing ? 'Actualizar' : 'Crear' }} Módulo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
