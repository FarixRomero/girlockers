<div>
    @php
        $levelColors = [
            'principiante' => 'bg-orange-500 text-white',
            'intermedio' => 'bg-blue-500 text-white',
            'avanzado' => 'bg-red-600 text-white',
        ];
        $levelColor = $levelColors[strtolower($course->level)] ?? 'bg-gray-500 text-white';
        $totalLessons = $course->modules->sum(fn($module) => $module->lessons->count());
        $trialLessons = $course->modules->flatMap->lessons->where('is_trial', true)->count();
    @endphp

    <!-- Mobile Hero Section -->
    <div class="lg:hidden relative">
        <!-- Background Image -->
        <div class="relative h-96 overflow-hidden">
            @if($course->image)
                <img src="{{ $course->image_url }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-gradient-to-br from-purple-600 via-purple-500 to-pink-500"></div>
            @endif

            <!-- Overlay -->
            <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-transparent to-black/80"></div>

            <!-- Top Bar -->
            <div class="absolute top-0 left-0 right-0 flex items-center justify-between p-4 z-10">
                <a href="{{ route('courses.index') }}" wire:navigate class="w-10 h-10 rounded-full bg-black/50 backdrop-blur-sm flex items-center justify-center text-white hover:bg-black/70 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1.5 bg-white/20 backdrop-blur-sm text-white text-xs font-bold uppercase rounded border border-white/30">
                        {{ $course->level }}
                    </span>
                    <button wire:click="toggleFavorite" class="w-10 h-10 rounded-full bg-black/50 backdrop-blur-sm flex items-center justify-center text-white hover:bg-black/70 transition">
                        @if($isFavorited)
                            <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                        @else
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        @endif
                    </button>
                </div>
            </div>

            <!-- Course Title -->
            <div class="absolute bottom-0 left-0 right-0 p-6">
                <p class="text-white/80 text-sm font-medium mb-2 uppercase tracking-wide">{{ $course->level }}</p>
                <h1 class="text-white text-4xl font-black uppercase tracking-tight leading-tight">
                    {{ $course->title }}
                </h1>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-gray-900 px-4 py-3">
            <div class="flex gap-2">
                <button
                    wire:click="$set('activeTab', 'overview')"
                    class="flex-1 py-3 rounded-lg font-bold text-sm transition {{ $activeTab === 'overview' ? 'bg-white text-gray-900' : 'bg-gray-800 text-white hover:bg-gray-700' }}">
                    Resumen
                </button>
                <button
                    wire:click="$set('activeTab', 'classes')"
                    class="flex-1 py-3 rounded-lg font-bold text-sm transition {{ $activeTab === 'classes' ? 'bg-white text-gray-900' : 'bg-gray-800 text-white hover:bg-gray-700' }}">
                    Clases
                </button>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white min-h-screen pb-16">
            @if($activeTab === 'overview')
                <!-- Overview Tab -->
                <div class="p-6 space-y-6">
                    <!-- Progress Section -->
                    <div class="bg-gray-900 rounded-lg p-6 space-y-4">
                        <div class="h-2 bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full bg-green-500 transition-all duration-500" style="width: {{ $completionPercentage }}%"></div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-green-500/20 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <p class="text-white text-xl font-bold">{{ $completionPercentage }}% Completado</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-green-500/20 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path>
                                    </svg>
                                </div>
                                <p class="text-white text-lg font-semibold">{{ $completedLessons }} de {{ $totalLessons }} Clases</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-green-500/20 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <p class="text-white text-lg font-semibold">{{ $minutesSpent }} Minutos Practicando</p>
                            </div>
                        </div>
                    </div>

                    <!-- Course Info -->
                    <div>
                        <h2 class="text-gray-900 text-xl font-bold mb-3">Acerca de este Curso</h2>
                        <p class="text-gray-700 leading-relaxed">{{ $course->description }}</p>
                    </div>

                    <!-- Instructor Info -->
                    @if($course->instructor)
                        <div class="bg-gray-100 rounded-lg p-5">
                            <h2 class="text-gray-900 text-xl font-bold mb-4">Instructora</h2>
                            <div class="flex items-start gap-4">
                                <!-- Avatar -->
                                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-xl flex-shrink-0 overflow-hidden">
                                    @if($course->instructor->avatar)
                                        <img src="{{ $course->instructor->photo_url }}" alt="{{ $course->instructor->name }}" class="w-full h-full object-cover">
                                    @else
                                        {{ substr($course->instructor->name, 0, 1) }}
                                    @endif
                                </div>

                                <!-- Info -->
                                <div class="flex-1">
                                    <h3 class="text-gray-900 font-bold text-lg mb-1">{{ $course->instructor->name }}</h3>
                                    @if($course->instructor->description)
                                        <p class="text-gray-600 text-sm leading-relaxed mb-3">{{ $course->instructor->description }}</p>
                                    @endif
                                    @if($course->instructor->instagram)
                                        <a href="https://instagram.com/{{ $course->instructor->instagram }}" target="_blank" class="inline-flex items-center gap-2 text-purple-600 hover:text-purple-700 font-semibold text-sm transition">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                            </svg>
                                            @{{ $course->instructor->instagram }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Stats -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-100 rounded-lg p-4">
                            <p class="text-gray-600 text-sm mb-1">Módulos</p>
                            <p class="text-gray-900 text-2xl font-bold">{{ $course->modules->count() }}</p>
                        </div>
                        <div class="bg-gray-100 rounded-lg p-4">
                            <p class="text-gray-600 text-sm mb-1">Clases</p>
                            <p class="text-gray-900 text-2xl font-bold">{{ $totalLessons }}</p>
                        </div>
                        @if($trialLessons > 0)
                        <div class="bg-gray-100 rounded-lg p-4">
                            <p class="text-gray-600 text-sm mb-1">Clases Gratis</p>
                            <p class="text-green-500 text-2xl font-bold">{{ $trialLessons }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Access Status -->
                    @if(!auth()->user()->hasFullAccess() && !auth()->user()->isAdmin())
                        <div class="bg-orange-500/10 border border-orange-500/30 rounded-lg p-4">
                            <p class="text-orange-400 text-sm">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                You can view all content but only access free lessons. <a href="{{ route('purchase-membership') }}" wire:navigate class="underline font-bold">Request full access</a> to unlock everything.
                            </p>
                        </div>
                    @endif
                </div>
            @else
                <!-- Classes Tab -->
                <div class="p-4 space-y-4">
                    @forelse($course->modules as $module)
                        <!-- Module Header -->
                        <div class="bg-gray-900 rounded-lg overflow-hidden">
                            <button
                                wire:click="toggleModule({{ $module->id }})"
                                class="w-full flex items-center justify-between p-4">
                                <div class="flex items-center gap-3">
                                    @php
                                        $moduleData = $moduleCompletionData[$module->id] ?? ['total' => 0, 'completed' => 0, 'isFullyCompleted' => false];
                                    @endphp
                                    @if($moduleData['isFullyCompleted'])
                                        <div class="w-8 h-8 rounded bg-green-500 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 rounded border-2 border-gray-600 bg-gray-800 flex items-center justify-center">
                                            <span class="text-gray-500 text-xs font-bold">{{ $moduleData['completed'] }}/{{ $moduleData['total'] }}</span>
                                        </div>
                                    @endif
                                    <div class="text-left">
                                        <p class="text-white text-sm font-bold">Etapa {{ $loop->iteration }}</p>
                                        <p class="text-white text-lg font-bold">{{ $module->title }}</p>
                                    </div>
                                </div>
                                <svg
                                    class="w-6 h-6 text-white transition-transform {{ in_array($module->id, $expandedModules) ? 'rotate-180' : '' }}"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            @if(in_array($module->id, $expandedModules))
                                <!-- Lessons List -->
                                <div class="px-4 pb-4 space-y-4">
                                    @php
                                        $dayNumber = 1;
                                    @endphp

                                    <div class="space-y-3">
                                        @php
                                            $moduleData = $moduleCompletionData[$module->id] ?? ['total' => 0, 'completed' => 0, 'isFullyCompleted' => false];
                                        @endphp
                                        <h4 class="text-white text-lg font-bold mb-2">Día {{ $dayNumber }}</h4>
                                        <p class="text-green-500 text-sm font-semibold mb-3">{{ $moduleData['completed'] }} de {{ $module->lessons->count() }} completadas</p>

                                        @foreach($module->lessons as $lesson)
                                            @php
                                                $canAccess = $lesson->isAccessibleBy(auth()->user());
                                                $isCompleted = $completedLessonIds->contains($lesson->id);
                                                $lessonLevelColors = [
                                                    'principiante' => 'bg-orange-500 text-white',
                                                    'intermedio' => 'bg-blue-500 text-white',
                                                    'avanzado' => 'bg-red-600 text-white',
                                                ];
                                                $lessonLevelColor = $lessonLevelColors[strtolower($course->level)] ?? 'bg-gray-500 text-white';
                                            @endphp

                                            <a
                                                href="{{ $canAccess ? route('lessons.show', $lesson) : route('purchase-membership') }}"
                                                {{ $canAccess ? 'wire:navigate' : '' }}
                                                wire:click="markLessonAsCompleted({{ $lesson->id }})"
                                                class="flex gap-3 bg-gray-800 rounded-lg overflow-hidden hover:bg-gray-700 transition">
                                                <!-- Thumbnail -->
                                                <div class="relative w-32 h-24 flex-shrink-0">
                                                    @if($lesson->thumbnail)
                                                        <img src="{{ $lesson->thumbnail_url }}" alt="{{ $lesson->title }}" class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full bg-gradient-to-br from-purple-500 to-pink-500"></div>
                                                    @endif

                                                    <!-- Overlay -->
                                                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                                        @if($canAccess)
                                                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M8 5v14l11-7z"></path>
                                                            </svg>
                                                        @else
                                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        @endif
                                                    </div>

                                                    <!-- Progress Check - Only show if completed -->
                                                    @if($isCompleted)
                                                        <div class="absolute top-2 left-2 w-6 h-6 rounded bg-green-500 flex items-center justify-center">
                                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Lesson Info -->
                                                <div class="flex-1 py-3 pr-3">
                                                    <h5 class="text-white font-bold text-sm mb-2 line-clamp-2">{{ $lesson->title }}</h5>

                                                    <!-- Tags -->
                                                    <div class="flex flex-wrap gap-1.5 mb-2">
                                                        <span class="px-2 py-0.5 {{ $lessonLevelColor }} text-xs font-bold uppercase rounded">
                                                            {{ $course->level }}
                                                        </span>
                                                        @if($lesson->is_trial)
                                                            <span class="px-2 py-0.5 bg-green-500 text-white text-xs font-bold uppercase rounded">Gratis</span>
                                                        @endif
                                                        @foreach($lesson->tags as $tag)
                                                            <span class="px-2 py-0.5 bg-gray-700 text-white text-xs font-bold uppercase rounded">
                                                                {{ $tag->name }}
                                                            </span>
                                                        @endforeach
                                                    </div>

                                                    <!-- Duration -->
                                                    @if($lesson->duration_minutes && $lesson->video_type !== 'youtube')
                                                        <p class="text-gray-400 text-xs">{{ $lesson->duration_minutes }}:00 • {{ $lesson->instructor?->name ?? 'Instructor' }}</p>
                                                    @endif
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <p class="text-gray-400">No hay módulos disponibles aún.</p>
                        </div>
                    @endforelse
                </div>
            @endif
        </div>

        <!-- Bottom CTA Button -->
        <div class="fixed bottom-0 left-0 right-0 z-50 lg:hidden">
            @if($nextLesson)
                <a href="{{ route('lessons.show', $nextLesson) }}" wire:navigate wire:click="markLessonAsCompleted({{ $nextLesson->id }})" class="block w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold text-lg transition text-center">
                    {{ $completedLessons > 0 ? 'CONTINUAR PROGRAMA' : 'COMENZAR PROGRAMA' }}
                </a>
            @else
                @if($completedLessons >= $totalLessons && $totalLessons > 0)
                    <button class="w-full py-4 bg-green-600 text-white font-bold text-lg cursor-default">
                        🎉 ¡COMPLETASTE TODAS LAS CLASES ACCESIBLES!
                    </button>
                @elseif($totalLessons === 0)
                    <button class="w-full py-4 bg-gray-600 text-white font-bold text-lg cursor-not-allowed" disabled>
                        NO HAY CLASES DISPONIBLES AÚN
                    </button>
                @else
                    <a href="{{ route('purchase-membership') }}" wire:navigate class="block w-full py-4 bg-orange-600 hover:bg-orange-700 text-white font-bold text-lg transition text-center">
                        🔓 SOLICITA ACCESO PARA CONTINUAR
                    </a>
                @endif
            @endif
        </div>
    </div>

    <!-- Desktop Version (existing design) -->
    <div class="hidden lg:block max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Course Header -->
        <div class="mb-8">
            <div class="mb-4 flex items-center justify-between">
                <a href="{{ route('courses.index') }}" wire:navigate class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 hover:text-gray-900 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <button wire:click="toggleFavorite" class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition">
                    @if($isFavorited)
                        <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                    @else
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    @endif
                </button>
            </div>

            <div class="card-premium">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Course Image -->
                    <div class="lg:col-span-1">
                        @if($course->image)
                            <img src="{{ $course->image_url }}" alt="{{ $course->title }}" class="w-full aspect-video object-cover rounded-2xl">
                        @else
                            <div class="w-full aspect-video bg-gradient-card rounded-2xl flex items-center justify-center">
                                <span class="text-8xl">💃</span>
                            </div>
                        @endif
                    </div>

                    <!-- Course Info -->
                    <div class="lg:col-span-2">
                        <span class="px-4 py-2 rounded text-xs font-bold uppercase {{ $levelColor }} inline-block mb-4">
                            {{ $course->level }}
                        </span>

                        <h1 class="font-display text-3xl md:text-4xl text-cream mb-4">
                            {{ $course->title }}
                        </h1>

                        <p class="text-cream/80 text-lg mb-6 leading-relaxed">
                            {{ $course->description }}
                        </p>

                        <!-- Stats -->
                        <div class="flex flex-wrap gap-6 text-cream/70 mb-6">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-pink-vibrant" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>{{ $course->modules->count() }} {{ $course->modules->count() === 1 ? 'módulo' : 'módulos' }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-pink-vibrant" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ $totalLessons }} {{ $totalLessons === 1 ? 'lección' : 'lecciones' }}</span>
                            </div>
                            @if($trialLessons > 0)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-pink-vibrant" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <span>{{ $trialLessons }} {{ $trialLessons === 1 ? 'lección gratuita' : 'lecciones gratuitas' }}</span>
                            </div>
                            @endif
                        </div>

                        <!-- Access Status -->
                        @if(!auth()->user()->hasFullAccess() && !auth()->user()->isAdmin())
                            <div class="bg-orange-500/10 border border-orange-500/30 rounded-lg p-4">
                                <p class="text-orange-400 text-sm flex items-start">
                                    <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Puedes ver todo el contenido del curso, pero solo puedes acceder a las lecciones gratuitas. <a href="{{ route('purchase-membership') }}" wire:navigate class="underline font-bold">Solicita acceso completo</a> para desbloquear todas las lecciones.</span>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Section -->
        <div class="mb-8">
            <div class="card-premium">
                <h2 class="font-display text-2xl text-cream mb-6">Tu Progreso</h2>
                <div class="space-y-6">
                    <!-- Progress Bar -->
                    <div>
                        <div class="h-3 bg-purple-deep rounded-full overflow-hidden mb-3">
                            <div class="h-full bg-green-500 transition-all duration-500" style="width: {{ $completionPercentage }}%"></div>
                        </div>
                        <p class="text-cream text-lg font-semibold">{{ $completionPercentage }}% Completado</p>
                    </div>

                    <!-- Stats Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-purple-deep/50 rounded-lg p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-green-500/20 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-7 h-7 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-cream/70 text-sm mb-1">Clases Completadas</p>
                                    <p class="text-cream text-2xl font-bold">{{ $completedLessons }} de {{ $totalLessons }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-purple-deep/50 rounded-lg p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-green-500/20 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-7 h-7 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-cream/70 text-sm mb-1">Tiempo Practicando</p>
                                    <p class="text-cream text-2xl font-bold">{{ $minutesSpent }} min</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-purple-deep/50 rounded-lg p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-green-500/20 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-7 h-7 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-cream/70 text-sm mb-1">Progreso</p>
                                    <p class="text-cream text-2xl font-bold">{{ $completionPercentage }}%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instructor Section -->
        @if($course->instructor)
        <div class="mb-8">
            <div class="card-premium">
                <h2 class="font-display text-2xl text-cream mb-6">Instructora</h2>
                <div class="flex items-start gap-6">
                    <!-- Avatar -->
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-3xl flex-shrink-0 overflow-hidden">
                        @if($course->instructor->avatar)
                            <img src="{{ $course->instructor->photo_url }}" alt="{{ $course->instructor->name }}" class="w-full h-full object-cover">
                        @else
                            {{ substr($course->instructor->name, 0, 1) }}
                        @endif
                    </div>

                    <!-- Info -->
                    <div class="flex-1">
                        <h3 class="text-cream font-bold text-2xl mb-3">{{ $course->instructor->name }}</h3>
                        @if($course->instructor->description)
                            <p class="text-cream/80 text-base leading-relaxed mb-4">{{ $course->instructor->description }}</p>
                        @endif
                        @if($course->instructor->instagram)
                            <a href="https://instagram.com/{{ $course->instructor->instagram }}" target="_blank" class="inline-flex items-center gap-2 text-pink-vibrant hover:text-pink-500 font-semibold text-base transition">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                                @{{ $course->instructor->instagram }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Modules Accordion -->
        <div class="space-y-4">
            <h2 class="font-display text-2xl text-cream mb-4">Contenido del Curso</h2>

            @forelse($course->modules as $module)
                <div class="card-premium">
                    <button
                        wire:click="toggleModule({{ $module->id }})"
                        class="w-full flex items-center justify-between text-left group">
                        <div class="flex-1">
                            <h3 class="font-display text-xl text-cream group-hover:text-pink-vibrant transition mb-1">
                                {{ $module->title }}
                            </h3>
                            <p class="text-cream/70 text-sm">
                                {{ $module->lessons->count() }} {{ $module->lessons->count() === 1 ? 'lección' : 'lecciones' }}
                            </p>
                        </div>
                        <svg
                            class="w-6 h-6 text-pink-vibrant transition-transform {{ in_array($module->id, $expandedModules) ? 'rotate-180' : '' }}"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    @if(in_array($module->id, $expandedModules))
                        <div class="mt-4 pt-4 border-t border-pink-vibrant/20 space-y-3">
                            <!-- Module Stats -->
                            @php
                                $moduleData = $moduleCompletionData[$module->id] ?? ['total' => 0, 'completed' => 0, 'isFullyCompleted' => false];
                            @endphp
                            <div class="mb-4">
                                <p class="text-green-500 text-sm font-semibold">{{ $moduleData['completed'] }} de {{ $module->lessons->count() }} completadas</p>
                            </div>

                            @foreach($module->lessons as $lesson)
                                @php
                                    $canAccess = $lesson->isAccessibleBy(auth()->user());
                                    $isCompleted = $completedLessonIds->contains($lesson->id);
                                    $lessonLevelColors = [
                                        'principiante' => 'bg-orange-500 text-white',
                                        'intermedio' => 'bg-blue-500 text-white',
                                        'avanzado' => 'bg-red-600 text-white',
                                    ];
                                    $lessonLevelColor = $lessonLevelColors[strtolower($course->level)] ?? 'bg-gray-500 text-white';
                                @endphp

                                <a
                                    href="{{ $canAccess ? route('lessons.show', $lesson) : route('purchase-membership') }}"
                                    {{ $canAccess ? 'wire:navigate' : '' }}
                                    wire:click="markLessonAsCompleted({{ $lesson->id }})"
                                    class="flex items-start gap-3 p-3 rounded-lg hover:bg-purple-deep/50 cursor-pointer transition group">
                                    <!-- Lesson Thumbnail -->
                                    <div class="relative w-24 h-16 rounded-lg overflow-hidden flex-shrink-0">
                                        @if($lesson->thumbnail)
                                            <img src="{{ $lesson->thumbnail_url }}" alt="{{ $lesson->title }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-white opacity-80" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M8 5v14l11-7z"></path>
                                                </svg>
                                            </div>
                                        @endif

                                        <!-- Lock/Play overlay -->
                                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                            @if($canAccess)
                                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M8 5v14l11-7z"></path>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                        </div>

                                        <!-- Progress Check - Only show if completed -->
                                        @if($isCompleted)
                                            <div class="absolute top-2 left-2 w-6 h-6 rounded bg-green-500 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Lesson Info -->
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-cream {{ $canAccess ? 'group-hover:text-pink-vibrant' : '' }} transition font-medium mb-1">
                                            {{ $lesson->title }}
                                        </h4>

                                        <!-- Tags and Level -->
                                        <div class="flex flex-wrap gap-1.5 mb-1">
                                            <!-- Level Badge -->
                                            <span class="px-2 py-0.5 {{ $lessonLevelColor }} text-xs font-bold uppercase rounded">
                                                {{ $course->level }}
                                            </span>

                                            <!-- Trial Badge -->
                                            @if($lesson->is_trial)
                                                <span class="px-2 py-0.5 bg-green-500 text-white text-xs font-bold uppercase rounded">Gratis</span>
                                            @endif

                                            <!-- Lesson Tags -->
                                            @foreach($lesson->tags as $tag)
                                                <span class="px-2 py-0.5 bg-gray-700 text-white text-xs font-bold uppercase rounded">
                                                    {{ $tag->name }}
                                                </span>
                                            @endforeach
                                        </div>

                                        @if($lesson->description)
                                            <p class="text-cream/60 text-xs mt-1 line-clamp-1">{{ $lesson->description }}</p>
                                        @endif
                                    </div>

                                    <!-- Arrow Icon -->
                                    @if($canAccess)
                                        <svg class="w-5 h-5 text-pink-vibrant group-hover:translate-x-1 transition flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <div class="card-premium text-center py-8">
                    <p class="text-cream/70">Este curso aún no tiene módulos.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
