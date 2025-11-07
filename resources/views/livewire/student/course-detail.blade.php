<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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

    <!-- Course Header -->
    <div class="mb-8">
        <div class="mb-4">
            <a href="{{ route('courses.index') }}" wire:navigate class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 hover:text-gray-900 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
        </div>

        <div class="card-premium">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Course Image -->
                <div class="lg:col-span-1">
                    @if($course->image)
                        <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->title }}" class="w-full aspect-video object-cover rounded-2xl">
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
                                <span>Puedes ver todo el contenido del curso, pero solo puedes acceder a las lecciones gratuitas. <a href="{{ route('request-access') }}" wire:navigate class="underline font-bold">Solicita acceso completo</a> para desbloquear todas las lecciones.</span>
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

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
                        @foreach($module->lessons as $lesson)
                            @php
                                $canAccess = $lesson->isAccessibleBy(auth()->user());
                                $lessonLevelColors = [
                                    'principiante' => 'bg-orange-500 text-white',
                                    'intermedio' => 'bg-blue-500 text-white',
                                    'avanzado' => 'bg-red-600 text-white',
                                ];
                                $lessonLevelColor = $lessonLevelColors[strtolower($course->level)] ?? 'bg-gray-500 text-white';
                            @endphp

                            <a
                                href="{{ $canAccess ? route('lessons.show', $lesson) : route('request-access') }}"
                                {{ $canAccess ? 'wire:navigate' : '' }}
                                class="flex items-start gap-3 p-3 rounded-lg hover:bg-purple-deep/50 cursor-pointer transition group">
                                <!-- Lesson Thumbnail -->
                                <div class="relative w-24 h-16 rounded-lg overflow-hidden flex-shrink-0">
                                    @if($lesson->thumbnail)
                                        <img src="{{ asset('storage/' . $lesson->thumbnail) }}" alt="{{ $lesson->title }}" class="w-full h-full object-cover">
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
