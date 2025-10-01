<div>
    <x-slot name="header">
        Dashboard Administrativo
    </x-slot>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="card-premium">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-cream/70 text-sm">Estudiantes</h3>
                <svg class="w-8 h-8 text-pink-vibrant/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <div class="text-3xl font-bold text-cream">{{ $stats['total_students'] }}</div>
            <div class="text-xs text-cream/60 mt-1">{{ $stats['premium_students'] }} con acceso completo</div>
        </div>

        <div class="card-premium">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-cream/70 text-sm">Solicitudes Pendientes</h3>
                <svg class="w-8 h-8 text-orange-400/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="text-3xl font-bold text-orange-400">{{ $stats['pending_requests'] }}</div>
            <a href="{{ route('admin.access-requests.index') }}" wire:navigate class="text-xs text-pink-vibrant hover:underline mt-1 inline-block">Ver todas →</a>
        </div>

        <div class="card-premium">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-cream/70 text-sm">Cursos</h3>
                <svg class="w-8 h-8 text-pink-vibrant/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <div class="text-3xl font-bold text-cream">{{ $stats['total_courses'] }}</div>
            <div class="text-xs text-cream/60 mt-1">{{ $stats['published_courses'] }} publicados</div>
        </div>

        <div class="card-premium">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-cream/70 text-sm">Lecciones</h3>
                <svg class="w-8 h-8 text-pink-vibrant/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="text-3xl font-bold text-cream">{{ $stats['total_lessons'] }}</div>
            <div class="text-xs text-cream/60 mt-1">{{ $stats['total_comments'] }} comentarios</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Pending Access Requests -->
        <div class="card-premium">
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-display text-xl text-cream flex items-center">
                    <svg class="w-6 h-6 mr-2 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    Solicitudes Pendientes
                </h2>
                <a href="{{ route('admin.access-requests.index') }}" wire:navigate class="text-pink-vibrant hover:text-pink-light text-sm">
                    Ver todas →
                </a>
            </div>

            @if($pendingRequests->isEmpty())
                <div class="text-center py-8 text-cream/70">
                    <svg class="w-16 h-16 mx-auto mb-4 text-cream/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p>No hay solicitudes pendientes</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($pendingRequests->take(5) as $request)
                        <div class="bg-purple-deeper rounded-lg p-4 flex items-center justify-between">
                            <div class="flex items-center flex-1">
                                <div class="w-10 h-10 rounded-full bg-gradient-pink flex items-center justify-center text-cream font-bold mr-3">
                                    {{ strtoupper(substr($request->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-cream font-medium">{{ $request->user->name }}</p>
                                    <p class="text-cream/60 text-sm">{{ $request->user->email }}</p>
                                    <p class="text-cream/50 text-xs">{{ $request->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.access-requests.index') }}" wire:navigate class="btn-primary text-sm px-4 py-2">
                                Revisar
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Recent Comments -->
        <div class="card-premium">
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-display text-xl text-cream flex items-center">
                    <svg class="w-6 h-6 mr-2 text-pink-vibrant" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    Comentarios Recientes
                </h2>
                <a href="{{ route('admin.comments.index') }}" wire:navigate class="text-pink-vibrant hover:text-pink-light text-sm">
                    Ver todos →
                </a>
            </div>

            @if($recentComments->isEmpty())
                <div class="text-center py-8 text-cream/70">
                    <svg class="w-16 h-16 mx-auto mb-4 text-cream/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <p>No hay comentarios aún</p>
                </div>
            @else
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @foreach($recentComments->take(5) as $comment)
                        <div class="bg-purple-deeper rounded-lg p-4">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-gradient-pink flex items-center justify-center text-cream font-bold text-sm mr-2">
                                        {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-cream text-sm font-medium">{{ $comment->user->name }}</p>
                                        <p class="text-cream/50 text-xs">{{ $comment->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                            <p class="text-cream/80 text-sm mb-2 line-clamp-2">{{ $comment->body }}</p>
                            <a href="{{ route('lessons.show', $comment->lesson) }}" wire:navigate class="text-pink-vibrant hover:underline text-xs">
                                {{ $comment->lesson->module->course->title }} - {{ $comment->lesson->title }}
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('admin.courses.index') }}" wire:navigate class="card-premium hover-lift group text-center py-8">
            <svg class="w-12 h-12 text-pink-vibrant mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <h3 class="font-display text-lg text-cream group-hover:text-pink-vibrant transition">Gestionar Cursos</h3>
            <p class="text-cream/60 text-sm mt-2">Crear, editar y organizar cursos</p>
        </a>

        <a href="{{ route('admin.users.index') }}" wire:navigate class="card-premium hover-lift group text-center py-8">
            <svg class="w-12 h-12 text-pink-vibrant mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <h3 class="font-display text-lg text-cream group-hover:text-pink-vibrant transition">Gestionar Usuarios</h3>
            <p class="text-cream/60 text-sm mt-2">Administrar estudiantes y permisos</p>
        </a>

        <a href="{{ route('admin.comments.index') }}" wire:navigate class="card-premium hover-lift group text-center py-8">
            <svg class="w-12 h-12 text-pink-vibrant mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
            </svg>
            <h3 class="font-display text-lg text-cream group-hover:text-pink-vibrant transition">Moderar Comentarios</h3>
            <p class="text-cream/60 text-sm mt-2">Revisar y gestionar comentarios</p>
        </a>
    </div>
</div>
