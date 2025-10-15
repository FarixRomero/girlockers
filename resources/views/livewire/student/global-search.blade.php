<div class="relative w-full" x-data="{ open: @entangle('showResults') }">
    <!-- Search Input -->
    <div class="relative">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        <input
            type="search"
            wire:model.live.debounce.300ms="query"
            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
            placeholder="Buscar clases, cursos, instructores..."
            @click.away="open && $wire.closeResults()"
        >
    </div>

    <!-- Search Results Dropdown -->
    <div
        x-show="open"
        x-transition
        class="absolute left-0 right-0 mt-2 bg-white rounded-lg shadow-lg border border-gray-200 max-h-96 overflow-y-auto z-50"
        style="display: none;"
    >
        @if($results && (count($results['lessons']) > 0 || count($results['courses']) > 0 || count($results['instructors']) > 0))
            <!-- Lessons Section -->
            @if(count($results['lessons']) > 0)
                <div class="px-4 py-2 border-b border-gray-200">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase">Clases</h3>
                </div>
                @foreach($results['lessons'] as $lesson)
                    <a
                        href="{{ route('lessons.show', $lesson) }}"
                        wire:navigate
                        class="block px-4 py-3 hover:bg-gray-50 transition border-b border-gray-100"
                        @click="open = false"
                    >
                        <div class="flex items-start space-x-3">
                            @if($lesson->thumbnail_url)
                                <img src="{{ $lesson->thumbnail_url }}" alt="{{ $lesson->title }}" class="w-16 h-10 object-cover rounded flex-shrink-0">
                            @else
                                <div class="w-16 h-10 bg-purple-100 rounded flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $lesson->title }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $lesson->module->course->title ?? '' }}</p>
                                @if($lesson->instructor)
                                    <p class="text-xs text-gray-400">{{ $lesson->instructor->name }}</p>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            @endif

            <!-- Courses Section -->
            @if(count($results['courses']) > 0)
                <div class="px-4 py-2 border-b border-gray-200">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase">Cursos</h3>
                </div>
                @foreach($results['courses'] as $course)
                    <a
                        href="{{ route('courses.show', $course) }}"
                        wire:navigate
                        class="block px-4 py-3 hover:bg-gray-50 transition border-b border-gray-100"
                        @click="open = false"
                    >
                        <div class="flex items-start space-x-3">
                            @if($course->image_url)
                                <img src="{{ $course->image_url }}" alt="{{ $course->title }}" class="w-16 h-10 object-cover rounded flex-shrink-0">
                            @else
                                <div class="w-16 h-10 bg-blue-100 rounded flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $course->title }}</p>
                                @if($course->description)
                                    <p class="text-xs text-gray-500 line-clamp-2">{{ $course->description }}</p>
                                @endif
                                <span class="inline-block mt-1 px-2 py-0.5 text-xs font-medium rounded-full
                                    @if($course->level === 'beginner') bg-green-100 text-green-800
                                    @elseif($course->level === 'intermediate') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($course->level) }}
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            @endif

            <!-- Instructors Section -->
            @if(count($results['instructors']) > 0)
                <div class="px-4 py-2 border-b border-gray-200">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase">Instructores</h3>
                </div>
                @foreach($results['instructors'] as $instructor)
                    <a
                        href="{{ route('instructors.index', ['instructor' => $instructor->id]) }}"
                        wire:navigate
                        class="block px-4 py-3 hover:bg-gray-50 transition border-b border-gray-100"
                        @click="open = false"
                    >
                        <div class="flex items-start space-x-3">
                            @if($instructor->photo_url)
                                <img src="{{ $instructor->photo_url }}" alt="{{ $instructor->name }}" class="w-10 h-10 rounded-full object-cover flex-shrink-0">
                            @else
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-white font-semibold text-sm">{{ substr($instructor->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $instructor->name }}</p>
                                <p class="text-xs text-gray-500">{{ $instructor->lessons_count }} {{ $instructor->lessons_count === 1 ? 'clase' : 'clases' }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            @endif
        @elseif($query && strlen($query) >= 2)
            <!-- No Results -->
            <div class="px-4 py-8 text-center">
                <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm text-gray-600">No se encontraron resultados para "{{ $query }}"</p>
            </div>
        @endif
    </div>
</div>
