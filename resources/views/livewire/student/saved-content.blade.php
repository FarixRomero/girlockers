<div class="pb-20 lg:pb-0">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-black mb-2">Guardados</h1>
            <p class="text-gray-dark">Tus clases favoritas e historial de visualización</p>
        </div>

        <!-- Tabs -->
        <div class="mb-6 border-b border-gray-light">
            <div class="flex space-x-4">
                <button
                    wire:click="$set('tab', 'favoritos')"
                    class="pb-3 px-2 font-medium text-sm transition-colors border-b-2 {{ $tab === 'favoritos' ? 'text-purple-primary border-purple-primary' : 'text-gray-dark border-transparent hover:text-purple-primary' }}">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <span>Favoritos ({{ $savedLessons->count() }})</span>
                    </div>
                </button>
                <button
                    wire:click="$set('tab', 'historial')"
                    class="pb-3 px-2 font-medium text-sm transition-colors border-b-2 {{ $tab === 'historial' ? 'text-purple-primary border-purple-primary' : 'text-gray-dark border-transparent hover:text-purple-primary' }}">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Historial ({{ $watchedLessons->count() }})</span>
                    </div>
                </button>
            </div>
        </div>

        <!-- Favoritos Tab Content -->
        @if($tab === 'favoritos')
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 md:gap-6">
                @forelse($savedLessons as $lesson)
                    <div class="group rounded-lg overflow-hidden">
                        <a href="{{ route('lessons.show', $lesson) }}" wire:navigate class="block">
                            <div class="aspect-video relative rounded-lg overflow-hidden">
                                @if($lesson->thumbnail_url)
                                    <img src="{{ $lesson->thumbnail_url }}" alt="{{ $lesson->title }}" class="w-full h-full object-cover">
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

                                <!-- Tags on image -->
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
                                    @if($lesson->duration_minutes && $lesson->video_type !== 'youtube')
                                        <span class="px-1.5 py-0.5 bg-black/80 text-white text-[10px] font-bold uppercase rounded">{{ $lesson->duration_minutes }} MIN</span>
                                    @endif
                                </div>

                                <!-- Heart icon (top-right) -->
                                <div class="absolute top-2 right-2 p-1.5 bg-black/50 rounded-full">
                                    <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path>
                                    </svg>
                                </div>
                            </div>

                            <div class="pt-2">
                                <h3 class="text-gray-900 font-semibold text-sm line-clamp-2 mb-1">{{ $lesson->title }}</h3>
                                @if($lesson->instructor)
                                    <p class="text-gray-500 text-xs font-normal">{{ $lesson->instructor->name }}</p>
                                @endif
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 bg-white rounded-xl border border-gray-light/50">
                        <svg class="mx-auto h-12 w-12 text-gray-light mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <p class="text-lg text-gray-dark font-medium">No tienes clases favoritas</p>
                        <p class="mt-2 text-sm text-gray-medium">Dale "me gusta" a tus clases favoritas para verlas aquí</p>
                        <a href="{{ route('courses.index') }}" wire:navigate class="mt-4 inline-flex items-center px-4 py-2 bg-purple-primary hover:bg-purple-dark text-white font-semibold rounded-lg shadow-sm hover:shadow transition-all">
                            Explorar Cursos
                        </a>
                    </div>
                @endforelse
            </div>
        @endif

        <!-- Historial Tab Content -->
        @if($tab === 'historial')
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 md:gap-6">
                @forelse($watchedLessons as $lesson)
                    <div class="group rounded-lg overflow-hidden">
                        <a href="{{ route('lessons.show', $lesson) }}" wire:navigate class="block">
                            <div class="aspect-video relative rounded-lg overflow-hidden">
                                @if($lesson->thumbnail_url)
                                    <img src="{{ $lesson->thumbnail_url }}" alt="{{ $lesson->title }}" class="w-full h-full object-cover">
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

                                <!-- Tags on image -->
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
                                    @if($lesson->duration_minutes && $lesson->video_type !== 'youtube')
                                        <span class="px-1.5 py-0.5 bg-black/80 text-white text-[10px] font-bold uppercase rounded">{{ $lesson->duration_minutes }} MIN</span>
                                    @endif
                                </div>

                                <!-- Watch indicator (top-right) -->
                                <div class="absolute top-2 right-2 bg-black/60 backdrop-blur-sm rounded-full p-1.5">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </div>
                            </div>

                            <div class="pt-2">
                                <h3 class="text-gray-900 font-semibold text-sm line-clamp-2 mb-1">{{ $lesson->title }}</h3>
                                @if($lesson->instructor)
                                    <p class="text-gray-500 text-xs font-normal">{{ $lesson->instructor->name }}</p>
                                @endif
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 bg-white rounded-xl border border-gray-light/50">
                        <svg class="mx-auto h-12 w-12 text-gray-light mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-lg text-gray-dark font-medium">No hay lecciones en tu historial</p>
                        <p class="mt-2 text-sm text-gray-medium">Las lecciones que veas aparecerán aquí</p>
                        <a href="{{ route('courses.index') }}" wire:navigate class="mt-4 inline-flex items-center px-4 py-2 bg-purple-primary hover:bg-purple-dark text-white font-semibold rounded-lg shadow-sm hover:shadow transition-all">
                            Explorar Cursos
                        </a>
                    </div>
                @endforelse
            </div>
        @endif
    </div>
</div>
