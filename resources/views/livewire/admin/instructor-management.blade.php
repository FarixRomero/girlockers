<div class="pb-20 lg:pb-0">
    <x-slot name="header">
        Gestión de Instructores
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 bg-white">
        <!-- Success/Error Messages -->
        @if(session()->has('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg shadow-sm">
                <p class="text-green-700 flex items-center font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ session('success') }}
                </p>
            </div>
        @endif

        @if(session()->has('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg shadow-sm">
                <p class="text-red-700 flex items-center font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    {{ session('error') }}
                </p>
            </div>
        @endif

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-2xl p-6 border border-gray-light/50 shadow-sm hover:shadow-md transition-all duration-300">
                <div class="text-gray-dark text-sm mb-1 font-medium">Total Instructores</div>
                <div class="text-3xl font-black text-black">{{ $stats['total'] }}</div>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-gray-light/50 shadow-sm hover:shadow-md transition-all duration-300">
                <div class="text-gray-dark text-sm mb-1 font-medium">Con Lecciones</div>
                <div class="text-3xl font-black text-purple-primary">{{ $stats['with_lessons'] }}</div>
            </div>
        </div>

        <!-- Filters & Actions -->
        <div class="bg-gray-ultralight rounded-2xl p-6 border border-gray-light/50 shadow-sm mb-6">
            <div class="flex flex-col md:flex-row gap-4 items-end">
                <!-- Search -->
                <div class="flex-1">
                    <label class="block text-gray-dark text-sm mb-2 font-medium">Buscar</label>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Buscar instructores..."
                        class="w-full bg-white border border-purple-primary/20 rounded-lg px-4 py-3 text-black placeholder-gray-medium focus:outline-none focus:border-purple-primary focus:ring-2 focus:ring-purple-primary/10 transition shadow-sm">
                </div>

                <!-- Create Button -->
                <button wire:click="openCreateModal" class="px-6 py-3 bg-purple-primary hover:bg-purple-dark text-white font-bold rounded-lg shadow-sm hover:shadow-md transition-all duration-300 whitespace-nowrap">
                    <svg class="w-5 h-5 inline-block mr-2 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nuevo Instructor
                </button>
            </div>
        </div>

        <!-- Instructors List -->
        <!-- Mobile View (Cards) -->
        <div class="md:hidden space-y-3">
            @forelse($instructors as $instructor)
                <div wire:key="instructor-mobile-{{ $instructor->id }}"
                     class="bg-white rounded-2xl p-4 border border-gray-light/50 shadow-sm">
                    <!-- Header with Avatar & Actions -->
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            @if($instructor->avatar)
                                <img src="{{ asset('storage/' . $instructor->avatar) }}"
                                     alt="{{ $instructor->name }}"
                                     class="w-14 h-14 object-cover rounded-full shadow-sm flex-shrink-0">
                            @else
                                <div class="w-14 h-14 bg-gradient-to-br from-purple-primary to-purple-light rounded-full flex items-center justify-center shadow-sm flex-shrink-0">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            @endif

                            <div class="flex-1 min-w-0">
                                <h3 class="text-black font-bold text-base truncate">{{ $instructor->name }}</h3>
                                @if($instructor->description)
                                    <p class="text-gray-dark text-sm line-clamp-1">{{ $instructor->description }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-1 flex-shrink-0">
                            <button wire:click="openEditModal({{ $instructor->id }})"
                                    class="p-2 text-purple-primary hover:bg-purple-ultralight rounded-lg transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button wire:click="deleteInstructor({{ $instructor->id }})"
                                    wire:confirm="¿Estás seguro de eliminar el instructor '{{ $instructor->name }}'?"
                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Info Grid -->
                    <div class="grid grid-cols-3 gap-3 pt-3 border-t border-gray-light/50">
                        <!-- Instagram -->
                        <div class="text-center">
                            <div class="text-xs text-gray-medium mb-1">Instagram</div>
                            @if($instructor->instagram)
                                <a href="https://instagram.com/{{ ltrim($instructor->instagram, '@') }}"
                                   target="_blank"
                                   class="text-purple-primary text-xs font-medium hover:underline inline-flex items-center justify-center">
                                    <svg class="w-3 h-3 mr-0.5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                    </svg>
                                    <span class="truncate max-w-[60px]">{{ Str::limit($instructor->instagram, 10) }}</span>
                                </a>
                            @else
                                <span class="text-gray-medium text-xs">-</span>
                            @endif
                        </div>

                        <!-- Lecciones -->
                        <div class="text-center">
                            <div class="text-xs text-gray-medium mb-1">Lecciones</div>
                            <div class="text-sm font-bold text-purple-primary">{{ $instructor->lessons_count }}</div>
                        </div>

                        <!-- Likes -->
                        <div class="text-center">
                            <div class="text-xs text-gray-medium mb-1">Likes</div>
                            <div class="text-sm font-bold text-pink-500 inline-flex items-center justify-center">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $instructor->likes_count }}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl text-center py-12">
                    <svg class="w-16 h-16 text-gray-light mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <p class="text-gray-dark font-medium">No se encontraron instructores</p>
                    <p class="text-gray-medium text-sm mt-2">Comienza agregando tu primer instructor</p>
                </div>
            @endforelse
        </div>

        <!-- Desktop View (Table) -->
        <div class="hidden md:block bg-white rounded-2xl border border-gray-light/50 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-light bg-gray-ultralight">
                            <th class="text-left py-4 px-6 text-gray-dark font-bold text-sm uppercase tracking-wide">Instructor</th>
                            <th class="text-left py-4 px-6 text-gray-dark font-bold text-sm uppercase tracking-wide">Instagram</th>
                            <th class="text-left py-4 px-6 text-gray-dark font-bold text-sm uppercase tracking-wide">Lecciones</th>
                            <th class="text-left py-4 px-6 text-gray-dark font-bold text-sm uppercase tracking-wide">Likes</th>
                            <th class="text-right py-4 px-6 text-gray-dark font-bold text-sm uppercase tracking-wide">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($instructors as $instructor)
                            <tr class="border-b border-gray-light/30 hover:bg-purple-ultralight/30 transition-colors" wire:key="instructor-{{ $instructor->id }}">
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        @if($instructor->avatar)
                                            <img src="{{ asset('storage/' . $instructor->avatar) }}" alt="{{ $instructor->name }}" class="w-12 h-12 object-cover rounded-full mr-4 shadow-sm">
                                        @else
                                            <div class="w-12 h-12 bg-gradient-to-br from-purple-primary to-purple-light rounded-full flex items-center justify-center mr-4 shadow-sm">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-black font-bold">{{ $instructor->name }}</p>
                                            <p class="text-gray-dark text-sm">{{ Str::limit($instructor->description, 60) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    @if($instructor->instagram)
                                        <a href="https://instagram.com/{{ ltrim($instructor->instagram, '@') }}" target="_blank" class="text-purple-primary hover:underline flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                            </svg>
                                            {{$instructor->instagram}}
                                        </a>
                                    @else
                                        <span class="text-gray-medium text-sm">-</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs rounded-full font-bold border border-purple-200 shadow-sm">
                                        {{ $instructor->lessons_count }} lecciones
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center text-gray-dark">
                                        <svg class="w-4 h-4 mr-1 text-pink-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $instructor->likes_count }}
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button
                                            wire:click="openEditModal({{ $instructor->id }})"
                                            class="p-2 text-purple-primary hover:bg-purple-ultralight rounded-lg transition"
                                            title="Editar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>

                                        <button
                                            wire:click="deleteInstructor({{ $instructor->id }})"
                                            wire:confirm="¿Estás seguro de eliminar el instructor '{{ $instructor->name }}'?"
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
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
                                <td colspan="5" class="py-16 text-center">
                                    <svg class="w-20 h-20 text-gray-light mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <p class="text-gray-dark font-medium">No se encontraron instructores</p>
                                    <p class="text-gray-medium text-sm mt-2">Comienza agregando tu primer instructor</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($instructors->hasPages())
            <div class="mt-6">
                {{ $instructors->links() }}
            </div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showModal') }">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 transition-opacity bg-black/70 backdrop-blur-sm" wire:click="closeModal"></div>

                <!-- Modal panel -->
                <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <form wire:submit="saveInstructor">
                        <div class="px-8 py-6 border-b border-gray-light bg-gradient-to-r from-purple-primary to-purple-light">
                            <h3 class="text-2xl font-display font-bold text-white">
                                {{ $isEditing ? 'Editar Instructor' : 'Crear Nuevo Instructor' }}
                            </h3>
                        </div>

                        <div class="px-8 py-6 space-y-5 bg-gray-ultralight/30">
                            <!-- Name -->
                            <div>
                                <label class="block text-gray-dark text-sm mb-2 font-bold">Nombre *</label>
                                <input
                                    type="text"
                                    wire:model="name"
                                    class="w-full"
                                    placeholder="Ej: María González">
                                @error('name') <p class="mt-1 text-red-600 text-sm font-medium">{{ $message }}</p> @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-gray-dark text-sm mb-2 font-bold">Descripción</label>
                                <textarea
                                    wire:model="description"
                                    rows="4"
                                    class="w-full resize-none"
                                    placeholder="Describe la experiencia y especialidad del instructor..."></textarea>
                                @error('description') <p class="mt-1 text-red-600 text-sm font-medium">{{ $message }}</p> @enderror
                            </div>

                            <!-- Instagram -->
                            <div>
                                <label class="block text-gray-dark text-sm mb-2 font-bold">Instagram</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-3 text-gray-medium">@</span>
                                    <input
                                        type="text"
                                        wire:model="instagram"
                                        class="w-full pl-8"
                                        placeholder="usuario_instagram">
                                </div>
                                @error('instagram') <p class="mt-1 text-red-600 text-sm font-medium">{{ $message }}</p> @enderror
                            </div>

                            <!-- Avatar -->
                            <div>
                                <label class="block text-gray-dark text-sm mb-2 font-bold">Foto del Instructor</label>
                                <input
                                    type="file"
                                    wire:model="avatar"
                                    accept="image/*"
                                    class="w-full file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-purple-primary file:text-white hover:file:bg-purple-dark file:cursor-pointer file:transition">
                                @error('avatar') <p class="mt-1 text-red-600 text-sm font-medium">{{ $message }}</p> @enderror

                                @if ($avatar)
                                    <div class="mt-3 bg-white p-3 rounded-lg border border-gray-light">
                                        <p class="text-gray-dark text-sm mb-2 font-medium">Vista previa:</p>
                                        <img src="{{ $avatar->temporaryUrl() }}" class="w-24 h-24 object-cover rounded-full shadow-sm">
                                    </div>
                                @elseif ($existingAvatar)
                                    <div class="mt-3 bg-white p-3 rounded-lg border border-gray-light">
                                        <p class="text-gray-dark text-sm mb-2 font-medium">Foto actual:</p>
                                        <img src="{{ asset('storage/' . $existingAvatar) }}" class="w-24 h-24 object-cover rounded-full shadow-sm">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="px-8 py-6 border-t border-gray-light bg-white flex justify-end space-x-3">
                            <button
                                type="button"
                                wire:click="closeModal"
                                class="px-6 py-3 bg-gray-ultralight text-gray-dark font-medium rounded-lg hover:bg-gray-light transition-colors">
                                Cancelar
                            </button>
                            <button type="submit" class="px-6 py-3 bg-purple-primary hover:bg-purple-dark text-white font-bold rounded-lg shadow-sm hover:shadow transition-all">
                                {{ $isEditing ? 'Actualizar' : 'Crear' }} Instructor
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
