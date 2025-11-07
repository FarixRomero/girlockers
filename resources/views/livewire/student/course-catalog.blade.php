<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-4 md:mb-8 flex items-center justify-between">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Cursos</h1>

        <!-- Filter Button (Mobile Only) -->
        <button wire:click="$set('showFilterModal', true)" class="md:hidden flex items-center px-3 py-2 border border-gray-900 text-gray-900 font-semibold rounded hover:bg-gray-100 transition text-sm">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
            Filtrar
            @php
                $activeFilters = 0;
                if($selectedLevel !== 'all') $activeFilters++;
                if($selectedInstructor) $activeFilters++;
            @endphp
            @if($activeFilters > 0)
                <span class="ml-2 px-2 py-0.5 bg-purple-600 text-white text-xs font-bold rounded-full">{{ $activeFilters }}</span>
            @endif
        </button>
    </div>

    <!-- Tab-style Filters -->
    <div class="mb-4 md:mb-8">
        <!-- Navigation Tabs -->
        <div class="flex gap-2 mb-4 overflow-x-auto pb-2 scrollbar-hide">
            <!-- TODAS LAS CLASES -->
            <a
                href="{{ route('lessons.index', ['nivel' => $selectedLevel, 'instructor' => $selectedInstructor, 'buscar' => $search]) }}"
                wire:navigate
                class="px-4 md:px-6 py-2 font-bold text-xs md:text-sm transition bg-white text-gray-900 border border-gray-300 hover:bg-gray-50 whitespace-nowrap flex-shrink-0"
            >
                TODAS LAS CLASES
            </a>

            <!-- CLASES GRATIS -->
            <a
                href="{{ route('lessons.index', ['gratis' => 'true', 'nivel' => $selectedLevel, 'instructor' => $selectedInstructor, 'buscar' => $search]) }}"
                wire:navigate
                class="px-4 md:px-6 py-2 font-bold text-xs md:text-sm transition bg-white text-gray-900 border border-gray-300 hover:bg-gray-50 whitespace-nowrap flex-shrink-0"
            >
                CLASES GRATIS
            </a>

            <!-- CURSOS -->
            <button
                class="px-4 md:px-6 py-2 font-bold text-xs md:text-sm transition bg-gray-900 text-white whitespace-nowrap flex-shrink-0"
            >
                CURSOS
            </button>
        </div>

        <!-- Search Bar -->
        <div class="mb-3 md:mb-4">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Buscar cursos..."
                class="w-full px-3 md:px-4 py-2 md:py-3 text-sm md:text-base border border-gray-300 rounded focus:ring-2 focus:ring-gray-900 focus:border-transparent"
            >
        </div>

        <!-- Level Filter Pills (Desktop Only) -->
        <div class="hidden md:flex flex-wrap gap-2 items-center">
            <span class="text-sm text-gray-600 font-medium">Nivel:</span>
            <button
                wire:click="filterByLevel('all')"
                class="px-4 py-2 text-sm font-medium rounded transition {{ $selectedLevel === 'all' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
            >
                TODOS
            </button>
            <button
                wire:click="filterByLevel('principiante')"
                class="px-4 py-2 text-sm font-medium rounded transition {{ $selectedLevel === 'principiante' ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
            >
                PRINCIPIANTE
            </button>
            <button
                wire:click="filterByLevel('intermedio')"
                class="px-4 py-2 text-sm font-medium rounded transition {{ $selectedLevel === 'intermedio' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
            >
                INTERMEDIO
            </button>
            <button
                wire:click="filterByLevel('avanzado')"
                class="px-4 py-2 text-sm font-medium rounded transition {{ $selectedLevel === 'avanzado' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
            >
                AVANZADO
            </button>

            @if($selectedLevel !== 'all' || $selectedInstructor)
                <button
                    wire:click="clearFilters"
                    class="ml-2 text-sm text-purple-600 hover:text-purple-700 font-medium underline"
                >
                    Limpiar filtros
                </button>
            @endif
        </div>

        <!-- Active Filters Chips (Mobile Only) -->
        @if($selectedLevel !== 'all' || $selectedInstructor)
            <div class="md:hidden flex flex-wrap gap-2 items-center mt-3">
                <span class="text-xs text-gray-600 font-medium">Filtros activos:</span>

                @if($selectedLevel !== 'all')
                    <button
                        wire:click="filterByLevel('all')"
                        class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full {{ $selectedLevel === 'principiante' ? 'bg-orange-500 text-white' : ($selectedLevel === 'intermedio' ? 'bg-blue-500 text-white' : 'bg-red-600 text-white') }}"
                    >
                        {{ ucfirst($selectedLevel) }}
                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                @endif

                <button
                    wire:click="clearFilters"
                    class="text-xs text-purple-600 hover:text-purple-700 font-medium underline"
                >
                    Limpiar todo
                </button>
            </div>
        @endif
    </div>

    <!-- Courses Grid -->
    @if($courses->isEmpty())
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="mt-4 text-lg text-gray-600">No se encontraron cursos</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($courses as $course)
                <x-course-card :course="$course" />
            @endforeach
        </div>
    @endif

    <!-- Filter Modal -->
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showFilterModal') }" x-show="show" style="display: none;">
        <!-- Overlay -->
        <div
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-900 bg-opacity-75"
            @click="show = false"
        ></div>

        <!-- Modal Panel -->
        <div class="flex min-h-screen items-center justify-center p-4">
            <div
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[80vh] overflow-y-auto"
                @click.away="show = false"
            >
                <!-- Header -->
                <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between z-10">
                    <h2 class="text-2xl font-bold text-gray-900">Filtros</h2>
                    <button @click="show = false" class="p-2 hover:bg-gray-100 rounded-full transition">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Content -->
                <div class="p-6 space-y-6">
                    <!-- Level Filter -->
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-3">FILTRAR POR NIVEL</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button
                                wire:click="filterByLevel('all')"
                                class="px-4 py-2 text-sm font-medium rounded transition {{ $selectedLevel === 'all' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                            >
                                TODOS
                            </button>
                            <button
                                wire:click="filterByLevel('principiante')"
                                class="px-4 py-2 text-sm font-medium rounded transition {{ $selectedLevel === 'principiante' ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                            >
                                PRINCIPIANTE
                            </button>
                            <button
                                wire:click="filterByLevel('intermedio')"
                                class="px-4 py-2 text-sm font-medium rounded transition {{ $selectedLevel === 'intermedio' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                            >
                                INTERMEDIO
                            </button>
                            <button
                                wire:click="filterByLevel('avanzado')"
                                class="px-4 py-2 text-sm font-medium rounded transition {{ $selectedLevel === 'avanzado' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                            >
                                AVANZADO
                            </button>
                        </div>
                    </div>

                    <!-- Instructors Filter -->
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-3">FILTRAR POR INSTRUCTOR</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button
                                wire:click="$set('selectedInstructor', null)"
                                class="px-4 py-2 text-sm font-medium rounded transition {{ !$selectedInstructor ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                            >
                                Todos
                            </button>
                            @foreach($instructors as $instructor)
                                <button
                                    wire:click="$set('selectedInstructor', {{ $instructor->id }})"
                                    class="px-4 py-2 text-sm font-medium rounded transition text-left {{ $selectedInstructor == $instructor->id ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                                >
                                    <div class="truncate">{{ $instructor->name }}</div>
                                    <div class="text-xs opacity-70">{{ $instructor->lessons_count }} clases</div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="sticky bottom-0 bg-gray-50 border-t border-gray-200 px-6 py-4 flex items-center justify-between">
                    <button
                        wire:click="clearFilters"
                        class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition"
                    >
                        Limpiar filtros
                    </button>
                    <button
                        @click="show = false"
                        class="px-6 py-2 bg-gray-900 text-white font-semibold rounded hover:bg-gray-800 transition"
                    >
                        Aplicar filtros
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
