<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Welcome Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Welcome, {{ '@'.strtolower(str_replace(' ', '', auth()->user()->name)) }}!</h1>

        <!-- Stats Summary -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <div class="text-2xl font-bold text-purple-600">{{ $stats['accessible_courses'] }}</div>
                <div class="text-sm text-gray-600">Cursos Disponibles</div>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <div class="text-2xl font-bold text-purple-600">{{ $stats['total_comments'] }}</div>
                <div class="text-sm text-gray-600">Comentarios</div>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <div class="text-2xl font-bold text-purple-600">{{ $stats['total_likes'] }}</div>
                <div class="text-sm text-gray-600">Me Gusta</div>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <div class="text-2xl font-bold {{ $stats['has_access'] ? 'text-green-600' : 'text-gray-400' }}">
                    {{ $stats['has_access'] ? 'Premium' : 'Trial' }}
                </div>
                <div class="text-sm text-gray-600">Nivel de Acceso</div>
            </div>
        </div>
    </div>

    <!-- Access Status Banner -->
    @if(!$stats['has_access'])
        <div class="mb-8 card-premium bg-gradient-card border-2 border-pink-vibrant/30">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-3">
                        <svg class="w-6 h-6 text-pink-vibrant mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <h3 class="font-display text-xl text-cream">Desbloquea Todo el Contenido</h3>
                    </div>
                    <p class="text-cream/80 mb-4">
                        Actualmente tienes acceso a lecciones gratuitas. Solicita acceso completo para disfrutar de todos los cursos premium.
                    </p>
                    <a href="{{ route('request-access') }}" wire:navigate class="btn-primary inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                        </svg>
                        Solicitar Acceso Completo
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="mb-8 card-premium bg-green-500/10 border border-green-500/30">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="font-display text-lg text-green-400">Acceso Completo Activo</h3>
                    <p class="text-cream/70 text-sm">Tienes acceso ilimitado a todo el contenido de la plataforma</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Free Lessons Section -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Clases Gratuitas</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($trialLessons as $lesson)
                <a href="{{ route('lessons.show', $lesson) }}" wire:navigate class="group bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition">
                    <!-- Thumbnail Placeholder -->
                    <div class="aspect-video bg-gradient-to-br from-purple-500 to-pink-500 relative">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <svg class="w-12 h-12 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="absolute top-2 left-2 px-2 py-1 bg-green-500 text-white text-xs font-semibold rounded">Gratis</span>
                    </div>

                    <!-- Content -->
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 group-hover:text-purple-600 transition line-clamp-2 mb-2">
                            {{ $lesson->title }}
                        </h3>
                        <p class="text-sm text-gray-600 mb-2">{{ $lesson->module->course->title }}</p>
                        <div class="flex items-center text-xs text-gray-500">
                            <svg class="w-4 h-4 mr-1 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $lesson->likes_count }} me gusta
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-600">No hay lecciones gratuitas disponibles</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('courses.index') }}" wire:navigate class="inline-flex items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition">
                Explorar Todos los Cursos
            </a>
        </div>
    </div>

    <!-- Recent Comments -->
    @if($recentComments->isNotEmpty())
        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Mis Comentarios Recientes</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($recentComments->take(3) as $comment)
                    <a href="{{ route('lessons.show', $comment->lesson) }}" wire:navigate class="block bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition">
                        <p class="text-gray-700 text-sm mb-2 line-clamp-3">{{ $comment->body }}</p>
                        <p class="text-purple-600 text-sm font-medium hover:underline">{{ $comment->lesson->title }}</p>
                        <p class="text-gray-500 text-xs mt-1">{{ $comment->created_at->diffForHumans() }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
