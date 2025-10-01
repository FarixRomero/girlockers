<div>
    <x-slot name="header">
        ¡Bienvenida, {{ auth()->user()->name }}!
    </x-slot>

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

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="card-premium text-center">
            <div class="text-4xl mb-2">📚</div>
            <div class="text-3xl font-bold text-pink-vibrant mb-1">{{ $stats['accessible_courses'] }}</div>
            <div class="text-cream/70 text-sm">Cursos Disponibles</div>
        </div>

        <div class="card-premium text-center">
            <div class="text-4xl mb-2">💬</div>
            <div class="text-3xl font-bold text-pink-vibrant mb-1">{{ $stats['total_comments'] }}</div>
            <div class="text-cream/70 text-sm">Comentarios</div>
        </div>

        <div class="card-premium text-center">
            <div class="text-4xl mb-2">❤️</div>
            <div class="text-3xl font-bold text-pink-vibrant mb-1">{{ $stats['total_likes'] }}</div>
            <div class="text-cream/70 text-sm">Me Gusta</div>
        </div>

        <div class="card-premium text-center">
            <div class="text-4xl mb-2">{{ $stats['has_access'] ? '🔓' : '🔒' }}</div>
            <div class="text-3xl font-bold text-pink-vibrant mb-1">{{ $stats['has_access'] ? 'Premium' : 'Trial' }}</div>
            <div class="text-cream/70 text-sm">Nivel de Acceso</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Trial Lessons -->
            <div>
                <h2 class="font-display text-2xl text-cream mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-pink-vibrant" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Lecciones Gratuitas
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($trialLessons as $lesson)
                        <a href="{{ route('lessons.show', $lesson) }}" wire:navigate class="card-premium hover-lift group">
                            <div class="flex items-start justify-between mb-3">
                                <span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs rounded-full">Gratis</span>
                                <svg class="w-8 h-8 text-pink-vibrant/30 group-hover:text-pink-vibrant transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="font-display text-lg text-cream group-hover:text-pink-vibrant transition mb-2">{{ $lesson->title }}</h3>
                            <p class="text-cream/60 text-sm mb-3">{{ $lesson->module->course->title }}</p>
                            <div class="flex items-center text-cream/50 text-xs">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"></path>
                                </svg>
                                {{ $lesson->likes_count }} me gusta
                            </div>
                        </a>
                    @empty
                        <div class="col-span-2 card-premium text-center py-8">
                            <p class="text-cream/70">No hay lecciones gratuitas disponibles</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-6 text-center">
                    <a href="{{ route('courses.index') }}" wire:navigate class="btn-secondary">
                        Explorar Todos los Cursos
                    </a>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Quick Actions -->
            <div class="card-premium">
                <h3 class="font-display text-lg text-cream mb-4">Acciones Rápidas</h3>
                <div class="space-y-3">
                    <a href="{{ route('courses.index') }}" wire:navigate class="flex items-center p-3 bg-purple-darker rounded-lg hover:bg-purple-deep transition group">
                        <svg class="w-5 h-5 text-pink-vibrant mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span class="text-cream group-hover:text-pink-vibrant transition">Explorar Cursos</span>
                    </a>

                    @if(!$stats['has_access'])
                        <a href="{{ route('request-access') }}" wire:navigate class="flex items-center p-3 bg-purple-darker rounded-lg hover:bg-purple-deep transition group">
                            <svg class="w-5 h-5 text-pink-vibrant mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-cream group-hover:text-pink-vibrant transition">Solicitar Acceso</span>
                        </a>
                    @endif

                    <a href="{{ route('profile') }}" wire:navigate class="flex items-center p-3 bg-purple-darker rounded-lg hover:bg-purple-deep transition group">
                        <svg class="w-5 h-5 text-pink-vibrant mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="text-cream group-hover:text-pink-vibrant transition">Mi Perfil</span>
                    </a>
                </div>
            </div>

            <!-- Recent Comments -->
            @if($recentComments->isNotEmpty())
                <div class="card-premium">
                    <h3 class="font-display text-lg text-cream mb-4">Mis Comentarios Recientes</h3>
                    <div class="space-y-3">
                        @foreach($recentComments as $comment)
                            <a href="{{ route('lessons.show', $comment->lesson) }}" wire:navigate class="block p-3 bg-purple-darker rounded-lg hover:bg-purple-deep transition group">
                                <p class="text-cream/70 text-sm mb-1 line-clamp-2">{{ $comment->body }}</p>
                                <p class="text-pink-vibrant text-xs group-hover:underline">{{ $comment->lesson->title }}</p>
                                <p class="text-cream/50 text-xs mt-1">{{ $comment->created_at->diffForHumans() }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
