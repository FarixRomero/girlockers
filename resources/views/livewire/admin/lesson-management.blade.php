<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-3xl mx-auto px-4 py-4">
            <a href="{{ route('admin.courses.modules', $module->course_id) }}"
               class="inline-flex items-center text-purple-500 hover:text-purple-600 text-sm font-medium mb-3">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Volver
            </a>
            <h1 class="font-bold text-xl md:text-2xl text-gray-900 mb-1">{{ $module->title }}</h1>
            <p class="text-gray-600 text-sm">
                {{ $courseTitle }} • {{ $lessonsCount }} lecciones
            </p>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 py-6 pb-24">
        <!-- Alert Messages -->
        @if (session()->has('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Lessons List -->
        <div class="space-y-3">
            @forelse($module->lessons as $index => $lesson)
                <div wire:key="lesson-{{ $lesson->id }}" class="bg-white rounded-2xl p-4 relative group">
                    <div class="flex items-center gap-3 relative z-10">
                        <!-- Lesson Info (Clickable to edit) -->
                        <a href="{{ route('admin.lessons.edit', $lesson->id) }}" class="flex-1 min-w-0 flex items-center gap-3">
                            <!-- Thumbnail -->
                            <div class="w-20 h-14 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                @if($lesson->thumbnail)
                                    <img src="{{ $lesson->thumbnail_url }}"
                                         alt="{{ $lesson->title }}"
                                         class="w-full h-full object-cover"
                                         onerror="this.style.display='none';this.parentElement.style.backgroundColor='#e5e7eb';this.parentElement.innerHTML='<div class=\'flex items-center justify-center h-full text-gray-400 text-xs\'>Sin Imagen</div>'">
                                @else
                                    <div class="flex items-center justify-center h-full text-gray-400 text-xs">
                                        Sin Imagen
                                    </div>
                                @endif
                            </div>

                            <!-- Title & Info -->
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-base text-gray-900 line-clamp-1">
                                    {{ $lesson->title }}
                                </h3>
                                <p class="text-sm text-gray-500 line-clamp-1">
                                    {{ $lesson->is_trial ? 'Gratis' : 'Premium' }}
                                    @if($lesson->duration_minutes > 0)
                                        • {{ $lesson->duration_minutes }} min
                                    @endif
                                </p>
                            </div>
                        </a>

                        <!-- Actions -->
                        <div class="flex items-center gap-0.5 md:gap-1">
                            <!-- Move Up -->
                            @if($lesson->order > 1)
                                <button wire:click="moveUp({{ $lesson->id }})"
                                        class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                </button>
                            @endif

                            <!-- Move Down -->
                            @if($lesson->order < $module->lessons->count())
                                <button wire:click="moveDown({{ $lesson->id }})"
                                        class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            @endif

                            <!-- Toggle Trial -->
                            <button wire:click="toggleTrial({{ $lesson->id }})"
                                    class="p-2 text-{{ $lesson->is_trial ? 'green' : 'orange' }}-500 hover:bg-gray-50 rounded-lg transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </button>

                            <!-- Delete -->
                            <button wire:click="$dispatch('confirmDelete', { lessonId: {{ $lesson->id }}, lessonTitle: '{{ addslashes($lesson->title) }}' })"
                                    class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl text-center py-12">
                    <p class="text-gray-500 text-sm mb-4">No hay lecciones aún</p>
                    <a href="{{ route('admin.lessons.create', $module->id) }}"
                       class="inline-flex items-center px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg text-sm font-medium transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Crear primera lección
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Nueva Lección Button (Fixed Bottom) -->
        <div class="fixed bottom-4 left-0 right-0 px-4 z-40">
            <div class="max-w-3xl mx-auto">
                <a href="{{ route('admin.lessons.create', $module->id) }}"
                   class="group relative w-full flex items-center justify-center px-6 py-3 md:py-4 bg-purple-600 md:bg-gradient-to-r md:from-purple-600 md:to-purple-500 hover:bg-purple-700 md:hover:from-purple-700 md:hover:to-purple-600 text-white rounded-2xl shadow-lg md:shadow-xl md:hover:shadow-2xl font-semibold md:font-bold transition-all md:duration-300 md:transform md:hover:scale-[1.02] md:active:scale-[0.98]">
                    <!-- Glow effect on hover (desktop only) -->
                    <div class="hidden md:block absolute inset-0 rounded-2xl bg-gradient-to-r from-purple-400 to-purple-300 opacity-0 group-hover:opacity-20 blur-xl transition-opacity duration-300"></div>

                    <!-- Icon -->
                    <svg class="relative w-5 h-5 md:w-6 md:h-6 mr-2 md:transition-transform md:duration-300 md:group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>

                    <!-- Text -->
                    <span class="relative">Nueva Lección</span>
                </a>
            </div>
        </div>
    </div>
</div>

@script
<script>
    // Delete confirmation with Alpine.js or SweetAlert
    $wire.on('confirmDelete', (event) => {
        if (confirm(`¿Estás seguro de eliminar la lección "${event.lessonTitle}"?\n\nEsta acción no se puede deshacer.`)) {
            $wire.dispatch('confirmDelete', { lessonId: event.lessonId });
        }
    });
</script>
@endscript
