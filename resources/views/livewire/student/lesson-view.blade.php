<div>
    <!-- Top Navigation Bar -->
    <div class="fixed top-0 left-0 right-0 z-50 bg-purple-darker/95 backdrop-blur-sm border-b border-pink-vibrant/10 px-6 h-14">
        <div class="flex items-center justify-between h-full">
            <div class="flex items-center space-x-2 text-xs text-cream/70">
                <a href="{{ route('courses.index') }}" wire:navigate class="hover:text-pink-vibrant transition">Cursos</a>
                <span>/</span>
                <a href="{{ route('courses.show', $lesson->module->course) }}" wire:navigate class="hover:text-pink-vibrant transition">{{ $lesson->module->course->title }}</a>
                <span>/</span>
                <span class="text-cream">{{ $lesson->title }}</span>
            </div>
            <div class="text-cream font-display text-base">
                {{ $lesson->title }}
            </div>
        </div>
    </div>

    <!-- Video Player Section - calc(100vh - navbar height) -->
    <div class="fixed top-14 left-0 right-0 bg-black z-10" style="height: calc(100vh - 3.5rem);">
        @if($lesson->video_type === 'youtube' && $lesson->youtube_id)
            <div class="w-full h-full">
                <iframe
                    src="https://www.youtube.com/embed/{{ $lesson->youtube_id }}?rel=0&modestbranding=1"
                    title="{{ $lesson->title }}"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                    class="w-full h-full">
                </iframe>
            </div>
        @elseif($lesson->video_type === 'local' && $lesson->video_path)
            <x-local-video :lesson="$lesson" :title="$lesson->title" />
        @else
            <div class="h-full flex items-center justify-center">
                <div class="text-center">
                    <svg class="w-16 h-16 text-cream/30 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-cream/70">Video no disponible</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Lesson Info & Content Below Video - Hidden on mobile -->
    <div class="hidden md:block relative z-20 bg-purple-darkest" style="margin-top: 100vh;">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <div class="card-premium mb-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h1 class="font-display text-2xl md:text-3xl text-cream mb-2">
                            {{ $lesson->title }}
                        </h1>
                        <p class="text-cream/70 text-sm">
                            Módulo: {{ $lesson->module->title }}
                        </p>
                    </div>

                    @if($lesson->is_trial)
                        <span class="px-3 py-1 bg-green-500/20 text-green-400 text-sm rounded-full ml-4">
                            Gratis
                        </span>
                    @endif
                </div>

                @if($lesson->description)
                    <div class="prose prose-invert max-w-none">
                        <p class="text-cream/80 leading-relaxed">{{ $lesson->description }}</p>
                    </div>
                @endif
            </div>

            <!-- Like Button -->
            <div class="mb-6">
                <livewire:student.like-button :lesson="$lesson" :key="'like-'.$lesson->id" />
            </div>

            <!-- Navigation Buttons -->
            <div class="flex items-center justify-between gap-4">
                @if($previousLesson)
                    <a href="{{ route('lessons.show', $previousLesson) }}" wire:navigate class="btn-secondary flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Anterior
                    </a>
                @else
                    <div></div>
                @endif

                @if($nextLesson)
                    <a href="{{ route('lessons.show', $nextLesson) }}" wire:navigate class="btn-primary flex items-center ml-auto">
                        Siguiente
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Module Lessons -->
            <div class="card-premium sticky top-4">
                <h3 class="font-display text-lg text-cream mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-pink-vibrant" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ $lesson->module->title }}
                </h3>

                <div class="space-y-2 max-h-96 overflow-y-auto">
                    @foreach($lesson->module->lessons as $moduleLesson)
                        @php
                            $canAccess = $moduleLesson->isAccessibleBy(auth()->user());
                            $isCurrent = $moduleLesson->id === $lesson->id;
                        @endphp

                        <a
                            href="{{ $canAccess ? route('lessons.show', $moduleLesson) : '#' }}"
                            wire:navigate
                            class="flex items-center p-3 rounded-lg {{ $isCurrent ? 'bg-pink-vibrant/20 border border-pink-vibrant' : ($canAccess ? 'hover:bg-purple-deep/50' : 'opacity-50 cursor-not-allowed') }} transition">
                            <div class="w-8 h-8 rounded-lg {{ $isCurrent ? 'bg-pink-vibrant' : 'bg-purple-deep' }} flex items-center justify-center mr-3 flex-shrink-0">
                                @if($canAccess)
                                    @if($isCurrent)
                                        <svg class="w-4 h-4 text-cream" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-cream/70" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"></path>
                                        </svg>
                                    @endif
                                @else
                                    <svg class="w-4 h-4 text-cream/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="text-sm {{ $isCurrent ? 'text-pink-vibrant font-bold' : 'text-cream' }} truncate">
                                    {{ $moduleLesson->title }}
                                </p>
                                @if($moduleLesson->is_trial)
                                    <span class="text-xs text-green-400">Gratis</span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-4 pt-4 border-t border-pink-vibrant/20">
                    <a href="{{ route('courses.show', $lesson->module->course) }}" wire:navigate class="text-pink-vibrant hover:text-pink-light text-sm flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver al curso
                    </a>
                </div>
            </div>
        </div>
    </div>

            <!-- Comments Section -->
            <div class="mt-8">
                <div class="card-premium">
                    <livewire:student.comment-section :lesson="$lesson" :key="'comments-'.$lesson->id" />
                </div>
            </div>
        </div>
    </div>
</div>
