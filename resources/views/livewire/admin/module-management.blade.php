<div class="min-h-screen bg-gray-50 pb-20 lg:pb-8">
    <x-slot name="header">
        Módulos de {{ $course->title }}
    </x-slot>

    <!-- Header Section -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-3xl mx-auto px-4 py-4">
            <a href="{{ route('admin.courses.index') }}" wire:navigate class="inline-flex items-center text-purple-500 hover:text-purple-600 text-sm font-medium mb-3">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Volver
            </a>
            <h1 class="font-bold text-xl md:text-2xl text-gray-900 mb-1">{{ $course->title }}</h1>
            <p class="text-gray-600 text-sm">{{ ucfirst($course->level) }} • {{ $course->modules->count() }} {{ $course->modules->count() === 1 ? 'módulo' : 'módulos' }}</p>
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

        <!-- Course Description -->
        <div class="bg-white rounded-2xl p-4 mb-4">
            <p class="text-gray-600 text-sm leading-relaxed">{{ $course->description }}</p>
        </div>

        <!-- Modules List -->
        <div class="space-y-3">
        @forelse($course->modules as $module)
            <div class="bg-white rounded-2xl p-4 relative group" wire:key="module-{{ $module->id }}">
                <div class="flex items-center gap-3 relative z-10">
                    <!-- Order Badge -->
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                        {{ $module->order }}
                    </div>

                    <!-- Module Info (Clickable to lessons) -->
                    <a href="{{ route('admin.modules.lessons', $module->id) }}" wire:navigate class="flex-1 min-w-0">
                        <h3 class="font-semibold text-base text-gray-900">
                            {{ $module->title }}
                        </h3>
                        <p class="text-sm text-gray-500">({{ $module->lessons_count }} {{ $module->lessons_count === 1 ? 'lección' : 'lecciones' }})</p>
                    </a>

                    <!-- Actions -->
                    <div class="flex items-center gap-0.5 md:gap-1">
                        <!-- Edit -->
                        <button
                            wire:click="openEditModal({{ $module->id }})"
                            class="p-2 text-purple-500 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>

                        <!-- Delete -->
                        <button
                            wire:click="deleteModule({{ $module->id }})"
                            wire:confirm="¿Estás seguro de eliminar el módulo '{{ $module->title }}'?"
                            class="p-2 text-red-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl text-center py-12">
                <p class="text-gray-500 text-sm mb-4">No hay módulos aún</p>
                <button wire:click="openCreateModal" class="inline-flex items-center px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg text-sm font-medium transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Crear Primer Módulo
                </button>
            </div>
        @endforelse
        </div>

        <!-- Nuevo Módulo Button (Fixed Bottom) -->
        <div class="fixed bottom-4 left-0 right-0 px-4 lg:hidden">
            <div class="max-w-3xl mx-auto">
                <button wire:click="openCreateModal" class="w-full flex items-center justify-center px-6 py-3 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-2xl shadow-lg font-semibold">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nuevo Módulo
                </button>
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showModal') }">
            <div class="flex items-end md:items-center justify-center min-h-screen px-0 md:px-4 pt-4 pb-0 md:pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 transition-opacity bg-black/50 backdrop-blur-sm" wire:click="closeModal"></div>

                <!-- Modal panel -->
                <div class="inline-block overflow-hidden text-left align-bottom md:align-middle transition-all transform bg-white rounded-t-2xl md:rounded-2xl shadow-2xl sm:my-8 w-full sm:max-w-lg">
                    <form wire:submit="saveModule">
                        <!-- Header -->
                        <div class="px-4 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $isEditing ? 'Editar Módulo' : 'Nuevo Módulo' }}
                                </h3>
                                <button type="button" wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="px-4 py-4 space-y-4">
                            <!-- Title -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Título</label>
                                <input
                                    type="text"
                                    wire:model="title"
                                    class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    placeholder="Ej: Introducción al Hip-Hop">
                                @error('title') <p class="mt-1 text-red-500 text-xs">{{ $message }}</p> @enderror
                            </div>

                            <!-- Order -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Orden</label>
                                <input
                                    type="number"
                                    wire:model="order"
                                    min="1"
                                    class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    placeholder="1">
                                @error('order') <p class="mt-1 text-red-500 text-xs">{{ $message }}</p> @enderror
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
                                {{ $isEditing ? 'Guardar Cambios' : 'Crear Módulo' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
