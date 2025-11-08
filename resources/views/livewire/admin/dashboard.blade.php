<div class="min-h-screen bg-gray-50 pb-20 lg:pb-0">
    <!-- Header estilo Instagram -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-5xl mx-auto px-4 py-3 md:py-4">
            <h1 class="text-xl md:text-2xl font-semibold text-gray-900">Dashboard</h1>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-5xl mx-auto px-4 py-4 md:py-6">
        <!-- Stats Grid estilo Instagram Stories -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-6 md:mb-8">
            <!-- Estudiantes -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 md:p-6 hover:shadow-sm transition cursor-pointer">
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 md:w-16 h-12 md:h-16 rounded-full bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-600 p-0.5 mb-2 md:mb-3">
                        <div class="w-full h-full rounded-full bg-white flex items-center justify-center">
                            <svg class="w-6 md:w-8 h-6 md:h-8 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">{{ $stats['total_students'] }}</div>
                    <div class="text-xs md:text-sm text-gray-500 font-medium">Estudiantes</div>
                    <div class="text-xs text-gray-400 mt-0.5 md:mt-1">{{ $stats['premium_students'] }} premium</div>
                </div>
            </div>

            <!-- Solicitudes -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 md:p-6 hover:shadow-sm transition cursor-pointer">
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 md:w-16 h-12 md:h-16 rounded-full bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-600 p-0.5 mb-2 md:mb-3">
                        <div class="w-full h-full rounded-full bg-white flex items-center justify-center">
                            <svg class="w-6 md:w-8 h-6 md:h-8 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">{{ $stats['pending_requests'] }}</div>
                    <div class="text-xs md:text-sm text-gray-500 font-medium">Pendientes</div>
                    <a href="{{ route('admin.users.index') }}" wire:navigate class="text-xs text-blue-600 font-semibold mt-0.5 md:mt-1 hover:text-blue-700">Ver todas</a>
                </div>
            </div>

            <!-- Cursos -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 md:p-6 hover:shadow-sm transition cursor-pointer">
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 md:w-16 h-12 md:h-16 rounded-full bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-600 p-0.5 mb-2 md:mb-3">
                        <div class="w-full h-full rounded-full bg-white flex items-center justify-center">
                            <svg class="w-6 md:w-8 h-6 md:h-8 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">{{ $stats['total_courses'] }}</div>
                    <div class="text-xs md:text-sm text-gray-500 font-medium">Cursos</div>
                    <div class="text-xs text-gray-400 mt-0.5 md:mt-1">{{ $stats['published_courses'] }} publicados</div>
                </div>
            </div>

            <!-- Lecciones -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 md:p-6 hover:shadow-sm transition cursor-pointer">
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 md:w-16 h-12 md:h-16 rounded-full bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-600 p-0.5 mb-2 md:mb-3">
                        <div class="w-full h-full rounded-full bg-white flex items-center justify-center">
                            <svg class="w-6 md:w-8 h-6 md:h-8 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">{{ $stats['total_lessons'] }}</div>
                    <div class="text-xs md:text-sm text-gray-500 font-medium">Lecciones</div>
                    <div class="text-xs text-gray-400 mt-0.5 md:mt-1">{{ $stats['total_comments'] }} comentarios</div>
                </div>
            </div>
        </div>

        <!-- Feed Section -->
        <div class="space-y-4">
            <!-- Pending Requests Card -->
            @if($pendingRequests->isNotEmpty())
            <div class="bg-white border border-gray-200 rounded-lg">
                <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-gray-900">Solicitudes de Acceso</h2>
                    <a href="{{ route('admin.users.index') }}" wire:navigate class="text-sm text-blue-600 font-semibold hover:text-blue-700">Ver todas</a>
                </div>
                <div class="p-4">
                    <div class="space-y-3">
                        @foreach($pendingRequests->take(5) as $request)
                        <div class="flex items-center justify-between py-2">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-600 p-0.5">
                                    <div class="w-full h-full rounded-full bg-gray-200 flex items-center justify-center text-gray-700 font-semibold text-sm">
                                        {{ strtoupper(substr($request->user->name, 0, 1)) }}
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $request->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $request->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.users.index') }}" wire:navigate class="px-4 py-1.5 text-sm font-semibold text-blue-600 hover:text-blue-700">
                                Revisar
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Comments Card -->
            @if($recentComments->isNotEmpty())
            <div class="bg-white border border-gray-200 rounded-lg">
                <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-gray-900">Comentarios Recientes</h2>
                    <a href="{{ route('admin.comments.index') }}" wire:navigate class="text-sm text-blue-600 font-semibold hover:text-blue-700">Ver todos</a>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($recentComments->take(5) as $comment)
                    <div class="p-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-600 p-0.5 flex-shrink-0">
                                <div class="w-full h-full rounded-full bg-gray-200 flex items-center justify-center text-gray-700 font-semibold text-xs">
                                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-2">
                                    <p class="text-sm font-semibold text-gray-900">{{ $comment->user->name }}</p>
                                    <span class="text-xs text-gray-400">·</span>
                                    <p class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                                </div>
                                <p class="text-sm text-gray-900 mt-1">{{ $comment->body }}</p>
                                <a href="{{ route('lessons.show', $comment->lesson) }}" wire:navigate class="text-xs text-gray-500 hover:text-gray-700 mt-1 inline-block">
                                    {{ $comment->lesson->title }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Quick Actions Grid -->
        <div class="mt-6 md:mt-8 grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
            <a href="{{ route('admin.courses.index') }}" wire:navigate class="bg-white border border-gray-200 rounded-lg p-4 md:p-6 hover:shadow-sm transition group">
                <div class="flex items-center space-x-3 md:space-x-4">
                    <div class="w-10 md:w-12 h-10 md:h-12 rounded-full bg-gray-100 flex items-center justify-center group-hover:bg-gray-200 transition flex-shrink-0">
                        <svg class="w-5 md:w-6 h-5 md:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm md:text-base font-semibold text-gray-900">Administrar Cursos</h3>
                        <p class="text-xs text-gray-500">Crear y editar cursos</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.users.index') }}" wire:navigate class="bg-white border border-gray-200 rounded-lg p-4 md:p-6 hover:shadow-sm transition group">
                <div class="flex items-center space-x-3 md:space-x-4">
                    <div class="w-10 md:w-12 h-10 md:h-12 rounded-full bg-gray-100 flex items-center justify-center group-hover:bg-gray-200 transition flex-shrink-0">
                        <svg class="w-5 md:w-6 h-5 md:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm md:text-base font-semibold text-gray-900">Gestionar Usuarios</h3>
                        <p class="text-xs text-gray-500">Estudiantes y accesos</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.instructors.index') }}" wire:navigate class="bg-white border border-gray-200 rounded-lg p-4 md:p-6 hover:shadow-sm transition group">
                <div class="flex items-center space-x-3 md:space-x-4">
                    <div class="w-10 md:w-12 h-10 md:h-12 rounded-full bg-gray-100 flex items-center justify-center group-hover:bg-gray-200 transition flex-shrink-0">
                        <svg class="w-5 md:w-6 h-5 md:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm md:text-base font-semibold text-gray-900">Profesores</h3>
                        <p class="text-xs text-gray-500">Gestionar instructores</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.comments.index') }}" wire:navigate class="bg-white border border-gray-200 rounded-lg p-4 md:p-6 hover:shadow-sm transition group">
                <div class="flex items-center space-x-3 md:space-x-4">
                    <div class="w-10 md:w-12 h-10 md:h-12 rounded-full bg-gray-100 flex items-center justify-center group-hover:bg-gray-200 transition flex-shrink-0">
                        <svg class="w-5 md:w-6 h-5 md:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm md:text-base font-semibold text-gray-900">Moderación</h3>
                        <p class="text-xs text-gray-500">Revisar comentarios</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.landing-config.index') }}" wire:navigate class="bg-white border border-gray-200 rounded-lg p-4 md:p-6 hover:shadow-sm transition group">
                <div class="flex items-center space-x-3 md:space-x-4">
                    <div class="w-10 md:w-12 h-10 md:h-12 rounded-full bg-gray-100 flex items-center justify-center group-hover:bg-gray-200 transition flex-shrink-0">
                        <svg class="w-5 md:w-6 h-5 md:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm md:text-base font-semibold text-gray-900">Gestionar Landing</h3>
                        <p class="text-xs text-gray-500">Configurar landing page</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
