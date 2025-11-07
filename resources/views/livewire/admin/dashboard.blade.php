<div class="pb-20 lg:pb-0">
    <x-slot name="header">
        Dashboard Administrativo
    </x-slot>

    <!-- Stats Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-8 bg-white">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6 mb-4 md:mb-8">
            <div class="bg-white rounded-xl md:rounded-2xl p-3 md:p-6 border border-gray-light/50 shadow-sm hover:shadow-md hover:border-purple-primary/30 transition-all duration-300">
                <div class="flex items-center justify-between mb-1 md:mb-2">
                    <h3 class="text-gray-dark text-xs md:text-sm font-medium">Estudiantes</h3>
                    <svg class="w-5 md:w-8 h-5 md:h-8 text-purple-primary/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div class="text-xl md:text-3xl font-black text-black">{{ $stats['total_students'] }}</div>
                <div class="text-xs text-gray-dark mt-0.5 md:mt-1 hidden md:block">{{ $stats['premium_students'] }} con acceso</div>
            </div>

            <div class="bg-white rounded-xl md:rounded-2xl p-3 md:p-6 border border-gray-light/50 shadow-sm hover:shadow-md hover:border-purple-primary/30 transition-all duration-300">
                <div class="flex items-center justify-between mb-1 md:mb-2">
                    <h3 class="text-gray-dark text-xs md:text-sm font-medium">Solicitudes</h3>
                    <svg class="w-5 md:w-8 h-5 md:h-8 text-purple-light/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="text-xl md:text-3xl font-black text-purple-primary">{{ $stats['pending_requests'] }}</div>
                <a href="{{ route('admin.users.index') }}" wire:navigate class="text-xs text-purple-primary hover:text-purple-dark hover:underline mt-0.5 md:mt-1 inline-block font-medium hidden md:inline-block">Ver todas →</a>
            </div>

            <div class="bg-white rounded-xl md:rounded-2xl p-3 md:p-6 border border-gray-light/50 shadow-sm hover:shadow-md hover:border-purple-primary/30 transition-all duration-300">
                <div class="flex items-center justify-between mb-1 md:mb-2">
                    <h3 class="text-gray-dark text-xs md:text-sm font-medium">Cursos</h3>
                    <svg class="w-5 md:w-8 h-5 md:h-8 text-purple-primary/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div class="text-xl md:text-3xl font-black text-black">{{ $stats['total_courses'] }}</div>
                <div class="text-xs text-gray-dark mt-0.5 md:mt-1 hidden md:block">{{ $stats['published_courses'] }} publicados</div>
            </div>

            <div class="bg-white rounded-xl md:rounded-2xl p-3 md:p-6 border border-gray-light/50 shadow-sm hover:shadow-md hover:border-purple-primary/30 transition-all duration-300">
                <div class="flex items-center justify-between mb-1 md:mb-2">
                    <h3 class="text-gray-dark text-xs md:text-sm font-medium">Lecciones</h3>
                    <svg class="w-5 md:w-8 h-5 md:h-8 text-purple-primary/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="text-xl md:text-3xl font-black text-black">{{ $stats['total_lessons'] }}</div>
                <div class="text-xs text-gray-dark mt-0.5 md:mt-1 hidden md:block">{{ $stats['total_comments'] }} comentarios</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-8">
            <!-- Pending Access Requests -->
            <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-8 border border-gray-light/50 shadow-sm hover:shadow-md hover:border-purple-primary/20 transition-all duration-300">
                <div class="flex items-center justify-between mb-4 md:mb-6">
                    <h2 class="font-display text-base md:text-xl text-black font-bold flex items-center">
                        <svg class="w-5 md:w-6 h-5 md:h-6 mr-2 text-purple-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                        <span class="hidden md:inline">Solicitudes Pendientes</span>
                        <span class="md:hidden">Solicitudes</span>
                    </h2>
                    <a href="{{ route('admin.users.index') }}" wire:navigate class="text-purple-primary hover:text-purple-dark text-xs md:text-sm font-medium">
                        Ver →
                    </a>
                </div>

                @if($pendingRequests->isEmpty())
                    <div class="text-center py-8 text-gray-dark">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p>No hay solicitudes pendientes</p>
                    </div>
                @else
                    <div class="space-y-2 md:space-y-3">
                        @foreach($pendingRequests->take(5) as $request)
                            <div class="bg-white border border-purple-primary/10 rounded-lg p-3 md:p-4 flex items-center justify-between shadow-sm hover:shadow-md hover:border-purple-primary/30 hover:bg-purple-ultralight/30 transition-all">
                                <div class="flex items-center flex-1 min-w-0">
                                    <div class="w-8 md:w-10 h-8 md:h-10 rounded-full bg-gradient-to-br from-purple-primary to-purple-light flex items-center justify-center text-white font-bold mr-2 md:mr-3 shadow-sm flex-shrink-0">
                                        {{ strtoupper(substr($request->user->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-black font-medium text-sm md:text-base truncate">{{ $request->user->name }}</p>
                                        <p class="text-gray-dark text-xs md:text-sm truncate">{{ $request->user->email }}</p>
                                        <p class="text-gray-medium text-xs hidden md:block">{{ $request->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('admin.users.index') }}" wire:navigate class="px-3 md:px-4 py-1.5 md:py-2 bg-purple-primary hover:bg-purple-dark text-white font-medium text-xs md:text-sm rounded-lg shadow-sm hover:shadow transition-colors ml-2">
                                    Revisar
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Recent Comments -->
            <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-8 border border-gray-light/50 shadow-sm hover:shadow-md hover:border-purple-primary/20 transition-all duration-300">
                <div class="flex items-center justify-between mb-4 md:mb-6">
                    <h2 class="font-display text-base md:text-xl text-black font-bold flex items-center">
                        <svg class="w-5 md:w-6 h-5 md:h-6 mr-2 text-purple-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <span class="hidden md:inline">Comentarios Recientes</span>
                        <span class="md:hidden">Comentarios</span>
                    </h2>
                    <a href="{{ route('admin.comments.index') }}" wire:navigate class="text-purple-primary hover:text-purple-dark text-xs md:text-sm font-medium">
                        Ver →
                    </a>
                </div>

                @if($recentComments->isEmpty())
                    <div class="text-center py-8 text-gray-dark">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <p>No hay comentarios aún</p>
                    </div>
                @else
                    <div class="space-y-2 md:space-y-3 max-h-96 overflow-y-auto">
                        @foreach($recentComments->take(5) as $comment)
                            <div class="bg-white border border-purple-primary/10 rounded-lg p-3 md:p-4 shadow-sm hover:shadow-md hover:border-purple-primary/30 hover:bg-purple-ultralight/30 transition-all">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex items-center min-w-0 flex-1">
                                        <div class="w-7 md:w-8 h-7 md:h-8 rounded-full bg-gradient-to-br from-purple-primary to-purple-light flex items-center justify-center text-white font-bold text-xs md:text-sm mr-2 shadow-sm flex-shrink-0">
                                            {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-black text-xs md:text-sm font-medium truncate">{{ $comment->user->name }}</p>
                                            <p class="text-gray-medium text-xs">{{ $comment->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-gray-dark text-xs md:text-sm mb-2 line-clamp-2">{{ $comment->body }}</p>
                                <a href="{{ route('lessons.show', $comment->lesson) }}" wire:navigate class="text-purple-primary hover:text-purple-dark hover:underline text-xs font-medium line-clamp-1">
                                    {{ $comment->lesson->module->course->title }} - {{ $comment->lesson->title }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-4 md:mt-8 grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-6 mb-4 md:mb-8">
            <a href="{{ route('admin.courses.index') }}" wire:navigate class="bg-white rounded-xl md:rounded-2xl p-4 md:p-8 border border-gray-light/50 shadow-sm hover:shadow-md hover:border-purple-primary/30 hover:bg-purple-ultralight/20 transition-all duration-300 group text-center">
                <svg class="w-8 md:w-12 h-8 md:h-12 text-purple-primary/70 mx-auto mb-2 md:mb-4 group-hover:text-purple-primary group-hover:scale-110 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <h3 class="font-display text-sm md:text-lg text-black font-bold group-hover:text-purple-primary transition-colors">Cursos</h3>
                <p class="text-gray-dark text-xs md:text-sm mt-1 md:mt-2 hidden md:block">Crear, editar y organizar cursos</p>
            </a>

            <a href="{{ route('admin.users.index') }}" wire:navigate class="bg-white rounded-xl md:rounded-2xl p-4 md:p-8 border border-gray-light/50 shadow-sm hover:shadow-md hover:border-purple-primary/30 hover:bg-purple-ultralight/20 transition-all duration-300 group text-center">
                <svg class="w-8 md:w-12 h-8 md:h-12 text-purple-primary/70 mx-auto mb-2 md:mb-4 group-hover:text-purple-primary group-hover:scale-110 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <h3 class="font-display text-sm md:text-lg text-black font-bold group-hover:text-purple-primary transition-colors">Usuarios</h3>
                <p class="text-gray-dark text-xs md:text-sm mt-1 md:mt-2 hidden md:block">Administrar estudiantes y permisos</p>
            </a>

            <a href="{{ route('admin.instructors.index') }}" wire:navigate class="bg-white rounded-xl md:rounded-2xl p-4 md:p-8 border border-gray-light/50 shadow-sm hover:shadow-md hover:border-purple-primary/30 hover:bg-purple-ultralight/20 transition-all duration-300 group text-center">
                <svg class="w-8 md:w-12 h-8 md:h-12 text-purple-primary/70 mx-auto mb-2 md:mb-4 group-hover:text-purple-primary group-hover:scale-110 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <h3 class="font-display text-sm md:text-lg text-black font-bold group-hover:text-purple-primary transition-colors">Profesores</h3>
                <p class="text-gray-dark text-xs md:text-sm mt-1 md:mt-2 hidden md:block">Gestionar instructores de baile</p>
            </a>

            <a href="{{ route('admin.comments.index') }}" wire:navigate class="bg-white rounded-xl md:rounded-2xl p-4 md:p-8 border border-gray-light/50 shadow-sm hover:shadow-md hover:border-purple-primary/30 hover:bg-purple-ultralight/20 transition-all duration-300 group text-center">
                <svg class="w-8 md:w-12 h-8 md:h-12 text-purple-primary/70 mx-auto mb-2 md:mb-4 group-hover:text-purple-primary group-hover:scale-110 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                </svg>
                <h3 class="font-display text-sm md:text-lg text-black font-bold group-hover:text-purple-primary transition-colors">Comentarios</h3>
                <p class="text-gray-dark text-xs md:text-sm mt-1 md:mt-2 hidden md:block">Revisar y gestionar comentarios</p>
            </a>
        </div>
    </div>
</div>
