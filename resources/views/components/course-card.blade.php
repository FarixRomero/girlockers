@props(['course'])

@php
    $levelConfig = [
        'beginner' => ['name' => 'Principiante', 'icon' => '🌱', 'color' => 'bg-green-500/20 text-green-400'],
        'intermediate' => ['name' => 'Intermedio', 'icon' => '🔥', 'color' => 'bg-orange-500/20 text-orange-400'],
        'advanced' => ['name' => 'Avanzado', 'icon' => '💎', 'color' => 'bg-purple-500/20 text-purple-400'],
    ];

    $config = $levelConfig[$course->level] ?? $levelConfig['beginner'];
    $totalLessons = $course->modules->sum(fn($module) => $module->lessons_count);
@endphp

<a href="{{ route('courses.show', $course) }}" wire:navigate class="block card-premium hover-lift group">
    <!-- Course Image -->
    @if($course->image)
        <div class="aspect-video bg-purple-darkest rounded-t-3xl overflow-hidden mb-4">
            <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        </div>
    @else
        <div class="aspect-video bg-gradient-card rounded-t-3xl overflow-hidden mb-4 flex items-center justify-center">
            <span class="text-6xl">💃</span>
        </div>
    @endif

    <!-- Course Info -->
    <div class="px-2">
        <!-- Level Badge -->
        <div class="flex items-center justify-between mb-3">
            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $config['color'] }}">
                {{ $config['icon'] }} {{ $config['name'] }}
            </span>
            @if($course->is_published)
                <span class="text-xs text-green-400">● Publicado</span>
            @endif
        </div>

        <!-- Title & Description -->
        <h3 class="font-display text-xl text-cream mb-2 group-hover:text-pink-vibrant transition">
            {{ $course->title }}
        </h3>

        <p class="text-cream/70 text-sm mb-4 line-clamp-2">
            {{ $course->description }}
        </p>

        <!-- Stats -->
        <div class="flex items-center justify-between text-sm text-cream/60 pt-4 border-t border-pink-vibrant/10">
            <div class="flex items-center space-x-4">
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ $course->modules_count }} {{ $course->modules_count === 1 ? 'módulo' : 'módulos' }}
                </span>
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $totalLessons }} {{ $totalLessons === 1 ? 'lección' : 'lecciones' }}
                </span>
            </div>
            <svg class="w-5 h-5 text-pink-vibrant group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </div>
    </div>
</a>
