<div>
    @php
        $levelConfig = [
            'beginner' => ['name' => 'Principiante', 'icon' => '🌱', 'color' => 'bg-green-500/20 text-green-400'],
            'intermediate' => ['name' => 'Intermedio', 'icon' => '🔥', 'color' => 'bg-orange-500/20 text-orange-400'],
            'advanced' => ['name' => 'Avanzado', 'icon' => '💎', 'color' => 'bg-purple-500/20 text-purple-400'],
        ];
        $config = $levelConfig[$course->level] ?? $levelConfig['beginner'];
        $totalLessons = $course->modules->sum(fn($module) => $module->lessons->count());
        $trialLessons = $course->modules->flatMap->lessons->where('is_trial', true)->count();
    @endphp

    <!-- Course Header -->
    <div class="mb-8">
        <div class="mb-4">
            <a href="{{ route('courses.index') }}" wire:navigate class="text-pink-vibrant hover:text-pink-light inline-flex items-center text-sm">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Volver al catálogo
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
                    <span class="px-4 py-2 rounded-full text-sm font-bold {{ $config['color'] }} inline-block mb-4">
                        {{ $config['icon'] }} {{ $config['name'] }}
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
                                <span>Solo puedes ver lecciones de prueba. <a href="{{ route('dashboard') }}" wire:navigate class="underline font-bold">Solicita acceso completo</a> para desbloquear todo el contenido.</span>
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
                    <div class="mt-4 pt-4 border-t border-pink-vibrant/20 space-y-2">
                        @foreach($module->lessons as $lesson)
                            @php
                                $canAccess = $lesson->isAccessibleBy(auth()->user());
                            @endphp

                            <a
                                href="{{ $canAccess ? route('lessons.show', $lesson) : '#' }}"
                                wire:navigate
                                class="flex items-center justify-between p-3 rounded-lg {{ $canAccess ? 'hover:bg-purple-deep/50 cursor-pointer' : 'opacity-50 cursor-not-allowed' }} transition group">
                                <div class="flex items-center flex-1">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-pink flex items-center justify-center mr-3 flex-shrink-0">
                                        @if($canAccess)
                                            <svg class="w-5 h-5 text-cream" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z"></path>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-cream" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <h4 class="text-cream {{ $canAccess ? 'group-hover:text-pink-vibrant' : '' }} transition">
                                                {{ $lesson->title }}
                                            </h4>
                                            @if($lesson->is_trial)
                                                <span class="ml-2 px-2 py-0.5 bg-green-500/20 text-green-400 text-xs rounded-full">Gratis</span>
                                            @endif
                                        </div>
                                        @if($lesson->description)
                                            <p class="text-cream/60 text-sm mt-1">{{ Str::limit($lesson->description, 80) }}</p>
                                        @endif
                                    </div>
                                </div>

                                @if($canAccess)
                                    <svg class="w-5 h-5 text-pink-vibrant group-hover:translate-x-1 transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
