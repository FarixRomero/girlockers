<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if($selectedInstructor)
        <!-- Instructor Detail View -->
        <div class="mb-6">
            <button wire:click="clearSelection" class="inline-flex items-center text-purple-600 hover:text-purple-700 font-medium">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Volver a instructores
            </button>
        </div>

        <!-- Instructor Header -->
        <div class="bg-white rounded-lg shadow-sm p-8 mb-8">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                <!-- Avatar -->
                @if($selectedInstructor->photo_url)
                    <img src="{{ $selectedInstructor->photo_url }}" alt="{{ $selectedInstructor->name }}" class="w-32 h-32 rounded-full object-cover">
                @else
                    <div class="w-32 h-32 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-5xl text-white font-bold">{{ substr($selectedInstructor->name, 0, 1) }}</span>
                    </div>
                @endif

                <!-- Info -->
                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $selectedInstructor->name }}</h1>

                    @if($selectedInstructor->description)
                        <p class="text-gray-600 mb-4">{{ $selectedInstructor->description }}</p>
                    @endif

                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 text-sm text-gray-500">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-1 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $selectedInstructor->lessons_count }} {{ Str::plural('clase', $selectedInstructor->lessons_count) }}
                        </span>

                        @if($selectedInstructor->instagram)
                            <a href="https://instagram.com/{{ $selectedInstructor->instagram }}" target="_blank" class="inline-flex items-center text-purple-600 hover:text-purple-700">
                                <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                                {{ $selectedInstructor->instagram }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div x-data="{ activeTab: 'lessons' }" class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8">
                    <button
                        @click="activeTab = 'lessons'"
                        :class="activeTab === 'lessons' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-4 px-1 border-b-2 font-medium text-sm transition"
                    >
                        Clases ({{ $instructorLessons->count() }})
                    </button>
                    <button
                        @click="activeTab = 'courses'"
                        :class="activeTab === 'courses' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-4 px-1 border-b-2 font-medium text-sm transition"
                    >
                        Cursos ({{ $instructorCourses->count() }})
                    </button>
                </nav>
            </div>

            <!-- Lessons Tab -->
            <div x-show="activeTab === 'lessons'" class="mt-6">
                @if($instructorLessons->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($instructorLessons as $lesson)
                            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition">
                                <a href="{{ route('lessons.show', $lesson) }}" wire:navigate class="block">
                                    <!-- Thumbnail -->
                                    <div class="relative aspect-video bg-gray-200">
                                        @if($lesson->thumbnail_url)
                                            <img src="{{ $lesson->thumbnail_url }}" alt="{{ $lesson->title }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-100 to-pink-100">
                                                <svg class="w-16 h-16 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                        @endif

                                        <!-- Lock icon for premium lessons -->
                                        @if(!$lesson->is_trial && !auth()->user()->has_full_access)
                                            <div class="absolute top-2 left-2 bg-black/80 rounded-lg p-2">
                                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        @endif

                                        @if($lesson->is_trial)
                                            <span class="absolute top-2 right-2 px-2 py-1 bg-green-500 text-white text-xs font-semibold rounded">GRATIS</span>
                                        @endif
                                    </div>
                                </a>

                                <!-- Content -->
                                <div class="p-4">
                                    <a href="{{ route('lessons.show', $lesson) }}" wire:navigate>
                                        <h3 class="font-semibold text-gray-900 mb-2 hover:text-purple-600 transition line-clamp-2">{{ $lesson->title }}</h3>
                                    </a>
                                    <p class="text-sm text-gray-500 mb-3">{{ $lesson->module->course->title ?? '' }}</p>

                                    <!-- Tags -->
                                    @if($lesson->tags->count() > 0)
                                        <div class="flex flex-wrap gap-1 mb-3">
                                            @foreach($lesson->tags->take(3) as $tag)
                                                <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">{{ $tag->name }}</span>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Actions -->
                                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                        <button
                                            wire:click="toggleLike({{ $lesson->id }})"
                                            class="flex items-center space-x-1 text-sm {{ $lesson->is_liked ? 'text-red-500' : 'text-gray-500' }} hover:text-red-500 transition"
                                        >
                                            <svg class="w-5 h-5 {{ $lesson->is_liked ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                            <span>{{ $lesson->likes_count }}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-gray-600">Este instructor aún no tiene clases disponibles</p>
                    </div>
                @endif
            </div>

            <!-- Courses Tab -->
            <div x-show="activeTab === 'courses'" class="mt-6" style="display: none;">
                @if($instructorCourses->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($instructorCourses as $course)
                            <a href="{{ route('courses.show', $course) }}" wire:navigate class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition">
                                <!-- Image -->
                                <div class="relative aspect-video bg-gray-200">
                                    @if($course->image_url)
                                        <img src="{{ $course->image_url }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-purple-100">
                                            <svg class="w-16 h-16 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <span class="absolute top-2 right-2 px-2 py-1 text-white text-xs font-semibold rounded
                                        @if($course->level === 'beginner') bg-green-500
                                        @elseif($course->level === 'intermediate') bg-yellow-500
                                        @else bg-red-500
                                        @endif">
                                        {{ strtoupper($course->level) }}
                                    </span>
                                </div>

                                <!-- Content -->
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900 mb-2 hover:text-purple-600 transition line-clamp-2">{{ $course->title }}</h3>
                                    @if($course->description)
                                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $course->description }}</p>
                                    @endif
                                    <p class="text-sm text-gray-500">{{ $course->modules_count }} {{ Str::plural('módulo', $course->modules_count) }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <p class="text-gray-600">Este instructor aún no tiene cursos disponibles</p>
                    </div>
                @endif
            </div>
        </div>
    @else
        <!-- Instructors List View -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Instructores</h1>
            <p class="text-gray-600">Conoce a nuestros instructores expertos</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($instructors as $instructor)
                <div wire:click="selectInstructor({{ $instructor->id }})" class="bg-white rounded-lg p-6 shadow-sm hover:shadow-lg transition cursor-pointer">
                    <!-- Avatar -->
                    @if($instructor->photo_url)
                        <img src="{{ $instructor->photo_url }}" alt="{{ $instructor->name }}" class="w-24 h-24 mx-auto mb-4 rounded-full object-cover">
                    @else
                        <div class="w-24 h-24 mx-auto mb-4 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                            <span class="text-3xl text-white font-bold">{{ substr($instructor->name, 0, 1) }}</span>
                        </div>
                    @endif

                    <!-- Info -->
                    <div class="text-center">
                        <h3 class="text-xl font-semibold text-gray-900 mb-1">{{ $instructor->name }}</h3>
                        @if($instructor->description)
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $instructor->description }}</p>
                        @endif
                        <p class="text-sm text-gray-500">
                            {{ $instructor->lessons_count }} {{ Str::plural('clase', $instructor->lessons_count) }}
                        </p>

                        @if($instructor->instagram)
                            <a href="https://instagram.com/{{ $instructor->instagram }}" target="_blank" onclick="event.stopPropagation()" class="inline-flex items-center mt-3 text-sm text-purple-600 hover:text-purple-700">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                               {{ $instructor->instagram }}
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-lg text-gray-600">No hay instructores disponibles</p>
                </div>
            @endforelse
        </div>
    @endif
</div>
