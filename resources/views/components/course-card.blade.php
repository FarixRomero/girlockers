@props(['course'])

@php
    $levelColors = [
        'principiante' => 'bg-orange-500',
        'intermedio' => 'bg-blue-500',
        'avanzado' => 'bg-red-600'
    ];
    $levelColor = $levelColors[$course->level] ?? 'bg-gray-500';
    $totalLessons = $course->modules->sum(fn($module) => $module->lessons_count);
@endphp

<div class="group bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition relative">
    <!-- Thumbnail -->
    <a href="{{ route('courses.show', $course) }}" wire:navigate class="block relative">
        @if($course->image)
            <div class="aspect-video bg-gradient-to-br from-purple-500 to-pink-500 relative">
                <img src="{{ $course->image_url }}" alt="{{ $course->title }}" class="absolute inset-0 w-full h-full object-cover">

                <!-- Title Overlay (bottom) -->
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                    <h3 class="text-white font-bold text-lg line-clamp-2">{{ $course->title }}</h3>
                </div>
            </div>
        @else
            <div class="aspect-video bg-gradient-to-br from-purple-500 to-pink-500 relative">
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg class="w-16 h-16 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>

                <!-- Title Overlay (bottom) -->
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                    <h3 class="text-white font-bold text-lg line-clamp-2">{{ $course->title }}</h3>
                </div>
            </div>
        @endif
    </a>

    <!-- Content -->
    <div class="p-4">
        <!-- Tags Row -->
        <div class="flex flex-wrap gap-2 mb-3">
            <!-- Course Level Badge -->
            <span class="px-2 py-1 {{ $levelColor }} text-white text-xs font-bold uppercase rounded">
                {{ $course->level }}
            </span>

            <!-- Modules Count -->
            <span class="px-2 py-1 bg-gray-900 text-white text-xs font-bold uppercase rounded">
                {{ $course->modules_count }} {{ $course->modules_count === 1 ? 'MÓDULO' : 'MÓDULOS' }}
            </span>

            <!-- Lessons Count -->
            <span class="px-2 py-1 bg-gray-900 text-white text-xs font-bold uppercase rounded">
                {{ $totalLessons }} {{ $totalLessons === 1 ? 'CLASE' : 'CLASES' }}
            </span>
        </div>

        <!-- Description -->
        @if($course->description)
            <p class="text-sm text-gray-600 line-clamp-2">
                {{ $course->description }}
            </p>
        @endif
    </div>
</div>
