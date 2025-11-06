<div class="pb-20 lg:pb-0">
    <x-slot name="header">
        Gestión de Cursos
    </x-slot>

    <!-- Mobile Title -->
    <div class="md:hidden px-4 pt-4 pb-2 bg-white">
        <h1 class="text-xl font-bold text-black">Cursos</h1>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 bg-white">
        <!-- Success/Error Messages -->
        @if(session()->has('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg shadow-sm">
                <p class="text-green-700 flex items-center font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ session('success') }}
                </p>
            </div>
        @endif

        @if(session()->has('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg shadow-sm">
                <p class="text-red-700 flex items-center font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    {{ session('error') }}
                </p>
            </div>
        @endif

        <!-- Stats (Hidden on mobile) -->
        <div class="hidden md:grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-2xl p-6 border border-gray-light/50 shadow-sm hover:shadow-md transition-all duration-300">
                <div class="text-gray-dark text-sm mb-1 font-medium">Total Cursos</div>
                <div class="text-3xl font-black text-black">{{ $stats['total'] }}</div>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-gray-light/50 shadow-sm hover:shadow-md transition-all duration-300">
                <div class="text-gray-dark text-sm mb-1 font-medium">Publicados</div>
                <div class="text-3xl font-black text-green-600">{{ $stats['published'] }}</div>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-gray-light/50 shadow-sm hover:shadow-md transition-all duration-300">
                <div class="text-gray-dark text-sm mb-1 font-medium">Borradores</div>
                <div class="text-3xl font-black text-orange-500">{{ $stats['draft'] }}</div>
            </div>
        </div>

        <!-- Filters & Actions -->
        <div class="bg-gray-ultralight rounded-2xl p-4 md:p-6 border border-gray-light/50 shadow-sm mb-6">
            <!-- Mobile Layout -->
            <div class="flex md:hidden gap-2 items-center">
                <!-- Search (Smaller on mobile) -->
                <div class="flex-1">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Buscar..."
                        class="w-full bg-white border border-purple-primary/20 rounded-lg px-3 py-2 text-sm text-black placeholder-gray-medium focus:outline-none focus:border-purple-primary focus:ring-2 focus:ring-purple-primary/10 transition shadow-sm">
                </div>

                <!-- Create Button -->
                <button wire:click="openCreateModal" class="p-2 bg-purple-primary hover:bg-purple-dark text-white font-bold rounded-lg shadow-sm hover:shadow-md transition-all duration-300 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </button>
            </div>

            <!-- Desktop Layout -->
            <div class="hidden md:flex flex-col md:flex-row gap-4 items-end">
                <!-- Search -->
                <div class="flex-1">
                    <label class="block text-gray-dark text-sm mb-2 font-medium">Buscar</label>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Buscar cursos..."
                        class="w-full bg-white border border-purple-primary/20 rounded-lg px-4 py-3 text-black placeholder-gray-medium focus:outline-none focus:border-purple-primary focus:ring-2 focus:ring-purple-primary/10 transition shadow-sm">
                </div>

                <!-- Level Filter -->
                <div>
                    <label class="block text-gray-dark text-sm mb-2 font-medium">Nivel</label>
                    <select
                        wire:model.live="filterLevel"
                        class="bg-white border border-purple-primary/20 rounded-lg px-4 py-3 text-black focus:outline-none focus:border-purple-primary focus:ring-2 focus:ring-purple-primary/10 transition shadow-sm">
                        <option value="all">Todos los niveles</option>
                        <option value="principiante">Principiante</option>
                        <option value="intermedio">Intermedio</option>
                        <option value="avanzado">Avanzado</option>
                    </select>
                </div>

                <!-- Published Filter -->
                <div>
                    <label class="block text-gray-dark text-sm mb-2 font-medium">Estado</label>
                    <select
                        wire:model.live="filterPublished"
                        class="bg-white border border-purple-primary/20 rounded-lg px-4 py-3 text-black focus:outline-none focus:border-purple-primary focus:ring-2 focus:ring-purple-primary/10 transition shadow-sm">
                        <option value="all">Todos los estados</option>
                        <option value="published">Publicados</option>
                        <option value="draft">Borradores</option>
                    </select>
                </div>

                <!-- Create Button -->
                <button wire:click="openCreateModal" class="px-6 py-3 bg-purple-primary hover:bg-purple-dark text-white font-bold rounded-lg shadow-sm hover:shadow-md transition-all duration-300 whitespace-nowrap">
                    <svg class="w-5 h-5 inline-block mr-2 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nuevo Curso
                </button>
            </div>
        </div>

        <!-- Courses Table (Desktop) -->
        <div class="hidden md:block bg-white rounded-2xl border border-gray-light/50 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-light bg-gray-ultralight">
                            <th class="text-left py-4 px-6 text-gray-dark font-bold text-sm uppercase tracking-wide">Curso</th>
                            <th class="text-left py-4 px-6 text-gray-dark font-bold text-sm uppercase tracking-wide">Instructor</th>
                            <th class="text-left py-4 px-6 text-gray-dark font-bold text-sm uppercase tracking-wide">Nivel</th>
                            <th class="text-left py-4 px-6 text-gray-dark font-bold text-sm uppercase tracking-wide">Contenido</th>
                            <th class="text-left py-4 px-6 text-gray-dark font-bold text-sm uppercase tracking-wide">Estado</th>
                            <th class="text-right py-4 px-6 text-gray-dark font-bold text-sm uppercase tracking-wide">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                            <tr class="border-b border-gray-light/30 hover:bg-purple-ultralight/30 transition-colors" wire:key="course-{{ $course->id }}">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-4">
                                    @if($course->image)
                                        <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->title }}" class="w-20 h-20 object-cover rounded-lg flex-shrink-0 shadow-sm">
                                    @else
                                        <div class="w-20 h-20 bg-gradient-to-br from-purple-primary to-purple-light rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm">
                                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="min-w-0 flex-1">
                                        <p class="text-black font-bold text-base mb-1">{{ $course->title }}</p>
                                        <p class="text-gray-dark text-sm line-clamp-2">{{ $course->description }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                @if($course->instructor)
                                    <div class="flex items-center">
                                        @if($course->instructor->avatar)
                                            <img src="{{ asset('storage/' . $course->instructor->avatar) }}" alt="{{ $course->instructor->name }}" class="w-8 h-8 rounded-full mr-2">
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-purple-primary/20 flex items-center justify-center mr-2">
                                                <svg class="w-4 h-4 text-purple-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        <span class="text-sm text-gray-dark">{{ $course->instructor->name }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-medium text-sm italic">Sin instructor</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 rounded-full text-xs font-bold shadow-sm
                                    {{ $course->level === 'principiante' ? 'bg-green-100 text-green-700 border border-green-200' : '' }}
                                    {{ $course->level === 'intermedio' ? 'bg-orange-100 text-orange-700 border border-orange-200' : '' }}
                                    {{ $course->level === 'avanzado' ? 'bg-red-100 text-red-700 border border-red-200' : '' }}">
                                    {{ ucfirst($course->level) }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-sm text-gray-dark">
                                    <p class="font-medium">{{ $course->modules_count }} módulos</p>
                                    <p class="text-gray-medium">{{ $course->modules->sum('lessons_count') }} lecciones</p>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                @if($course->is_published)
                                    <span class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 text-xs rounded-full font-bold border border-green-200 shadow-sm whitespace-nowrap">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Publicado
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1.5 bg-orange-100 text-orange-700 text-xs rounded-full font-bold border border-orange-200 shadow-sm whitespace-nowrap">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                        </svg>
                                        Borrador
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-end space-x-2">
                                    <a
                                        href="{{ route('admin.courses.modules', $course->id) }}"
                                        wire:navigate
                                        class="px-4 py-2 bg-purple-primary hover:bg-purple-dark text-white rounded-lg transition-all duration-300 font-medium flex items-center shadow-sm hover:shadow"
                                        title="Gestionar módulos">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                        </svg>
                                        Módulos
                                    </a>

                                    <button
                                        wire:click="togglePublished({{ $course->id }})"
                                        class="p-2 hover:bg-purple-ultralight rounded-lg transition"
                                        title="{{ $course->is_published ? 'Despublicar' : 'Publicar' }}">
                                        <svg class="w-5 h-5 {{ $course->is_published ? 'text-green-600' : 'text-gray-medium' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>

                                    <button
                                        wire:click="openEditModal({{ $course->id }})"
                                        class="p-2 text-purple-primary hover:bg-purple-ultralight rounded-lg transition"
                                        title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>

                                    <button
                                        wire:click="deleteCourse({{ $course->id }})"
                                        wire:confirm="¿Estás seguro de eliminar el curso '{{ $course->title }}'?"
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                        title="Eliminar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center">
                                <svg class="w-20 h-20 text-gray-light mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-gray-dark font-medium">No se encontraron cursos</p>
                                <p class="text-gray-medium text-sm mt-2">Comienza creando tu primer curso</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Courses Cards (Mobile) -->
        <div class="md:hidden space-y-4">
            @forelse($courses as $course)
                <div class="bg-white rounded-2xl border border-gray-light/50 shadow-sm overflow-hidden" wire:key="course-mobile-{{ $course->id }}">
                    <!-- Course Image/Header (Clickable) -->
                    <a href="{{ route('admin.courses.modules', $course->id) }}" wire:navigate class="block relative h-32 cursor-pointer">
                        @if($course->image)
                            <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-purple-primary to-purple-light flex items-center justify-center">
                                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                        <!-- Level & Status Badges -->
                        <div class="absolute top-2 right-2 flex gap-2 pointer-events-none">
                            @if($course->is_published)
                                <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full font-bold border border-green-200 shadow-sm">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Publicado
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 bg-orange-100 text-orange-700 text-xs rounded-full font-bold border border-orange-200 shadow-sm">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                    </svg>
                                    Borrador
                                </span>
                            @endif
                        </div>
                    </a>

                    <!-- Course Content -->
                    <div class="p-4">
                        <!-- Title -->
                        <h3 class="text-black font-bold text-lg mb-2">{{ $course->title }}</h3>

                        <!-- Info Grid -->
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <!-- Level -->
                            <div>
                                <p class="text-gray-medium text-xs mb-1">Nivel</p>
                                <span class="px-2 py-1 rounded-full text-xs font-bold inline-block
                                    {{ $course->level === 'principiante' ? 'bg-green-100 text-green-700 border border-green-200' : '' }}
                                    {{ $course->level === 'intermedio' ? 'bg-orange-100 text-orange-700 border border-orange-200' : '' }}
                                    {{ $course->level === 'avanzado' ? 'bg-red-100 text-red-700 border border-red-200' : '' }}">
                                    {{ ucfirst($course->level) }}
                                </span>
                            </div>

                            <!-- Content -->
                            <div>
                                <p class="text-gray-medium text-xs mb-1">Contenido</p>
                                <p class="text-sm text-gray-dark font-medium">{{ $course->modules_count }} módulos</p>
                                <p class="text-xs text-gray-medium">{{ $course->modules->sum('lessons_count') }} lecciones</p>
                            </div>
                        </div>

                        <!-- Instructor -->
                        @if($course->instructor)
                            <div class="flex items-center gap-2 mb-4 pb-4 border-b border-gray-light/50">
                                @if($course->instructor->avatar)
                                    <img src="{{ asset('storage/' . $course->instructor->avatar) }}" alt="{{ $course->instructor->name }}" class="w-8 h-8 rounded-full">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-purple-primary/20 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-purple-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <span class="text-sm text-gray-dark">{{ $course->instructor->name }}</span>
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="flex gap-2">
                            <a
                                href="{{ route('admin.courses.modules', $course->id) }}"
                                wire:navigate
                                class="flex-1 px-4 py-2.5 bg-purple-primary hover:bg-purple-dark text-white rounded-lg transition-all font-medium text-center text-sm flex items-center justify-center gap-2 shadow-sm">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                </svg>
                                Módulos
                            </a>
                            <button
                                wire:click="togglePublished({{ $course->id }})"
                                class="p-2.5 hover:bg-purple-ultralight rounded-lg transition"
                                title="{{ $course->is_published ? 'Despublicar' : 'Publicar' }}">
                                <svg class="w-5 h-5 {{ $course->is_published ? 'text-green-600' : 'text-gray-medium' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                            <button
                                wire:click="openEditModal({{ $course->id }})"
                                class="p-2.5 text-purple-primary hover:bg-purple-ultralight rounded-lg transition"
                                title="Editar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button
                                wire:click="deleteCourse({{ $course->id }})"
                                wire:confirm="¿Estás seguro de eliminar el curso '{{ $course->title }}'?"
                                class="p-2.5 text-red-600 hover:bg-red-50 rounded-lg transition"
                                title="Eliminar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl p-12 text-center border border-gray-light/50">
                    <svg class="w-20 h-20 text-gray-light mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-gray-dark font-medium">No se encontraron cursos</p>
                    <p class="text-gray-medium text-sm mt-2">Comienza creando tu primer curso</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($courses->hasPages())
            <div class="mt-6">
                {{ $courses->links() }}
            </div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showModal') }">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 transition-opacity bg-black/70 backdrop-blur-sm" wire:click="closeModal"></div>

                <!-- Modal panel -->
                <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <form wire:submit="saveCourse">
                        <div class="px-8 py-6 border-b border-gray-light bg-gradient-to-r from-purple-primary to-purple-light">
                            <h3 class="text-2xl font-display font-bold text-white">
                                {{ $isEditing ? 'Editar Curso' : 'Crear Nuevo Curso' }}
                            </h3>
                        </div>

                        <div class="px-8 py-6 space-y-5 bg-gray-ultralight/30">
                            <!-- Title -->
                            <div>
                                <label class="block text-gray-dark text-sm mb-2 font-bold">Título *</label>
                                <input
                                    type="text"
                                    wire:model.live.debounce.300ms="title"
                                    class="w-full"
                                    placeholder="Ej: Fundamentos del Hip-Hop">
                                @error('title') <p class="mt-1 text-red-600 text-sm font-medium">{{ $message }}</p> @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-gray-dark text-sm mb-2 font-bold">Descripción *</label>
                                <textarea
                                    wire:model="description"
                                    rows="4"
                                    class="w-full resize-none"
                                    placeholder="Describe el contenido y objetivos del curso..."></textarea>
                                @error('description') <p class="mt-1 text-red-600 text-sm font-medium">{{ $message }}</p> @enderror
                            </div>

                            <!-- Instructor -->
                            <div>
                                <label class="block text-gray-dark text-sm mb-2 font-bold">Instructor</label>
                                <select
                                    wire:model="instructor_id"
                                    class="w-full">
                                    <option value="">Sin instructor asignado</option>
                                    @foreach($instructors as $instructor)
                                        <option value="{{ $instructor->id }}">{{ $instructor->name }}</option>
                                    @endforeach
                                </select>
                                @error('instructor_id') <p class="mt-1 text-red-600 text-sm font-medium">{{ $message }}</p> @enderror
                            </div>

                            <!-- Level -->
                            <div>
                                <label class="block text-gray-dark text-sm mb-2 font-bold">Nivel *</label>
                                <select
                                    wire:model="level"
                                    class="w-full">
                                    <option value="principiante">Principiante</option>
                                    <option value="intermedio">Intermedio</option>
                                    <option value="avanzado">Avanzado</option>
                                </select>
                                @error('level') <p class="mt-1 text-red-600 text-sm font-medium">{{ $message }}</p> @enderror
                            </div>

                            <!-- Image -->
                            <div>
                                <label class="block text-gray-dark text-sm mb-2 font-bold">Imagen del Curso</label>
                                <input
                                    type="file"
                                    wire:model="image"
                                    accept="image/*"
                                    class="w-full file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-purple-primary file:text-white hover:file:bg-purple-dark file:cursor-pointer file:transition">
                                @error('image') <p class="mt-1 text-red-600 text-sm font-medium">{{ $message }}</p> @enderror

                                @if ($image)
                                    <div class="mt-3 bg-white p-3 rounded-lg border border-gray-light">
                                        <p class="text-gray-dark text-sm mb-2 font-medium">Vista previa:</p>
                                        <img src="{{ $image->temporaryUrl() }}" class="w-32 h-32 object-cover rounded-lg shadow-sm">
                                    </div>
                                @elseif ($existingImage)
                                    <div class="mt-3 bg-white p-3 rounded-lg border border-gray-light">
                                        <p class="text-gray-dark text-sm mb-2 font-medium">Imagen actual:</p>
                                        <img src="{{ asset('storage/' . $existingImage) }}" class="w-32 h-32 object-cover rounded-lg shadow-sm">
                                    </div>
                                @endif
                            </div>

                            <!-- Published -->
                            <div class="flex items-center bg-white p-4 rounded-lg border border-gray-light">
                                <input
                                    type="checkbox"
                                    wire:model="is_published"
                                    id="is_published"
                                    class="w-5 h-5 text-purple-primary bg-white border-gray-light rounded focus:ring-purple-primary focus:ring-2">
                                <label for="is_published" class="ml-3 text-gray-dark text-sm font-medium cursor-pointer">Publicar curso inmediatamente</label>
                            </div>
                        </div>

                        <div class="px-8 py-6 border-t border-gray-light bg-white flex justify-end space-x-3">
                            <button
                                type="button"
                                wire:click="closeModal"
                                class="px-6 py-3 bg-gray-ultralight text-gray-dark font-medium rounded-lg hover:bg-gray-light transition-colors">
                                Cancelar
                            </button>
                            <button type="submit" class="px-6 py-3 bg-purple-primary hover:bg-purple-dark text-white font-bold rounded-lg shadow-sm hover:shadow transition-all">
                                {{ $isEditing ? 'Actualizar' : 'Crear' }} Curso
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
