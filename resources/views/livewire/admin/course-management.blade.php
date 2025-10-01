<div>
    <x-slot name="header">
        Gestión de Cursos
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

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="card-premium">
            <div class="text-cream/70 text-sm mb-1">Total Cursos</div>
            <div class="text-2xl font-bold text-cream">{{ $stats['total'] }}</div>
        </div>
        <div class="card-premium">
            <div class="text-cream/70 text-sm mb-1">Publicados</div>
            <div class="text-2xl font-bold text-green-400">{{ $stats['published'] }}</div>
        </div>
        <div class="card-premium">
            <div class="text-cream/70 text-sm mb-1">Borradores</div>
            <div class="text-2xl font-bold text-orange-400">{{ $stats['draft'] }}</div>
        </div>
    </div>

    <!-- Filters & Actions -->
    <div class="card-premium mb-6">
        <div class="flex flex-col md:flex-row gap-4 items-end">
            <!-- Search -->
            <div class="flex-1">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Buscar cursos..."
                    class="w-full bg-purple-deeper border border-pink-vibrant/20 rounded-lg px-4 py-2 text-cream placeholder-cream/40 focus:outline-none focus:border-pink-vibrant transition">
            </div>

            <!-- Level Filter -->
            <div>
                <select
                    wire:model.live="filterLevel"
                    class="bg-purple-deeper border border-pink-vibrant/20 rounded-lg px-4 py-2 text-cream focus:outline-none focus:border-pink-vibrant transition">
                    <option value="all">Todos los niveles</option>
                    <option value="principiante">Principiante</option>
                    <option value="intermedio">Intermedio</option>
                    <option value="avanzado">Avanzado</option>
                </select>
            </div>

            <!-- Published Filter -->
            <div>
                <select
                    wire:model.live="filterPublished"
                    class="bg-purple-deeper border border-pink-vibrant/20 rounded-lg px-4 py-2 text-cream focus:outline-none focus:border-pink-vibrant transition">
                    <option value="all">Todos los estados</option>
                    <option value="published">Publicados</option>
                    <option value="draft">Borradores</option>
                </select>
            </div>

            <!-- Create Button -->
            <button wire:click="openCreateModal" class="btn-primary whitespace-nowrap">
                + Nuevo Curso
            </button>
        </div>
    </div>

    <!-- Courses Table -->
    <div class="card-premium overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-pink-vibrant/20">
                        <th class="text-left py-4 px-4 text-cream/70 font-medium text-sm">Curso</th>
                        <th class="text-left py-4 px-4 text-cream/70 font-medium text-sm">Nivel</th>
                        <th class="text-left py-4 px-4 text-cream/70 font-medium text-sm">Contenido</th>
                        <th class="text-left py-4 px-4 text-cream/70 font-medium text-sm">Estado</th>
                        <th class="text-right py-4 px-4 text-cream/70 font-medium text-sm">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr class="border-b border-pink-vibrant/10 hover:bg-purple-deep/50 transition" wire:key="course-{{ $course->id }}">
                            <td class="py-4 px-4">
                                <div class="flex items-center">
                                    @if($course->image_path)
                                        <img src="{{ asset('storage/' . $course->image_path) }}" alt="{{ $course->title }}" class="w-16 h-16 object-cover rounded-lg mr-3">
                                    @else
                                        <div class="w-16 h-16 bg-gradient-pink rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-8 h-8 text-cream" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-cream font-medium">{{ $course->title }}</p>
                                        <p class="text-cream/60 text-sm">{{ Str::limit($course->description, 60) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold
                                    {{ $course->level === 'principiante' ? 'bg-green-500/20 text-green-400' : '' }}
                                    {{ $course->level === 'intermedio' ? 'bg-orange-500/20 text-orange-400' : '' }}
                                    {{ $course->level === 'avanzado' ? 'bg-red-500/20 text-red-400' : '' }}">
                                    {{ ucfirst($course->level) }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <div class="text-sm text-cream/70">
                                    <p>{{ $course->modules_count }} módulos</p>
                                    <p>{{ $course->modules->sum('lessons_count') }} lecciones</p>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                @if($course->is_published)
                                    <span class="px-3 py-1 bg-green-500/20 text-green-400 text-xs rounded-full font-bold">
                                        ✓ Publicado
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-orange-500/20 text-orange-400 text-xs rounded-full font-bold">
                                        📝 Borrador
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center justify-end space-x-2">
                                    <a
                                        href="{{ route('admin.courses.modules', $course->id) }}"
                                        wire:navigate
                                        class="p-2 text-cream/70 hover:text-cream hover:bg-purple-deep rounded-lg transition"
                                        title="Gestionar módulos">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </a>

                                    <button
                                        wire:click="togglePublished({{ $course->id }})"
                                        class="p-2 hover:bg-purple-deep rounded-lg transition"
                                        title="{{ $course->is_published ? 'Despublicar' : 'Publicar' }}">
                                        <svg class="w-5 h-5 {{ $course->is_published ? 'text-green-400' : 'text-cream/40' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>

                                    <button
                                        wire:click="openEditModal({{ $course->id }})"
                                        class="p-2 text-pink-vibrant hover:bg-purple-deep rounded-lg transition"
                                        title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>

                                    <button
                                        wire:click="deleteCourse({{ $course->id }})"
                                        wire:confirm="¿Estás seguro de eliminar el curso '{{ $course->title }}'?"
                                        class="p-2 text-red-400 hover:bg-purple-deep rounded-lg transition"
                                        title="Eliminar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <svg class="w-16 h-16 text-cream/30 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-cream/70">No se encontraron cursos</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($courses->hasPages())
        <div class="mt-6">
            {{ $courses->links() }}
        </div>
    @endif

    <!-- Create/Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showModal') }">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 transition-opacity bg-black/80" wire:click="closeModal"></div>

                <!-- Modal panel -->
                <div class="inline-block overflow-hidden text-left align-bottom transition-all transform card-premium sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <form wire:submit="saveCourse">
                        <div class="px-6 py-4 border-b border-pink-vibrant/20">
                            <h3 class="text-xl font-display text-cream">
                                {{ $isEditing ? 'Editar Curso' : 'Crear Nuevo Curso' }}
                            </h3>
                        </div>

                        <div class="px-6 py-4 space-y-4">
                            <!-- Title -->
                            <div>
                                <label class="block text-cream/70 text-sm mb-2">Título *</label>
                                <input
                                    type="text"
                                    wire:model.live.debounce.300ms="title"
                                    class="w-full bg-purple-deeper border border-pink-vibrant/20 rounded-lg px-4 py-2 text-cream focus:outline-none focus:border-pink-vibrant transition"
                                    placeholder="Ej: Fundamentos del Hip-Hop">
                                @error('title') <p class="mt-1 text-red-400 text-sm">{{ $message }}</p> @enderror
                            </div>

                            <!-- Slug -->
                            <div>
                                <label class="block text-cream/70 text-sm mb-2">Slug *</label>
                                <input
                                    type="text"
                                    wire:model="slug"
                                    class="w-full bg-purple-deeper border border-pink-vibrant/20 rounded-lg px-4 py-2 text-cream focus:outline-none focus:border-pink-vibrant transition"
                                    placeholder="fundamentos-del-hip-hop">
                                @error('slug') <p class="mt-1 text-red-400 text-sm">{{ $message }}</p> @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-cream/70 text-sm mb-2">Descripción *</label>
                                <textarea
                                    wire:model="description"
                                    rows="4"
                                    class="w-full bg-purple-deeper border border-pink-vibrant/20 rounded-lg px-4 py-2 text-cream focus:outline-none focus:border-pink-vibrant transition resize-none"
                                    placeholder="Describe el contenido y objetivos del curso..."></textarea>
                                @error('description') <p class="mt-1 text-red-400 text-sm">{{ $message }}</p> @enderror
                            </div>

                            <!-- Level -->
                            <div>
                                <label class="block text-cream/70 text-sm mb-2">Nivel *</label>
                                <select
                                    wire:model="level"
                                    class="w-full bg-purple-deeper border border-pink-vibrant/20 rounded-lg px-4 py-2 text-cream focus:outline-none focus:border-pink-vibrant transition">
                                    <option value="principiante">Principiante</option>
                                    <option value="intermedio">Intermedio</option>
                                    <option value="avanzado">Avanzado</option>
                                </select>
                                @error('level') <p class="mt-1 text-red-400 text-sm">{{ $message }}</p> @enderror
                            </div>

                            <!-- Image -->
                            <div>
                                <label class="block text-cream/70 text-sm mb-2">Imagen del Curso</label>
                                <input
                                    type="file"
                                    wire:model="image"
                                    accept="image/*"
                                    class="w-full bg-purple-deeper border border-pink-vibrant/20 rounded-lg px-4 py-2 text-cream focus:outline-none focus:border-pink-vibrant transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-pink-vibrant file:text-cream file:cursor-pointer">
                                @error('image') <p class="mt-1 text-red-400 text-sm">{{ $message }}</p> @enderror

                                @if ($image)
                                    <div class="mt-2">
                                        <p class="text-cream/70 text-sm mb-2">Vista previa:</p>
                                        <img src="{{ $image->temporaryUrl() }}" class="w-32 h-32 object-cover rounded-lg">
                                    </div>
                                @elseif ($image_path)
                                    <div class="mt-2">
                                        <p class="text-cream/70 text-sm mb-2">Imagen actual:</p>
                                        <img src="{{ asset('storage/' . $image_path) }}" class="w-32 h-32 object-cover rounded-lg">
                                    </div>
                                @endif
                            </div>

                            <!-- Published -->
                            <div class="flex items-center">
                                <input
                                    type="checkbox"
                                    wire:model="is_published"
                                    id="is_published"
                                    class="w-4 h-4 text-pink-vibrant bg-purple-deeper border-pink-vibrant/20 rounded focus:ring-pink-vibrant focus:ring-2">
                                <label for="is_published" class="ml-2 text-cream/70 text-sm">Publicar curso</label>
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
                                {{ $isEditing ? 'Actualizar' : 'Crear' }} Curso
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
