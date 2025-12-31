<div class="relative">
    <!-- Back Button (Mobile) - Over video -->
    <div class="lg:hidden fixed top-4 left-4 z-50">
        <a href="{{ url()->previous() }}" wire:navigate class="w-10 h-10 rounded-full bg-black/50 backdrop-blur-sm flex items-center justify-center text-white hover:bg-black/70 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
    </div>

    <!-- Back Button (Desktop) - Over video -->
    <div class="hidden lg:block absolute top-4 left-4 z-50">
        <a href="{{ url()->previous() }}" wire:navigate class="w-10 h-10 rounded-full bg-black/50 backdrop-blur-sm flex items-center justify-center text-white hover:bg-black/70 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
    </div>

    <!-- Video Player Section - calc(100vh - navbar height) -->
    <div class="z-10" style="height: calc(100vh - 3.5rem);">
        @if ($lesson->video_type === 'youtube' && $lesson->youtube_id)
            <!-- YouTube Player -->
            <div class="w-full h-full">
                <iframe src="https://www.youtube.com/embed/{{ $lesson->youtube_id }}?rel=0&modestbranding=1"
                    title="{{ $lesson->title }}" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen class="w-full h-full">
                </iframe>
            </div>
        @elseif($lesson->video_type === 'bunny' && $lesson->bunny_video_id)
            <!-- Bunny.net Player -->
            @php
                $libraryId = config('bunny.library_id');
            @endphp
            <div class="w-full h-full">
                <iframe src="https://iframe.mediadelivery.net/embed/{{ $libraryId }}/{{ $lesson->bunny_video_id }}"
                    loading="lazy" style="border: none;"
                    allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;"
                    allowfullscreen="true" title="{{ $lesson->title }}" class="w-full h-full">
                </iframe>
            </div>
        @elseif($lesson->video_type === 'local' && $lesson->video_path)
            <!-- Local Video Player -->
            <x-local-video :lesson="$lesson" :title="$lesson->title" />
        @else
            <!-- No Video Available -->
            <div class="h-full flex items-center justify-center">
                <div class="text-center">
                    <svg class="w-16 h-16 text-cream/30 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                        </path>
                    </svg>
                    <p class="text-cream/70">Video no disponible</p>
                    @if (auth()->user()->is_admin)
                        <p class="text-cream/50 text-sm mt-2">
                            Tipo: {{ $lesson->video_type ?? 'no definido' }}
                            @if ($lesson->video_type === 'bunny')
                                | Video ID: {{ $lesson->bunny_video_id ?? 'no asignado' }}
                            @endif
                        </p>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Lesson Info Section -->
    <div class="px-4 py-6">
        <div class="flex items-start gap-3 mb-4">
            <!-- Instructor Avatar -->
            @if($lesson->instructor)
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-lg">
                        {{ strtoupper(substr($lesson->instructor->name, 0, 1)) }}
                    </div>
                </div>
            @endif

            <!-- Title and Instructor -->
            <div class="flex-1 min-w-0">
                <h1 class="text-xl md:text-2xl font-bold text-gray-900 mb-1 leading-tight">{{ $lesson->title }}</h1>
                @if($lesson->instructor)
                    <p class="text-sm text-gray-600">{{ $lesson->instructor->name }}</p>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2">
                <!-- Share Button -->
                <button
                    onclick="navigator.share ? navigator.share({title: '{{ $lesson->title }}', url: window.location.href}) : navigator.clipboard.writeText(window.location.href).then(() => alert('Link copiado al portapapeles'))"
                    class="w-12 h-12 rounded-full border-2 border-gray-300 flex items-center justify-center hover:bg-gray-50 transition"
                    title="Compartir">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                    </svg>
                </button>

                <!-- Like Button -->
                <button
                    wire:click="toggleLike"
                    class="w-12 h-12 rounded-full border-2 border-gray-300 flex items-center justify-center hover:bg-gray-50 transition"
                    title="{{ $isLiked ? 'Quitar de guardados' : 'Guardar clase' }}">
                    @if($isLiked)
                        <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path>
                        </svg>
                    @else
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    @endif
                </button>
            </div>
        </div>

        <!-- Tags/Badges -->
        <div class="flex flex-wrap gap-1.5">
            @php
                $levelColors = [
                    'principiante' => 'bg-cyan-500',
                    'intermedio' => 'bg-blue-500',
                    'avanzado' => 'bg-red-500'
                ];
                $courseLevel = $lesson->module?->course?->level;
                $levelColor = isset($courseLevel) ? ($levelColors[strtolower($courseLevel)] ?? 'bg-gray-900') : 'bg-gray-900';
            @endphp

            @if($courseLevel)
                <span class="px-3 py-1.5 {{ $levelColor }} text-white text-xs font-bold uppercase rounded">
                    {{ $courseLevel }}
                </span>
            @endif

            @if($lesson->duration_minutes && $lesson->video_type !== 'youtube')
                <span class="px-3 py-1.5 bg-gray-900 text-white text-xs font-bold uppercase rounded">
                    {{ $lesson->duration_minutes }} MIN
                </span>
            @endif

            @foreach($lesson->tags as $tag)
                <span class="px-3 py-1.5 bg-gray-900 text-white text-xs font-bold uppercase rounded">
                    {{ $tag->name }}
                </span>
            @endforeach

            <span class="px-3 py-1.5 bg-gray-900 text-white text-xs font-bold uppercase rounded">
                {{ strtoupper(str_replace('-', ' ', $lesson->module->course->title)) }}
            </span>
        </div>
    </div>

    <!-- Upcoming Lessons Carousel -->
    @if($upcomingLessons && $upcomingLessons->count() > 0)
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h2 class="text-xl md:text-3xl font-bold text-gray-900 mb-4 md:mb-6">Próximas clases del curso</h2>
        <div class="relative">
            <div class="overflow-x-auto pb-4 -mx-4 px-4 scroll-smooth scrollbar-hide" id="upcoming-carousel">
                <div class="flex gap-3 md:gap-6" style="width: max-content;">
                    @foreach($upcomingLessons as $upcomingLesson)
                        <div class="group rounded-lg overflow-hidden" style="width: 280px; flex-shrink: 0;">
                            <a href="{{ route('lessons.show', $upcomingLesson) }}" wire:navigate class="block">
                                <div class="aspect-video relative rounded-lg overflow-hidden">
                                    @if($upcomingLesson->thumbnail)
                                        <img src="{{ $upcomingLesson->thumbnail_url }}" alt="{{ $upcomingLesson->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
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
                                    @if(!$upcomingLesson->is_trial && !auth()->user()->has_full_access)
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
                                            $courseLevel = $upcomingLesson->module?->course?->level;
                                            $levelColor = isset($courseLevel) ? ($levelColors[$courseLevel] ?? 'bg-gray-500') : 'bg-gray-500';
                                        @endphp
                                        @if($courseLevel)
                                            <span class="px-1.5 py-0.5 {{ $levelColor }} text-white text-[10px] font-bold uppercase rounded">{{ $courseLevel }}</span>
                                        @endif
                                        @if($upcomingLesson->duration)
                                            <span class="px-1.5 py-0.5 bg-black/80 text-white text-[10px] font-bold uppercase rounded">{{ $upcomingLesson->duration }} MIN</span>
                                        @endif
                                        @foreach($upcomingLesson->tags->take(1) as $tag)
                                            <span class="px-1.5 py-0.5 bg-black/80 text-white text-[10px] font-bold uppercase rounded">{{ $tag->name }}</span>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Info abajo sin background -->
                                <div class="pt-2">
                                    <h3 class="text-gray-900 font-semibold text-sm line-clamp-2 mb-1">{{ $upcomingLesson->title }}</h3>
                                    @if($upcomingLesson->instructor)
                                        <p class="text-gray-500 text-xs font-normal">{{ $upcomingLesson->instructor->name }}</p>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Scroll Buttons -->
            <button onclick="document.getElementById('upcoming-carousel').scrollBy({left: -340, behavior: 'smooth'})"
                    class="hidden md:flex absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 bg-white/90 hover:bg-white p-3 rounded-full shadow-lg transition z-10">
                <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button onclick="document.getElementById('upcoming-carousel').scrollBy({left: 340, behavior: 'smooth'})"
                    class="hidden md:flex absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 bg-white/90 hover:bg-white p-3 rounded-full shadow-lg transition z-10">
                <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </div>
    @endif

    <!-- Similar Lessons Carousel -->
    @if($similarLessons && $similarLessons->count() > 0)
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-6">
        <h2 class="text-xl md:text-3xl font-bold text-gray-900 mb-4 md:mb-6">Clases similares</h2>
        <div class="relative">
            <div class="overflow-x-auto pb-4 -mx-4 px-4 scroll-smooth scrollbar-hide" id="similar-carousel">
                <div class="flex gap-3 md:gap-6" style="width: max-content;">
                    @foreach($similarLessons as $similarLesson)
                        <div class="group rounded-lg overflow-hidden" style="width: 280px; flex-shrink: 0;">
                            <a href="{{ route('lessons.show', $similarLesson) }}" wire:navigate class="block">
                                <div class="aspect-video relative rounded-lg overflow-hidden">
                                    @if($similarLesson->thumbnail)
                                        <img src="{{ $similarLesson->thumbnail_url }}" alt="{{ $similarLesson->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
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
                                    @if(!$similarLesson->is_trial && !auth()->user()->has_full_access)
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
                                            $courseLevel = $similarLesson->module?->course?->level;
                                            $levelColor = isset($courseLevel) ? ($levelColors[$courseLevel] ?? 'bg-gray-500') : 'bg-gray-500';
                                        @endphp
                                        @if($courseLevel)
                                            <span class="px-1.5 py-0.5 {{ $levelColor }} text-white text-[10px] font-bold uppercase rounded">{{ $courseLevel }}</span>
                                        @endif
                                        @if($similarLesson->duration)
                                            <span class="px-1.5 py-0.5 bg-black/80 text-white text-[10px] font-bold uppercase rounded">{{ $similarLesson->duration }} MIN</span>
                                        @endif
                                        @foreach($similarLesson->tags->take(1) as $tag)
                                            <span class="px-1.5 py-0.5 bg-black/80 text-white text-[10px] font-bold uppercase rounded">{{ $tag->name }}</span>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Info abajo sin background -->
                                <div class="pt-2">
                                    <h3 class="text-gray-900 font-semibold text-sm line-clamp-2 mb-1">{{ $similarLesson->title }}</h3>
                                    @if($similarLesson->instructor)
                                        <p class="text-gray-500 text-xs font-normal">{{ $similarLesson->instructor->name }}</p>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Scroll Buttons -->
            <button onclick="document.getElementById('similar-carousel').scrollBy({left: -340, behavior: 'smooth'})"
                    class="hidden md:flex absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 bg-white/90 hover:bg-white p-3 rounded-full shadow-lg transition z-10">
                <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button onclick="document.getElementById('similar-carousel').scrollBy({left: 340, behavior: 'smooth'})"
                    class="hidden md:flex absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 bg-white/90 hover:bg-white p-3 rounded-full shadow-lg transition z-10">
                <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </div>
    @endif

    <!-- Comments Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="max-w-4xl mx-auto">
            <livewire:student.comment-section :lesson="$lesson" :key="'comments-' . $lesson->id" />
        </div>
    </div>
</div>
