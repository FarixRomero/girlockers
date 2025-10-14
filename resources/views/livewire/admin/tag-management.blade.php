<div>
    <x-slot name="header">
        Gestión de Tags
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
                <div class="text-gray-dark text-sm mb-1 font-medium">Total Tags</div>
                <div class="text-3xl font-black text-black">{{ $stats['total'] }}</div>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-gray-light/50 shadow-sm hover:shadow-md transition-all duration-300">
                <div class="text-gray-dark text-sm mb-1 font-medium">En Uso</div>
                <div class="text-3xl font-black text-purple-primary">{{ $stats['with_lessons'] }}</div>
            </div>
        </div>

        <!-- Filters & Actions -->
        <div class="bg-gray-ultralight rounded-2xl p-6 border border-gray-light/50 shadow-sm mb-6">
            <div class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-gray-dark text-sm mb-2 font-medium">Buscar</label>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Buscar tags..."
                        class="w-full bg-white border border-purple-primary/20 rounded-lg px-4 py-3 text-black placeholder-gray-medium focus:outline-none focus:border-purple-primary focus:ring-2 focus:ring-purple-primary/10 transition shadow-sm">
                </div>
                <button wire:click="openCreateModal" class="px-6 py-3 bg-purple-primary hover:bg-purple-dark text-white font-bold rounded-lg shadow-sm hover:shadow-md transition-all duration-300 whitespace-nowrap">
                    <svg class="w-5 h-5 inline-block mr-2 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nuevo Tag
                </button>
            </div>
        </div>

        <!-- Tags Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($tags as $tag)
                <div class="bg-white rounded-lg border border-gray-light shadow-sm hover:shadow-md transition-all p-4" wire:key="tag-{{ $tag->id }}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="font-bold text-black">{{ $tag->name }}</h3>
                            <div class="mt-2">
                                <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded-full font-bold">
                                    {{ $tag->lessons_count }} lecciones
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-1 ml-2">
                            <button
                                wire:click="openEditModal({{ $tag->id }})"
                                class="p-2 text-purple-primary hover:bg-purple-ultralight rounded-lg transition"
                                title="Editar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button
                                wire:click="deleteTag({{ $tag->id }})"
                                wire:confirm="¿Estás seguro de eliminar el tag '{{ $tag->name }}'?"
                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                title="Eliminar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16">
                    <svg class="w-20 h-20 text-gray-light mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <p class="text-gray-dark font-medium">No se encontraron tags</p>
                    <p class="text-gray-medium text-sm mt-2">Comienza agregando tu primer tag</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($tags->hasPages())
            <div class="mt-6">
                {{ $tags->links() }}
            </div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showModal') }">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-black/70 backdrop-blur-sm" wire:click="closeModal"></div>
                <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit="saveTag">
                        <div class="px-8 py-6 border-b border-gray-light bg-gradient-to-r from-purple-primary to-purple-light">
                            <h3 class="text-2xl font-display font-bold text-white">
                                {{ $isEditing ? 'Editar Tag' : 'Crear Nuevo Tag' }}
                            </h3>
                        </div>
                        <div class="px-8 py-6 space-y-5 bg-gray-ultralight/30">
                            <div>
                                <label class="block text-gray-dark text-sm mb-2 font-bold">Nombre *</label>
                                <input
                                    type="text"
                                    wire:model.live.debounce.300ms="name"
                                    class="w-full"
                                    placeholder="Ej: Coreografía">
                                @error('name') <p class="mt-1 text-red-600 text-sm font-medium">{{ $message }}</p> @enderror
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
                                {{ $isEditing ? 'Actualizar' : 'Crear' }} Tag
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
