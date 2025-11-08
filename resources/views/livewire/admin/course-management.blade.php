<div class="min-h-screen bg-gray-50 pb-20 lg:pb-8">
    <x-slot name="header">
        Gestión de Cursos
    </x-slot>

    <!-- Header Section -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-3xl mx-auto px-4 py-4">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <h1 class="font-bold text-xl md:text-2xl text-gray-900 mb-1">Cursos</h1>
                    <p class="text-gray-600 text-sm">{{ $stats['total'] }} {{ $stats['total'] === 1 ? 'curso' : 'cursos' }} • {{ $stats['published'] }} {{ $stats['published'] === 1 ? 'publicado' : 'publicados' }}</p>
                </div>
                <button wire:click="openCreateModal" class="hidden lg:flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-semibold transition-colors flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nuevo Curso
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 py-6">
        <!-- Success/Error Messages -->
        @if(session()->has('success'))
            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-700 text-sm">{{ session('success') }}</p>
            </div>
        @endif

        @if(session()->has('error'))
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-red-700 text-sm">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Search Bar -->
        <div class="bg-white rounded-2xl p-3 mb-4 border border-gray-200">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Buscar cursos..."
                class="w-full bg-transparent border-0 focus:outline-none focus:ring-0 text-sm text-gray-900 placeholder-gray-400">
        </div>

        <!-- Filters (Collapsible on mobile) -->
        <div class="bg-white rounded-2xl p-3 mb-4 border border-gray-200" x-data="{ showFilters: false }">
            <button @click="showFilters = !showFilters" class="w-full flex items-center justify-between text-sm text-gray-700">
                <span class="font-medium">Filtros</span>
                <svg class="w-5 h-5 transition-transform" :class="showFilters ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-show="showFilters" x-collapse class="mt-3 space-y-3">
                <div>
                    <select
                        wire:model.live="filterLevel"
                        class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="all">Todos los niveles</option>
                        <option value="principiante">Principiante</option>
                        <option value="intermedio">Intermedio</option>
                        <option value="avanzado">Avanzado</option>
                    </select>
                </div>
                <div>
                    <select
                        wire:model.live="filterPublished"
                        class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="all">Todos los estados</option>
                        <option value="published">Publicados</option>
                        <option value="draft">Borradores</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Courses List -->
        <div class="space-y-3">
        @forelse($courses as $course)
            <div class="bg-white rounded-2xl p-4 relative group" wire:key="course-{{ $course->id }}">
                <a href="{{ route('admin.courses.modules', $course->id) }}" wire:navigate class="flex items-center gap-3 relative z-10">
                    <!-- Course Image -->
                    @if($course->image)
                        <img src="{{ $course->image_url }}" alt="{{ $course->title }}" class="w-16 h-16 md:w-20 md:h-20 object-cover rounded-lg flex-shrink-0">
                    @else
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 md:w-10 md:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif

                    <!-- Course Info -->
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-base text-gray-900">{{ $course->title }}</h3>
                        <p class="text-sm text-gray-500">
                            {{ ucfirst($course->level) }}
                            @if($course->instructor) • {{ $course->instructor->name }}@endif
                        </p>
                        <p class="text-xs text-gray-400">{{ $course->modules_count }} módulos • {{ $course->modules->sum('lessons_count') }} lecciones</p>
                    </div>

                    <!-- Status Badge -->
                    @if($course->is_published)
                        <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium border border-green-200">Publicado</span>
                    @else
                        <span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs rounded-full font-medium border border-orange-200">Borrador</span>
                    @endif
                </a>

                <!-- Actions -->
                <div class="flex items-center gap-1 mt-3 pt-3 border-t border-gray-100">
                    <button
                        wire:click="togglePublished({{ $course->id }})"
                        class="p-2 text-{{ $course->is_published ? 'green' : 'gray' }}-500 hover:bg-gray-50 rounded-lg transition"
                        title="{{ $course->is_published ? 'Despublicar' : 'Publicar' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                    <button
                        wire:click="openEditModal({{ $course->id }})"
                        class="p-2 text-purple-500 hover:bg-purple-50 rounded-lg transition"
                        title="Editar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <button
                        wire:click="deleteCourse({{ $course->id }})"
                        wire:confirm="¿Estás seguro de eliminar el curso '{{ $course->title }}'?"
                        class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition"
                        title="Eliminar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl text-center py-12">
                <p class="text-gray-500 text-sm mb-4">No se encontraron cursos</p>
                <button wire:click="openCreateModal" class="inline-flex items-center px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg text-sm font-medium transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Crear Primer Curso
                </button>
            </div>
        @endforelse
        </div>

        <!-- Pagination -->
        @if($courses->hasPages())
            <div class="mt-6">
                {{ $courses->links() }}
            </div>
        @endif

        <!-- Nuevo Curso Button (Fixed Bottom) -->
        <div class="fixed bottom-24 left-0 right-0 px-4 lg:hidden">
            <div class="max-w-3xl mx-auto">
                <button wire:click="openCreateModal" class="w-full flex items-center justify-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-2xl shadow-lg font-semibold transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nuevo Curso
                </button>
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showModal') }">
            <div class="flex items-end md:items-center justify-center min-h-screen px-0 md:px-4 pt-4 pb-20 md:pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 transition-opacity bg-black/50 backdrop-blur-sm" wire:click="closeModal"></div>

                <!-- Modal panel -->
                <div class="inline-block overflow-hidden text-left align-bottom md:align-middle transition-all transform bg-white rounded-t-2xl md:rounded-2xl shadow-2xl sm:my-8 w-full sm:max-w-lg mb-0 md:mb-0">
                    <form wire:submit="saveCourse">
                        <!-- Header -->
                        <div class="px-4 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $isEditing ? 'Editar Curso' : 'Nuevo Curso' }}
                                </h3>
                                <button type="button" wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="px-4 py-4 space-y-4 max-h-[60vh] overflow-y-auto">
                            <!-- Title -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Título</label>
                                <input
                                    type="text"
                                    wire:model.live.debounce.300ms="title"
                                    class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    placeholder="Ej: Fundamentos del Hip-Hop">
                                @error('title') <p class="mt-1 text-red-500 text-xs">{{ $message }}</p> @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                                <textarea
                                    wire:model="description"
                                    rows="4"
                                    class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent resize-none"
                                    placeholder="Describe el contenido y objetivos del curso..."></textarea>
                                @error('description') <p class="mt-1 text-red-500 text-xs">{{ $message }}</p> @enderror
                            </div>

                            <!-- Instructor -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Instructor</label>
                                <select
                                    wire:model="instructor_id"
                                    class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <option value="">Sin instructor asignado</option>
                                    @foreach($instructors as $instructor)
                                        <option value="{{ $instructor->id }}">{{ $instructor->name }}</option>
                                    @endforeach
                                </select>
                                @error('instructor_id') <p class="mt-1 text-red-500 text-xs">{{ $message }}</p> @enderror
                            </div>

                            <!-- Level -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nivel</label>
                                <select
                                    wire:model="level"
                                    class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <option value="principiante">Principiante</option>
                                    <option value="intermedio">Intermedio</option>
                                    <option value="avanzado">Avanzado</option>
                                </select>
                                @error('level') <p class="mt-1 text-red-500 text-xs">{{ $message }}</p> @enderror
                            </div>

                            <!-- Image -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Imagen del Curso</label>
                                <input
                                    type="file"
                                    wire:model="image"
                                    accept="image/*"
                                    class="w-full text-sm text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-purple-500 file:text-white hover:file:bg-purple-600 file:cursor-pointer file:transition">
                                @error('image') <p class="mt-1 text-red-500 text-xs">{{ $message }}</p> @enderror

                                @if ($image)
                                    <div class="mt-3">
                                        <p class="text-gray-700 text-xs mb-2">Vista previa:</p>
                                        <img src="{{ $image->temporaryUrl() }}" class="w-24 h-24 object-cover rounded-lg">
                                    </div>
                                @elseif ($existingImage)
                                    <div class="mt-3">
                                        <p class="text-gray-700 text-xs mb-2">Imagen actual:</p>
                                        <img src="{{ asset('storage/' . $existingImage) }}" class="w-24 h-24 object-cover rounded-lg">
                                    </div>
                                @endif
                            </div>

                            <!-- Published -->
                            <div class="flex items-center">
                                <input
                                    type="checkbox"
                                    wire:model="is_published"
                                    id="is_published"
                                    class="w-4 h-4 text-purple-500 bg-white border-gray-300 rounded focus:ring-purple-500 focus:ring-2">
                                <label for="is_published" class="ml-2 text-sm text-gray-700 cursor-pointer">Publicar curso inmediatamente</label>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="px-4 py-4 border-t border-gray-200 flex flex-col-reverse md:flex-row justify-end gap-2 md:gap-3">
                            <button
                                type="button"
                                wire:click="closeModal"
                                class="w-full md:w-auto px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-sm font-medium">
                                Cancelar
                            </button>
                            <button type="submit" class="w-full md:w-auto px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition text-sm font-medium">
                                {{ $isEditing ? 'Guardar Cambios' : 'Crear Curso' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
