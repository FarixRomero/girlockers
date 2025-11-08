<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-4 md:mb-8 flex items-center justify-between">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Clases</h1>

        <!-- Filter Button (Mobile & Desktop) -->
        <button wire:click="$set('showFilterModal', true)" class="flex items-center px-3 md:px-4 py-2 border border-gray-900 text-gray-900 font-semibold rounded hover:bg-gray-100 transition text-sm md:text-base">
            <svg class="w-4 h-4 md:w-5 md:h-5 mr-1 md:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

        <!-- Active Filters Chips (Mobile & Desktop) -->
        @if($selectedLevel !== 'all' || $selectedTag || $selectedInstructor)
            <div class="flex flex-wrap gap-2 items-center mt-3">
                <span class="text-xs md:text-sm text-gray-600 font-medium">Filtros activos:</span>

                @if($selectedLevel !== 'all')
                    <button
                        wire:click="filterByLevel('all')"
                        class="inline-flex items-center px-3 py-1 text-xs md:text-sm font-medium rounded-full {{ $selectedLevel === 'principiante' ? 'bg-orange-500 text-white' : ($selectedLevel === 'intermedio' ? 'bg-blue-500 text-white' : 'bg-red-600 text-white') }}"
                    >
                        {{ ucfirst($selectedLevel) }}
                        <svg class="w-3 h-3 md:w-4 md:h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                @endif

                @if($selectedTag)
                    <button
                        wire:click="$set('selectedTag', null)"
                        class="inline-flex items-center px-3 py-1 text-xs md:text-sm font-medium rounded-full bg-purple-500 text-white"
                    >
                        {{ $tags->find($selectedTag)?->name ?? 'Tag' }}
                        <svg class="w-3 h-3 md:w-4 md:h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                @endif

                @if($selectedInstructor)
                    <button
                        wire:click="$set('selectedInstructor', null)"
                        class="inline-flex items-center px-3 py-1 text-xs md:text-sm font-medium rounded-full bg-gray-700 text-white"
                    >
                        {{ $instructors->find($selectedInstructor)?->name ?? 'Instructor' }}
                        <svg class="w-3 h-3 md:w-4 md:h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                @endif

                <button
                    wire:click="clearFilters"
                    class="text-xs md:text-sm text-purple-600 hover:text-purple-700 font-medium underline"
                >
                    Limpiar todo
                </button>
            </div>
        @endif
    </div>

    <!-- Lessons Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-6">
        @forelse($lessons as $lesson)
            <div class="group rounded-lg overflow-hidden" style="flex-shrink: 0;">
                <a href="{{ route('lessons.show', $lesson) }}" wire:navigate class="block">
                    <div class="aspect-video relative rounded-lg overflow-hidden">
                        @if($lesson->thumbnail_url)
                            <img src="{{ $lesson->thumbnail_url }}" alt="{{ $lesson->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-purple-500 to-pink-500">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        @endif

                        <!-- Lock icon for premium lessons -->
                        @if(!$lesson->is_trial && !auth()->user()->has_full_access)
                            <div class="absolute top-2 left-2 bg-black/80 rounded-lg p-1.5">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        @endif

                        <!-- Tags en la parte inferior de la imagen -->
                        <div class="absolute bottom-2 left-2 flex flex-wrap gap-1">
                            @php
                                $levelColors = [
                                    'principiante' => 'bg-green-500',
                                    'intermedio' => 'bg-blue-500',
                                    'avanzado' => 'bg-red-500'
                                ];
                                $courseLevel = $lesson->module?->course?->level;
                                $levelColor = isset($courseLevel) ? ($levelColors[$courseLevel] ?? 'bg-gray-500') : 'bg-gray-500';
                            @endphp
                            @if($courseLevel)
                                <span class="px-1.5 py-0.5 {{ $levelColor }} text-white text-[10px] font-bold uppercase rounded">{{ $courseLevel }}</span>
                            @endif
                            @if($lesson->duration_minutes && $lesson->video_type !== 'youtube')
                                <span class="px-1.5 py-0.5 bg-black/80 text-white text-[10px] font-bold uppercase rounded">{{ $lesson->duration_minutes }} MIN</span>
                            @endif
                            @foreach($lesson->tags->take(1) as $tag)
                                <span class="px-1.5 py-0.5 bg-black/80 text-white text-[10px] font-bold uppercase rounded">{{ $tag->name }}</span>
                            @endforeach
                        </div>

                        <!-- Heart icon (top-right) -->
                        <button
                            wire:click.prevent="toggleLike({{ $lesson->id }})"
                            class="absolute top-2 right-2 p-1.5 bg-black/50 hover:bg-black/70 rounded-full transition z-10"
                        >
                            @if($lesson->is_liked)
                                <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path>
                                </svg>
                            @else
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            @endif
                        </button>
                    </div>

                    <!-- Info abajo sin background -->
                    <div class="pt-2">
                        <h3 class="text-gray-900 font-semibold text-sm line-clamp-2 mb-1">{{ $lesson->title }}</h3>
                        @if($lesson->instructor)
                            <p class="text-gray-500 text-xs font-normal">{{ $lesson->instructor->name }}</p>
                        @endif
                    </div>
                </a>
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

    <!-- Infinite Scroll Trigger (Mobile) -->
    @if($lessons->hasMorePages())
        <div
            class="mt-8 md:hidden"
            x-data="{
                observe() {
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                $wire.call('loadMore');
                            }
                        });
                    }, {
                        rootMargin: '100px'
                    });
                    observer.observe(this.$el);
                }
            }"
            x-init="observe()"
        >
            <div class="flex justify-center py-4">
                <div wire:loading wire:target="loadMore" class="flex items-center space-x-2 text-gray-600">
                    <svg class="animate-spin h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-sm font-medium">Cargando más clases...</span>
                </div>
            </div>
        </div>
    @endif

    <!-- Pagination (Desktop) -->
    <div class="mt-8 hidden md:block">
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
