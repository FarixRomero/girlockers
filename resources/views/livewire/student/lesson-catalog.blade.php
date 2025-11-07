<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-4 md:mb-8 flex items-center justify-between">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Clases</h1>

        <!-- Filter Button (Mobile Only) -->
        <button wire:click="$set('showFilterModal', true)" class="md:hidden flex items-center px-3 py-2 border border-gray-900 text-gray-900 font-semibold rounded hover:bg-gray-100 transition text-sm">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
            Filtrar
            @php
                $activeFilters = 0;
                if($selectedLevel !== 'all') $activeFilters++;
                if($selectedTag) $activeFilters++;
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
            <button
                wire:click="$set('onlyFree', false)"
                class="px-4 md:px-6 py-2 font-bold text-xs md:text-sm transition {{ !$onlyFree ? 'bg-gray-900 text-white' : 'bg-white text-gray-900 border border-gray-300 hover:bg-gray-50' }} whitespace-nowrap flex-shrink-0"
            >
                TODAS LAS CLASES
            </button>

            <!-- CLASES GRATIS -->
            <button
                wire:click="$set('onlyFree', true)"
                class="px-4 md:px-6 py-2 font-bold text-xs md:text-sm transition {{ $onlyFree ? 'bg-gray-900 text-white' : 'bg-white text-gray-900 border border-gray-300 hover:bg-gray-50' }} whitespace-nowrap flex-shrink-0"
            >
                CLASES GRATIS
            </button>

            <!-- CURSOS -->
            <a
                href="{{ route('courses.index', ['nivel' => $selectedLevel, 'instructor' => $selectedInstructor, 'buscar' => $search]) }}"
                wire:navigate
                class="px-4 md:px-6 py-2 font-bold text-xs md:text-sm transition bg-white text-gray-900 border border-gray-300 hover:bg-gray-50 whitespace-nowrap flex-shrink-0"
            >
                CURSOS
            </a>
        </div>

        <!-- Search Bar -->
        <div class="mb-3 md:mb-4">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Buscar clases..."
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

            @if($selectedLevel !== 'all' || $selectedTag || $selectedInstructor)
                <button
                    wire:click="clearFilters"
                    class="ml-2 text-sm text-purple-600 hover:text-purple-700 font-medium underline"
                >
                    Limpiar filtros
                </button>
            @endif
        </div>

        <!-- Active Filters Chips (Mobile Only) -->
        @if($selectedLevel !== 'all' || $selectedTag || $selectedInstructor)
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

                @if($selectedTag)
                    <button
                        wire:click="$set('selectedTag', null)"
                        class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-purple-500 text-white"
                    >
                        {{ $tags->find($selectedTag)?->name ?? 'Tag' }}
                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                @endif

                @if($selectedInstructor)
                    <button
                        wire:click="$set('selectedInstructor', null)"
                        class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-gray-700 text-white"
                    >
                        {{ $instructors->find($selectedInstructor)?->name ?? 'Instructor' }}
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

    <!-- Lessons Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($lessons as $lesson)
            <div class="group bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition relative">
                <!-- Thumbnail -->
                <a href="{{ route('lessons.show', $lesson) }}" wire:navigate class="block relative">
                    <div class="aspect-video bg-gradient-to-br from-purple-500 to-pink-500 relative">
                        @if($lesson->thumbnail_url)
                            <img src="{{ $lesson->thumbnail_url }}" alt="{{ $lesson->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        @endif

                        <!-- Lock icon for premium lessons -->
                        @if(!$lesson->is_trial && !auth()->user()->has_full_access)
                            <div class="absolute top-3 left-3 bg-black/80 rounded-lg p-2 z-10">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        @endif

                        <!-- Title Overlay (bottom) -->
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                            <h3 class="text-white font-bold text-lg line-clamp-2">{{ $lesson->title }}</h3>
                        </div>
                    </div>
                </a>

                <!-- Content -->
                <div class="p-4">
                    <!-- Tags Row -->
                    <div class="flex flex-wrap gap-2 mb-3">
                        <!-- Course Level Badge -->
                        @php
                            $levelColors = [
                                'principiante' => 'bg-orange-500',
                                'intermedio' => 'bg-blue-500',
                                'avanzado' => 'bg-red-600'
                            ];
                            $levelColor = $levelColors[$lesson->module->course->level] ?? 'bg-gray-500';
                        @endphp
                        <span class="px-2 py-1 {{ $levelColor }} text-white text-xs font-bold uppercase rounded">
                            {{ $lesson->module->course->level }}
                        </span>

                        <!-- Duration Badge (if available) -->
                        @if($lesson->duration)
                            <span class="px-2 py-1 bg-gray-900 text-white text-xs font-bold uppercase rounded">
                                {{ $lesson->duration }} MIN
                            </span>
                        @endif

                        <!-- Tags -->
                        @foreach($lesson->tags as $tag)
                            <span class="px-2 py-1 bg-gray-900 text-white text-xs font-bold uppercase rounded">
                                {{ $tag->name }}
                            </span>
                        @endforeach
                    </div>

                    <!-- Instructor & Course Info -->
                    <div class="text-sm text-gray-600 mb-2">
                        <span class="font-medium">{{ $lesson->module->course->title }}</span>
                        @if($lesson->instructor)
                            <span class="text-gray-400"> • </span>
                            <span>{{ $lesson->instructor->name }}</span>
                        @endif
                    </div>

                    <!-- Free Class Badge (if applicable) -->
                    @if($lesson->is_trial)
                        <div class="flex items-center text-sm">
                            <span class="text-green-600 font-semibold flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Clase gratis
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Three-dot menu (top-right) -->
                <div class="absolute top-4 right-4 z-10" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" class="p-2 bg-white/90 hover:bg-white rounded-full shadow-md transition">
                        <svg class="w-5 h-5 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div
                        x-show="open"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 py-1"
                        style="display: none;"
                    >
                        <!-- Save/Unsave Lesson -->
                        <button
                            wire:click="toggleLike({{ $lesson->id }})"
                            @click="open = false"
                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition flex items-center"
                        >
                            @if($lesson->is_liked)
                                <svg class="w-5 h-5 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path>
                                </svg>
                                <span>Quitar de guardados</span>
                            @else
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <span>Guardar clase</span>
                            @endif
                        </button>

                        @if($lesson->instructor)
                            <!-- View Instructor -->
                            <a
                                href="{{ route('instructors.index') }}"
                                wire:navigate
                                @click="open = false"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition flex items-center"
                            >
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span>Ver instructores</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="mt-4 text-lg text-gray-600">No se encontraron clases</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $lessons->links() }}
    </div>

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

                        <!-- Tags Filter -->
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-3">FILTRAR POR CATEGORÍA / TAG</label>
                            <div class="grid grid-cols-2 gap-3">
                                <button
                                    wire:click="$set('selectedTag', null)"
                                    class="px-4 py-2 text-sm font-medium rounded transition {{ !$selectedTag ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                                >
                                    Todos
                                </button>
                                @foreach($tags as $tag)
                                    <button
                                        wire:click="$set('selectedTag', {{ $tag->id }})"
                                        class="px-4 py-2 text-sm font-medium rounded transition {{ $selectedTag == $tag->id ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                                    >
                                        {{ $tag->name }}
                                    </button>
                                @endforeach
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
</div>
