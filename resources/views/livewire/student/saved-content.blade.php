<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Guardados</h1>
        <p class="text-gray-600">Tus clases favoritas</p>
    </div>

    <!-- Saved Lessons Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($savedLessons as $lesson)
            <a href="{{ route('lessons.show', $lesson) }}" wire:navigate class="group bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition">
                <!-- Thumbnail -->
                <div class="aspect-video bg-gradient-to-br from-purple-500 to-pink-500 relative overflow-hidden">
                    @if($lesson->thumbnail)
                        <img
                            src="{{ asset('storage/' . $lesson->thumbnail) }}"
                            alt="{{ $lesson->title }}"
                            class="w-full h-full object-cover"
                            onerror="this.style.display='none';this.parentElement.querySelector('.placeholder-icon').style.display='flex';">
                    @endif
                    <div class="placeholder-icon absolute inset-0 flex items-center justify-center {{ $lesson->thumbnail ? 'hidden' : '' }}">
                        <svg class="w-12 h-12 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <!-- Like indicator -->
                    <div class="absolute top-2 right-2">
                        <svg class="w-6 h-6 text-red-500 fill-current drop-shadow-lg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 group-hover:text-purple-600 transition line-clamp-2 mb-2">
                        {{ $lesson->title }}
                    </h3>
                    <p class="text-sm text-gray-600 mb-2">{{ $lesson->module->course->title }}</p>
                    @if($lesson->instructor)
                        <p class="text-xs text-gray-500">{{ $lesson->instructor->name }}</p>
                    @endif
                </div>
            </a>
        @empty
            <div class="col-span-full text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                </svg>
                <p class="mt-4 text-lg text-gray-600">No tienes clases guardadas</p>
                <p class="mt-2 text-sm text-gray-500">Dale "me gusta" a tus clases favoritas para verlas aquí</p>
                <a href="{{ route('lessons.index') }}" wire:navigate class="mt-4 inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition">
                    Explorar Clases
                </a>
            </div>
        @endforelse
    </div>
</div>
