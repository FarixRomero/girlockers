<div>
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

    <div class="mt-8 flex justify-center">
        <div class="container card-premium max-w-3xl mx-auto">
            <livewire:student.comment-section :lesson="$lesson" :key="'comments-' . $lesson->id" />
        </div>
    </div>
</div>
