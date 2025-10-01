<div>
    <x-slot name="header">
        Catálogo de Cursos
    </x-slot>

    <div class="max-w-7xl mx-auto">
        <!-- Filter Tabs -->
        <div class="mb-8 flex flex-wrap gap-3">
            <button
                wire:click="filterByLevel('all')"
                class="px-6 py-2 rounded-full font-medium transition {{ $selectedLevel === 'all' ? 'bg-pink-vibrant text-cream' : 'bg-purple-darker text-cream/70 hover:bg-purple-deep' }}">
                Todos
            </button>
            <button
                wire:click="filterByLevel('beginner')"
                class="px-6 py-2 rounded-full font-medium transition {{ $selectedLevel === 'beginner' ? 'bg-pink-vibrant text-cream' : 'bg-purple-darker text-cream/70 hover:bg-purple-deep' }}">
                🌱 Principiante
            </button>
            <button
                wire:click="filterByLevel('intermediate')"
                class="px-6 py-2 rounded-full font-medium transition {{ $selectedLevel === 'intermediate' ? 'bg-pink-vibrant text-cream' : 'bg-purple-darker text-cream/70 hover:bg-purple-deep' }}">
                🔥 Intermedio
            </button>
            <button
                wire:click="filterByLevel('advanced')"
                class="px-6 py-2 rounded-full font-medium transition {{ $selectedLevel === 'advanced' ? 'bg-pink-vibrant text-cream' : 'bg-purple-darker text-cream/70 hover:bg-purple-deep' }}">
                💎 Avanzado
            </button>
        </div>

        <!-- Courses Grid -->
        @if($courses->isEmpty())
            <div class="card-premium text-center py-12">
                <div class="text-6xl mb-4">🎯</div>
                <h3 class="font-display text-2xl text-cream mb-3">No hay cursos disponibles</h3>
                <p class="text-cream/70">Pronto agregaremos más contenido para este nivel.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" wire:loading.class="opacity-50">
                @foreach($courses as $course)
                    <x-course-card :course="$course" />
                @endforeach
            </div>

            <!-- Loading Indicator -->
            <div wire:loading class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50">
                <div class="card-glass px-6 py-4 flex items-center space-x-3">
                    <svg class="animate-spin h-5 w-5 text-pink-vibrant" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-cream">Cargando...</span>
                </div>
            </div>
        @endif
    </div>
</div>
