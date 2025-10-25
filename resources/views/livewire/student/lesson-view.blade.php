<div>
    <!-- Video Player Section - calc(100vh - navbar height) -->
    <div class="z-10" style="height: calc(100vh - 3.5rem);">
        @if(!$lesson->isAccessibleBy(auth()->user()))
            <!-- Access Denied - Premium Lesson -->
            <div class="h-full flex items-center justify-center bg-gradient-to-br from-purple-900 via-pink-900 to-black">
                <div class="text-center max-w-2xl px-4">
                    <svg class="w-24 h-24 text-pink-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Esta clase es Premium</h2>
                    <p class="text-white/80 text-lg mb-8">Para acceder a esta clase necesitas tener acceso completo a la plataforma.</p>
                    <a href="{{ route('request-access') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-pink-500 to-purple-500 text-white font-bold text-lg rounded-lg hover:shadow-lg transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                        </svg>
                        Solicitar Acceso Completo
                    </a>
                </div>
            </div>
        @elseif ($lesson->video_type === 'youtube' && $lesson->youtube_id)
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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <!-- Title and Actions -->
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-6">
                <div class="flex-1">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">{{ $lesson->title }}</h1>
                    <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600">
                        <span class="font-medium">{{ $lesson->module->course->title }}</span>
                        @if($lesson->instructor)
                            <span class="text-gray-400">•</span>
                            <span>{{ $lesson->instructor->name }}</span>
                        @endif
                        @if($lesson->duration)
                            <span class="text-gray-400">•</span>
                            <span>{{ $lesson->duration }} min</span>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-3">
                    <!-- Save/Like Button -->
                    <button
                        wire:click="toggleLike"
                        class="flex items-center justify-center gap-2 px-4 py-2 bg-white border-2 border-gray-900 text-gray-900 font-semibold rounded hover:bg-gray-50 transition"
                        title="{{ $isLiked ? 'Quitar de guardados' : 'Guardar clase' }}"
                    >
                        @if($isLiked)
                            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path>
                            </svg>
                            <span class="hidden sm:inline">Guardado</span>
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <span class="hidden sm:inline">Guardar</span>
                        @endif
                    </button>

                    <!-- Share Button -->
                    <button
                        onclick="navigator.share ? navigator.share({title: '{{ $lesson->title }}', url: window.location.href}) : navigator.clipboard.writeText(window.location.href).then(() => alert('Link copiado al portapapeles'))"
                        class="flex items-center justify-center gap-2 px-4 py-2 bg-white border-2 border-gray-900 text-gray-900 font-semibold rounded hover:bg-gray-50 transition"
                        title="Compartir clase"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                        </svg>
                        <span class="hidden sm:inline">Compartir</span>
                    </button>

                    <!-- Go to Course Button -->
                    <a
                        href="{{ route('courses.show', $lesson->module->course) }}"
                        wire:navigate
                        class="flex items-center justify-center gap-2 px-4 py-2 bg-gray-900 text-white font-semibold rounded hover:bg-gray-800 transition"
                        title="Ver curso completo"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span class="hidden sm:inline">Ver curso</span>
                    </a>
                </div>
            </div>

            <!-- Tags -->
            @if($lesson->tags->count() > 0)
            <div class="flex flex-wrap gap-2">
                @foreach($lesson->tags as $tag)
                    <span class="px-3 py-1 bg-gray-900 text-white text-xs font-bold uppercase rounded">
                        {{ $tag->name }}
                    </span>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <!-- Upcoming Lessons Carousel -->
    @if($upcomingLessons && $upcomingLessons->count() > 0)
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">Próximas clases del curso</h2>
        <div class="relative ">
            <div class=" pb-4 -mx-4 px-4 scroll-smooth overflow-hidden" id="upcoming-carousel" >
                <div class="flex gap-6" style="width: max-content;">
                    @foreach($upcomingLessons as $upcomingLesson)
                        <div class="group bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition" style="width: 320px;">
                            <!-- Thumbnail -->
                            <a href="{{ route('lessons.show', $upcomingLesson) }}" wire:navigate class="block relative">
                                <div class="aspect-video bg-gradient-to-br from-purple-500 to-pink-500 relative">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>

                                    <!-- Lock icon for premium lessons -->
                                    @if(!$upcomingLesson->is_trial && !auth()->user()->has_full_access)
                                        <div class="absolute top-3 left-3 bg-black/80 rounded-lg p-2">
                                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @endif

                                    @if($upcomingLesson->is_trial)
                                        <span class="absolute top-3 right-3 px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full">Gratis</span>
                                    @endif

                                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                                        <h3 class="text-white font-bold text-lg line-clamp-2">{{ $upcomingLesson->title }}</h3>
                                    </div>
                                </div>
                            </a>

                            <!-- Content -->
                            <div class="p-4">
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @php
                                        $levelColors = [
                                            'principiante' => 'bg-orange-500',
                                            'intermedio' => 'bg-blue-500',
                                            'avanzado' => 'bg-red-600'
                                        ];
                                        $levelColor = $levelColors[$upcomingLesson->module->course->level] ?? 'bg-gray-500';
                                    @endphp
                                    <span class="px-2 py-1 {{ $levelColor }} text-white text-xs font-bold uppercase rounded">
                                        {{ $upcomingLesson->module->course->level }}
                                    </span>

                                    @if($upcomingLesson->duration)
                                        <span class="px-2 py-1 bg-gray-900 text-white text-xs font-bold uppercase rounded">
                                            {{ $upcomingLesson->duration }} MIN
                                        </span>
                                    @endif
                                </div>

                                <div class="text-sm text-gray-600">
                                    @if($upcomingLesson->instructor)
                                        <span>{{ $upcomingLesson->instructor->name }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Scroll Buttons -->
            <button onclick="document.getElementById('upcoming-carousel').scrollBy({left: -340, behavior: 'smooth'})"
                    class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 bg-white/90 hover:bg-white p-3 rounded-full shadow-lg transition z-10">
                <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button onclick="document.getElementById('upcoming-carousel').scrollBy({left: 340, behavior: 'smooth'})"
                    class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 bg-white/90 hover:bg-white p-3 rounded-full shadow-lg transition z-10">
                <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </div>
    @endif

    <!-- Similar Lessons Carousel -->
    @if($similarLessons && $similarLessons->count() > 0)
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">Clases similares</h2>
        <div class="relative ">
            <div class=" pb-4 -mx-4 px-4 scroll-smooth overflow-hidden" id="similar-carousel">
                <div class="flex gap-6" style="width: max-content;">
                    @foreach($similarLessons as $similarLesson)
                        <div class="group bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition" style="width: 320px;">
                            <!-- Thumbnail -->
                            <a href="{{ route('lessons.show', $similarLesson) }}" wire:navigate class="block relative">
                                <div class="aspect-video bg-gradient-to-br from-purple-500 to-pink-500 relative">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>

                                    <!-- Lock icon for premium lessons -->
                                    @if(!$similarLesson->is_trial && !auth()->user()->has_full_access)
                                        <div class="absolute top-3 left-3 bg-black/80 rounded-lg p-2">
                                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @endif

                                    @if($similarLesson->is_trial)
                                        <span class="absolute top-3 right-3 px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full">Gratis</span>
                                    @endif

                                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                                        <h3 class="text-white font-bold text-lg line-clamp-2">{{ $similarLesson->title }}</h3>
                                    </div>
                                </div>
                            </a>

                            <!-- Content -->
                            <div class="p-4">
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @php
                                        $levelColors = [
                                            'principiante' => 'bg-orange-500',
                                            'intermedio' => 'bg-blue-500',
                                            'avanzado' => 'bg-red-600'
                                        ];
                                        $levelColor = $levelColors[$similarLesson->module->course->level] ?? 'bg-gray-500';
                                    @endphp
                                    <span class="px-2 py-1 {{ $levelColor }} text-white text-xs font-bold uppercase rounded">
                                        {{ $similarLesson->module->course->level }}
                                    </span>

                                    @if($similarLesson->duration)
                                        <span class="px-2 py-1 bg-gray-900 text-white text-xs font-bold uppercase rounded">
                                            {{ $similarLesson->duration }} MIN
                                        </span>
                                    @endif

                                    @foreach($similarLesson->tags->take(2) as $tag)
                                        <span class="px-2 py-1 bg-gray-900 text-white text-xs font-bold uppercase rounded">
                                            {{ $tag->name }}
                                        </span>
                                    @endforeach
                                </div>

                                <div class="text-sm text-gray-600 mb-2">
                                    <span class="font-medium">{{ $similarLesson->module->course->title }}</span>
                                    @if($similarLesson->instructor)
                                        <span class="text-gray-400"> • </span>
                                        <span>{{ $similarLesson->instructor->name }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Scroll Buttons -->
            <button onclick="document.getElementById('similar-carousel').scrollBy({left: -340, behavior: 'smooth'})"
                    class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 bg-white/90 hover:bg-white p-3 rounded-full shadow-lg transition z-10">
                <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button onclick="document.getElementById('similar-carousel').scrollBy({left: 340, behavior: 'smooth'})"
                    class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 bg-white/90 hover:bg-white p-3 rounded-full shadow-lg transition z-10">
                <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </div>
    @endif

    <!-- Comments Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-sm p-6">
            <livewire:student.comment-section :lesson="$lesson" :key="'comments-' . $lesson->id" />
        </div>
    </div>
</div>
