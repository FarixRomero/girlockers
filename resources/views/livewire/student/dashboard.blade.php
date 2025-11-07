<div class="max-w-full">
    <!-- Hero Section - Bienvenida con estadísticas -->
    <div class="bg-gradient-to-br from-gray-900 via-purple-900 to-black text-white py-4 md:py-6 px-4">
        <h1 class="text-2xl font-black md:text-7xl md:font-bold mb-3 md:mb-4 tracking-tight">
            Bienvenido,<br>
            <span class="bg-gradient-to-r from-pink-500 via-purple-500 to-blue-500 bg-clip-text text-transparent">
                {{ $stats['name'] }}
            </span>
        </h1>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-2 md:gap-4 mt-3 md:mt-4">
            <!-- Minutes Dancing -->
            <div class="bg-white/10 backdrop-blur-md rounded-xl md:rounded-2xl p-2 md:p-4 border border-white/20 hover:bg-white/20 transition">
                <div class="text-3xl font-black md:text-7xl md:font-bold mb-1 md:mb-2 bg-gradient-to-r from-pink-400 to-purple-400 bg-clip-text text-transparent">
                    {{ $stats['total_minutes'] }}
                </div>
                <div class="text-xs font-bold md:text-xl md:font-semibold uppercase tracking-wider text-white/90">
                    Minutos Bailando
                </div>
            </div>

            <!-- Completed Lessons -->
            <div class="bg-white/10 backdrop-blur-md rounded-xl md:rounded-2xl p-2 md:p-4 border border-white/20 hover:bg-white/20 transition">
                <div class="text-3xl font-black md:text-7xl md:font-bold mb-1 md:mb-2 bg-gradient-to-r from-blue-400 to-cyan-400 bg-clip-text text-transparent">
                    {{ $stats['completed_lessons'] }}
                </div>
                <div class="text-xs font-bold md:text-xl md:font-semibold uppercase tracking-wider text-white/90">
                    Clases Completadas
                </div>
            </div>

            <!-- Access Status -->
            <div class="bg-white/10 backdrop-blur-md rounded-xl md:rounded-2xl p-2 md:p-4 border border-white/20 hover:bg-white/20 transition col-span-2 md:col-span-1">
                <div class="text-2xl font-black md:text-5xl md:font-bold mb-1 md:mb-2 {{ $stats['has_access'] ? 'text-green-400' : 'text-yellow-400' }}">
                    {{ $stats['has_access'] ? 'PREMIUM' : 'FREE' }}
                </div>
                <div class="text-xs font-bold md:text-xl md:font-semibold uppercase tracking-wider text-white/90">
                    Tu Plan
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Lessons Carousel -->
    @if($recentLessons->count() > 0)
    <div class="px-4 py-3 md:py-4">
        <h2 class="text-xl font-black md:text-5xl md:font-bold text-gray-900 mb-3 md:mb-4 tracking-tight">Últimas Clases</h2>
        <div class="relative overflow-hidden">
            <div class="overflow-x-auto pb-4 -mx-4 px-4 scroll-smooth scrollbar-hide" id="recent-carousel">
                <div class="flex gap-3 md:gap-6" style="width: max-content;">
                    @foreach($recentLessons as $lesson)
                        <div class="group rounded-lg overflow-hidden" style="width: 280px; flex-shrink: 0;">
                            <a href="{{ route('lessons.show', $lesson) }}" wire:navigate class="block">
                                <div class="aspect-video relative rounded-lg overflow-hidden">
                                    @if($lesson->thumbnail)
                                        <img src="{{ asset('storage/' . $lesson->thumbnail) }}" alt="{{ $lesson->title }}" class="w-full h-full object-cover">
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
                                        @if($lesson->duration)
                                            <span class="px-1.5 py-0.5 bg-black/80 text-white text-[10px] font-bold uppercase rounded">{{ $lesson->duration }} MIN</span>
                                        @endif
                                        @foreach($lesson->tags->take(1) as $tag)
                                            <span class="px-1.5 py-0.5 bg-black/80 text-white text-[10px] font-bold uppercase rounded">{{ $tag->name }}</span>
                                        @endforeach
                                    </div>
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
                    @endforeach
                </div>
            </div>
            <button onclick="document.getElementById('recent-carousel').scrollBy({left: -290, behavior: 'smooth'})"
                    class="hidden md:flex absolute left-0 top-1/3 -translate-y-1/2 -translate-x-4 bg-white/90 hover:bg-white p-3 rounded-full shadow-lg transition z-10">
                <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button onclick="document.getElementById('recent-carousel').scrollBy({left: 290, behavior: 'smooth'})"
                    class="hidden md:flex absolute right-0 top-1/3 -translate-y-1/2 translate-x-4 bg-white/90 hover:bg-white p-3 rounded-full shadow-lg transition z-10">
                <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </div>
    @endif

    <!-- Coreografía Carousel -->
    @if($coreografiaLessons->count() > 0)
    <div class="px-4 py-3 md:py-4">
        <h2 class="text-xl font-black md:text-5xl md:font-bold text-gray-900 mb-3 md:mb-4 tracking-tight">Coreografía</h2>
        <div class="relative overflow-hidden">
            <div class="overflow-x-auto pb-4 -mx-4 px-4 scroll-smooth scrollbar-hide" id="coreografia-carousel">
                <div class="flex gap-3 md:gap-6" style="width: max-content;">
                    @foreach($coreografiaLessons as $lesson)
                        <div class="group rounded-lg overflow-hidden" style="width: 280px; flex-shrink: 0;">
                            <a href="{{ route('lessons.show', $lesson) }}" wire:navigate class="block">
                                <div class="aspect-video relative rounded-lg overflow-hidden">
                                    @if($lesson->thumbnail)
                                        <img src="{{ asset('storage/' . $lesson->thumbnail) }}" alt="{{ $lesson->title }}" class="w-full h-full object-cover">
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
                                        @if($lesson->duration)
                                            <span class="px-1.5 py-0.5 bg-black/80 text-white text-[10px] font-bold uppercase rounded">{{ $lesson->duration }} MIN</span>
                                        @endif
                                        @foreach($lesson->tags->take(1) as $tag)
                                            <span class="px-1.5 py-0.5 bg-black/80 text-white text-[10px] font-bold uppercase rounded">{{ $tag->name }}</span>
                                        @endforeach
                                    </div>
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
                    @endforeach
                </div>
            </div>
            <button onclick="document.getElementById('coreografia-carousel').scrollBy({left: -290, behavior: 'smooth'})"
                    class="hidden md:flex absolute left-0 top-1/3 -translate-y-1/2 -translate-x-4 bg-white/90 hover:bg-white p-3 rounded-full shadow-lg transition z-10">
                <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button onclick="document.getElementById('coreografia-carousel').scrollBy({left: 290, behavior: 'smooth'})"
                    class="hidden md:flex absolute right-0 top-1/3 -translate-y-1/2 translate-x-4 bg-white/90 hover:bg-white p-3 rounded-full shadow-lg transition z-10">
                <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </div>
    @endif

    <!-- Saved Lessons Carousel -->
    @if($savedLessons->count() > 0)
    <div class="px-4 py-3 md:py-4">
        <h2 class="text-xl font-black md:text-5xl md:font-bold text-gray-900 mb-3 md:mb-4 tracking-tight">Tus Clases Guardadas</h2>
        <div class="relative overflow-hidden">
            <div class="overflow-x-auto pb-4 -mx-4 px-4 scroll-smooth scrollbar-hide" id="saved-carousel">
                <div class="flex gap-3 md:gap-6" style="width: max-content;">
                    @foreach($savedLessons as $lesson)
                        <div class="group rounded-lg overflow-hidden" style="width: 280px; flex-shrink: 0;">
                            <a href="{{ route('lessons.show', $lesson) }}" wire:navigate class="block">
                                <div class="aspect-video relative rounded-lg overflow-hidden">
                                    @if($lesson->thumbnail)
                                        <img src="{{ asset('storage/' . $lesson->thumbnail) }}" alt="{{ $lesson->title }}" class="w-full h-full object-cover">
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
                                        @if($lesson->duration)
                                            <span class="px-1.5 py-0.5 bg-black/80 text-white text-[10px] font-bold uppercase rounded">{{ $lesson->duration }} MIN</span>
                                        @endif
                                        @foreach($lesson->tags->take(1) as $tag)
                                            <span class="px-1.5 py-0.5 bg-black/80 text-white text-[10px] font-bold uppercase rounded">{{ $tag->name }}</span>
                                        @endforeach
                                    </div>
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
                    @endforeach
                </div>
            </div>
            <button onclick="document.getElementById('saved-carousel').scrollBy({left: -290, behavior: 'smooth'})"
                    class="hidden md:flex absolute left-0 top-1/3 -translate-y-1/2 -translate-x-4 bg-white/90 hover:bg-white p-3 rounded-full shadow-lg transition z-10">
                <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button onclick="document.getElementById('saved-carousel').scrollBy({left: 290, behavior: 'smooth'})"
                    class="hidden md:flex absolute right-0 top-1/3 -translate-y-1/2 translate-x-4 bg-white/90 hover:bg-white p-3 rounded-full shadow-lg transition z-10">
                <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </div>
    @endif

    <!-- Trending Courses -->
    @if($trendingCourses->count() > 0)
    <div class="px-4 py-3 md:py-4 pb-8">
        <h2 class="text-xl font-black md:text-5xl md:font-bold text-gray-900 mb-3 md:mb-4 tracking-tight">Cursos de Moda</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($trendingCourses as $course)
                <a href="{{ route('courses.show', $course) }}" wire:navigate class="group bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-xl transition">
                    <div class="aspect-video relative">
                        @if($course->image)
                            <!-- Course Image -->
                            <img src="{{ asset('storage/' . $course->image) }}"
                                 alt="{{ $course->title }}"
                                 class="w-full h-full object-cover">
                        @else
                            <!-- Fallback Gradient -->
                            <div class="w-full h-full bg-gradient-to-br from-pink-500 via-purple-500 to-blue-500">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="w-20 h-20 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                            </div>
                        @endif

                        <!-- Overlay with info -->
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/90 to-transparent p-6">
                            <h3 class="text-white font-bold text-2xl mb-2">{{ $course->title }}</h3>
                            <div class="flex items-center gap-4 text-white/80 text-sm">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"></path>
                                    </svg>
                                    {{ $course->modules_count }} módulos
                                </span>
                                @php
                                    $levelColors = [
                                        'principiante' => 'bg-orange-500',
                                        'intermedio' => 'bg-blue-500',
                                        'avanzado' => 'bg-red-600'
                                    ];
                                    $levelColor = $levelColors[$course->level] ?? 'bg-gray-500';
                                @endphp
                                <span class="px-2 py-1 {{ $levelColor }} text-white text-xs font-bold uppercase rounded">
                                    {{ $course->level }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @if($course->description)
                    <div class="p-6">
                        <p class="text-gray-700 line-clamp-3">{{ $course->description }}</p>
                    </div>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
