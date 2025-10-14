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
    <div class="card-premium mb-6 bg-gradient-to-br from-purple-darkest via-purple-deep to-purple-darkest border-2 border-pink-vibrant/30">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <p class="text-cream/80 mb-4 text-base leading-relaxed">{{ $course->description }}</p>
                <div class="flex items-center flex-wrap gap-3 text-sm">
                    <span class="flex items-center px-4 py-2 bg-purple-deep/70 rounded-xl text-cream border border-pink-vibrant/30 hover:border-pink-vibrant/60 transition-colors">
                        <svg class="w-5 h-5 mr-2 text-pink-vibrant" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                        </svg>
                        <span class="font-bold text-lg">{{ $course->modules->count() }}</span>
                        <span class="ml-1.5">{{ $course->modules->count() === 1 ? 'módulo' : 'módulos' }}</span>
                    </span>
                    <span class="flex items-center px-4 py-2 bg-purple-deep/70 rounded-xl text-cream border border-pink-vibrant/30 hover:border-pink-vibrant/60 transition-colors">
                        <svg class="w-5 h-5 mr-2 text-pink-vibrant" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-bold text-lg">{{ $course->modules->sum('lessons_count') }}</span>
                        <span class="ml-1.5">{{ $course->modules->sum('lessons_count') === 1 ? 'lección' : 'lecciones' }}</span>
                    </span>
                    @if($course->is_published)
                        <span class="px-4 py-2 bg-green-500/20 text-green-400 rounded-xl font-bold border border-green-500/40 flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Publicado
                        </span>
                    @else
                        <span class="px-4 py-2 bg-orange-500/20 text-orange-400 rounded-xl font-bold border border-orange-500/40 flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                            </svg>
                            Borrador
                        </span>
                    @endif
                </div>
            </div>
            <button wire:click="openCreateModal" class="btn-primary ml-4 flex items-center shadow-lg shadow-pink-vibrant/20">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Nuevo Módulo
            </button>
        </div>
    </div>

    <!-- Modules List -->
    <div class="space-y-4">
        @forelse($course->modules as $module)
            <div class="card-premium hover:shadow-2xl hover:shadow-pink-vibrant/20 transition-all duration-300 group" wire:key="module-{{ $module->id }}">
                <div class="flex items-start justify-between">
                    <div class="flex items-start flex-1">
                        <!-- Order Badge with Icon -->
                        <div class="relative mr-4">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-pink-vibrant via-pink-light to-orange-500 flex items-center justify-center text-cream font-bold text-xl shadow-lg shadow-pink-vibrant/30 group-hover:scale-110 transition-transform duration-300">
                                {{ $module->order }}
                            </div>
                            <!-- Decorative icon -->
                            <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-purple-darkest rounded-full flex items-center justify-center border-2 border-purple-deep">
                                <svg class="w-3 h-3 text-pink-vibrant" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Module Info -->
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="font-display text-xl text-cream group-hover:text-pink-vibrant transition-colors duration-300">
                                    {{ $module->title }}
                                </h3>
                            </div>
                            <p class="text-cream/70 text-sm mb-4 leading-relaxed">{{ $module->description }}</p>

                            <div class="flex items-center space-x-4 text-sm">
                                <span class="flex items-center px-3 py-1.5 bg-purple-deep/50 rounded-lg text-cream/80 border border-pink-vibrant/20">
                                    <svg class="w-4 h-4 mr-1.5 text-pink-vibrant" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="font-medium">{{ $module->lessons_count }}</span>
                                    <span class="ml-1">{{ $module->lessons_count === 1 ? 'lección' : 'lecciones' }}</span>
                                </span>
                                <a href="{{ route('admin.modules.lessons', $module->id) }}"
                                   wire:navigate
                                   class="flex items-center px-4 py-1.5 bg-gradient-to-r from-pink-vibrant to-pink-light text-cream rounded-lg hover:shadow-lg hover:shadow-pink-vibrant/30 transition-all duration-300 font-medium">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Gestionar lecciones
                                    <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center space-x-1 ml-4">
                        <!-- Move Up -->
                        @if(!$loop->first)
                            <button
                                wire:click="moveUp({{ $module->id }})"
                                class="p-2.5 text-cream/70 hover:text-cream hover:bg-purple-deep rounded-xl transition-all duration-200 hover:scale-110"
                                title="Mover arriba">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"></path>
                                </svg>
                            </button>
                        @endif

                        <!-- Move Down -->
                        @if(!$loop->last)
                            <button
                                wire:click="moveDown({{ $module->id }})"
                                class="p-2.5 text-cream/70 hover:text-cream hover:bg-purple-deep rounded-xl transition-all duration-200 hover:scale-110"
                                title="Mover abajo">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        @endif

                        <!-- Edit -->
                        <button
                            wire:click="openEditModal({{ $module->id }})"
                            class="p-2.5 text-pink-vibrant hover:bg-pink-vibrant/10 rounded-xl transition-all duration-200 hover:scale-110"
                            title="Editar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>

                        <!-- Delete -->
                        <button
                            wire:click="deleteModule({{ $module->id }})"
                            wire:confirm="¿Estás seguro de eliminar el módulo '{{ $module->title }}'?"
                            class="p-2.5 text-red-400 hover:bg-red-500/10 rounded-xl transition-all duration-200 hover:scale-110"
                            title="Eliminar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="card-premium text-center py-16 bg-gradient-to-br from-purple-darkest via-purple-deep to-purple-darkest border-2 border-dashed border-pink-vibrant/30">
                <div class="inline-block p-6 bg-gradient-to-br from-pink-vibrant/10 to-purple-deep/30 rounded-3xl mb-6">
                    <svg class="w-20 h-20 text-pink-vibrant mx-auto" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-display text-cream mb-2">¡Comienza a crear contenido!</h3>
                <p class="text-cream/70 mb-6 max-w-md mx-auto">Este curso aún no tiene módulos. Crea el primer módulo para comenzar a estructurar tu contenido educativo.</p>
                <button wire:click="openCreateModal" class="btn-primary inline-flex items-center shadow-lg shadow-pink-vibrant/30 hover:shadow-pink-vibrant/50 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
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
