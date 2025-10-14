<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Instructores</h1>
        <p class="text-gray-600">Conoce a nuestros instructores expertos</p>
    </div>

    <!-- Instructors Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($instructors as $instructor)
            <div class="bg-white rounded-lg p-6 shadow-sm hover:shadow-lg transition">
                <!-- Avatar -->
                <div class="w-24 h-24 mx-auto mb-4 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                    <span class="text-3xl text-white font-bold">{{ substr($instructor->name, 0, 1) }}</span>
                </div>

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
                        <a href="https://instagram.com/{{ $instructor->instagram }}" target="_blank" class="inline-flex items-center mt-3 text-sm text-purple-600 hover:text-purple-700">
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
</div>
