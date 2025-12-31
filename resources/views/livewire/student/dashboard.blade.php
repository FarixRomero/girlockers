<div class="max-w-full">
    <!-- Payment Success Modal -->
    @if(session('payment_success'))
    <div x-data="{ show: true }"
         x-show="show"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title"
         role="dialog"
         aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                 aria-hidden="true"
                 @click="show = false"></div>

            <!-- Center the modal -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-6 pt-6 pb-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-16 w-16 rounded-full bg-green-100 sm:mx-0 sm:h-16 sm:w-16">
                            <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2" id="modal-title">
                                ¡Pago Exitoso!
                            </h3>
                            <div class="mt-4">
                                <p class="text-base text-gray-700 mb-3">
                                    Tu pago se realizó correctamente. Ahora tienes acceso completo a todas las clases de nuestra plataforma.
                                </p>
                                <div class="bg-purple-50 rounded-lg p-4 border border-purple-100">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-purple-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <div class="text-sm text-purple-900">
                                            <p class="font-semibold mb-1">Membresía {{ session('membership_type') === 'monthly' ? 'Mensual' : 'Trimestral' }} activada</p>
                                            <p class="text-purple-700">¡Disfruta de todo el contenido premium sin restricciones!</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse">
                    <button type="button"
                            @click="show = false"
                            class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-base font-semibold text-white hover:from-purple-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm">
                        ¡Empezar a bailar!
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Hero Section - Bienvenida con estadísticas (unified design for mobile and desktop) -->
    <div class="bg-white py-6 px-4">
        <!-- Header con logo y notificación (solo móvil) -->
        <div class="md:hidden flex items-center justify-between mb-6">
            <a href="{{ route('dashboard') }}" wire:navigate>
                <img src="{{ asset('images/girls_lockers_logo.png') }}" alt="Girls Lockers" class="h-10 w-auto object-contain">
            </a>
            <livewire:student.notifications />
        </div>

        <!-- Título de bienvenida -->
        <h1 class="text-2xl md:text-3xl font-bold text-stone-800 mb-1 tracking-tight">
            Bienvenido de nuevo
        </h1>
        <p class="text-stone-600 text-sm font-medium mb-6">Aquí tienes tus clases y progreso</p>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-6 mt-8">
            <!-- Minutes Dancing -->
            <div class="bg-white rounded-xl p-6 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)] border-t-4 border-blue-900 flex flex-col justify-between h-36 transition-all hover:-translate-y-1 hover:shadow-xl group">
                <div class="bg-blue-50 w-10 h-10 rounded-full flex items-center justify-center text-blue-900 group-hover:bg-blue-900 group-hover:text-white transition-colors duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <div class="text-3xl font-bold text-stone-800 tracking-tight">{{ $stats['total_minutes'] }}</div>
                    <div class="text-[10px] font-bold text-stone-600 uppercase tracking-wider mt-1">Minutos bailando</div>
                </div>
            </div>

            <!-- Completed Lessons -->
            <div class="bg-white rounded-xl p-6 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)] border-t-4 border-purple-900 flex flex-col justify-between h-36 transition-all hover:-translate-y-1 hover:shadow-xl group">
                <div class="bg-purple-50 w-10 h-10 rounded-full flex items-center justify-center text-purple-900 group-hover:bg-purple-900 group-hover:text-white transition-colors duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <div class="text-3xl font-bold text-stone-800 tracking-tight">{{ $stats['completed_lessons'] }}</div>
                    <div class="text-[10px] font-bold text-stone-600 uppercase tracking-wider mt-1">Clases completadas</div>
                </div>
            </div>

            <!-- Access Status (solo desktop) -->
            <div class="hidden md:flex bg-white rounded-xl p-6 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)] border-t-4 border-amber-600 flex-col justify-between h-36 transition-all hover:-translate-y-1 hover:shadow-xl group">
                <div class="bg-amber-50 w-10 h-10 rounded-full flex items-center justify-center text-amber-700 group-hover:bg-amber-600 group-hover:text-white transition-colors duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                    </svg>
                </div>
                <div>
                    <div class="text-3xl font-bold tracking-tight text-stone-800">
                        {{ $stats['has_access'] ? 'Premium' : 'Free' }}
                    </div>
                    <div class="text-[10px] font-bold text-stone-600 uppercase tracking-wider mt-1">Tu plan</div>
                </div>
            </div>
        </div>

        <!-- Premium CTA Button (only show if user doesn't have full access) -->
        @if(!$stats['has_access'])
        <a href="{{ route('purchase-membership') }}" wire:navigate class="block">
            <div class="bg-gradient-to-r from-pink-500 via-pink-600 to-orange-500 rounded-2xl p-5 md:p-6 text-white shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl md:text-2xl font-bold mb-1">Desbloquea tu potencial con Premium</h3>
                        <p class="text-sm md:text-base text-white/90">Acceso ilimitado a clases y contenido exclusivo de locking.</p>
                    </div>
                    <div class="bg-white rounded-full px-4 py-2 md:px-6 md:py-3">
                        <span class="text-pink-600 font-bold text-sm md:text-base">Únete a Premium</span>
                    </div>
                </div>
            </div>
        </a>
        @endif
    </div>

    <!-- Recent Lessons Carousel -->
    @if($recentLessons->count() > 0)
    <div class="px-6 py-6 md:py-8">
        <h2 class="text-2xl font-bold text-stone-800 mb-6 tracking-tight border-l-4 border-blue-900 pl-3">Últimas Clases</h2>
        <div class="relative overflow-hidden">
            <div class="overflow-x-auto pb-4 -mx-4 px-4 scroll-smooth scrollbar-hide" id="recent-carousel">
                <div class="flex gap-3 md:gap-6" style="width: max-content;">
                    @foreach($recentLessons as $lesson)
                        <div class="group rounded-lg overflow-hidden" style="width: 280px; flex-shrink: 0;">
                            <a href="{{ route('lessons.show', $lesson) }}" wire:navigate class="block">
                                <div class="aspect-video relative rounded-xl overflow-hidden shadow-md border border-stone-100">
                                    @if($lesson->thumbnail)
                                        <img src="{{ $lesson->thumbnail_url }}" alt="{{ $lesson->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-purple-500 to-pink-500">
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <svg class="w-16 h-16 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Lock icon for premium lessons -->
                                    @if(!$lesson->is_trial && !auth()->user()->has_full_access)
                                        <div class="absolute top-2 left-2 bg-black/80 rounded-lg p-1.5">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @endif

                                    <!-- Tags en la parte inferior de la imagen -->
                                    <div class="absolute bottom-2 left-2 flex flex-wrap gap-1">
                                        @php
                                            $levelColors = [
                                                'principiante' => 'bg-green-500',
                                                'intermedio' => 'bg-blue-500',
                                                'avanzado' => 'bg-red-500'
                                            ];
                                            $courseLevel = $lesson->module?->course?->level;
                                            $levelColor = isset($courseLevel) ? ($levelColors[$courseLevel] ?? 'bg-gray-500') : 'bg-gray-500';
                                        @endphp
                                        @if($courseLevel)
                                            <span class="px-1.5 py-0.5 {{ $levelColor }} text-white text-[10px] font-bold uppercase rounded">{{ $courseLevel }}</span>
                                        @endif
                                        @if($lesson->duration_minutes && $lesson->video_type !== 'youtube')
                                            <span class="px-1.5 py-0.5 bg-black/80 text-white text-[10px] font-bold uppercase rounded">{{ $lesson->duration_minutes }} MIN</span>
                                        @endif
                                        @foreach($lesson->tags->take(1) as $tag)
                                            <span class="px-1.5 py-0.5 bg-black/80 text-white text-[10px] font-bold uppercase rounded">{{ $tag->name }}</span>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Info abajo sin background -->
                                <div class="pt-3">
                                    <h3 class="text-stone-800 font-semibold text-sm line-clamp-2 mb-1">{{ $lesson->title }}</h3>
                                    @if($lesson->instructor)
                                        <p class="text-stone-600 text-xs mt-1 font-medium">{{ $lesson->instructor->name }}</p>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            <button onclick="document.getElementById('recent-carousel').scrollBy({left: -290, behavior: 'smooth'})"
                    class="hidden md:flex absolute left-0 top-1/3 -translate-y-1/2 -translate-x-4 bg-white/90 hover:bg-white p-3 rounded-full shadow-lg transition z-10">
                <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button onclick="document.getElementById('recent-carousel').scrollBy({left: 290, behavior: 'smooth'})"
                    class="hidden md:flex absolute right-0 top-1/3 -translate-y-1/2 translate-x-4 bg-white/90 hover:bg-white p-3 rounded-full shadow-lg transition z-10">
                <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </div>
    @endif

    <!-- Coreografía Carousel -->
    @if($coreografiaLessons->count() > 0)
    <div class="px-6 py-6 md:py-8">
        <h2 class="text-2xl font-bold text-stone-800 mb-6 tracking-tight border-l-4 border-blue-900 pl-3">Coreografía</h2>
        <div class="relative overflow-hidden">
            <div class="overflow-x-auto pb-4 -mx-4 px-4 scroll-smooth scrollbar-hide" id="coreografia-carousel">
                <div class="flex gap-3 md:gap-6" style="width: max-content;">
                    @foreach($coreografiaLessons as $lesson)
                        <div class="group rounded-lg overflow-hidden" style="width: 280px; flex-shrink: 0;">
                            <a href="{{ route('lessons.show', $lesson) }}" wire:navigate class="block">
                                <div class="aspect-video relative rounded-xl overflow-hidden shadow-md border border-stone-100">
                                    @if($lesson->thumbnail)
                                        <img src="{{ $lesson->thumbnail_url }}" alt="{{ $lesson->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-purple-500 to-pink-500">
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <svg class="w-16 h-16 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Lock icon for premium lessons -->
                                    @if(!$lesson->is_trial && !auth()->user()->has_full_access)
                                        <div class="absolute top-2 left-2 bg-black/80 rounded-lg p-1.5">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @endif

                                    <!-- Tags en la parte inferior de la imagen -->
                                    <div class="absolute bottom-2 left-2 flex flex-wrap gap-1">
                                        @php
                                            $levelColors = [
                                                'principiante' => 'bg-green-500',
                                                'intermedio' => 'bg-blue-500',
                                                'avanzado' => 'bg-red-500'
                                            ];
                                            $courseLevel = $lesson->module?->course?->level;
                                            $levelColor = isset($courseLevel) ? ($levelColors[$courseLevel] ?? 'bg-gray-500') : 'bg-gray-500';
                                        @endphp
                                        @if($courseLevel)
                                            <span class="px-1.5 py-0.5 {{ $levelColor }} text-white text-[10px] font-bold uppercase rounded">{{ $courseLevel }}</span>
                                        @endif
                                        @if($lesson->duration_minutes && $lesson->video_type !== 'youtube')
                                            <span class="px-1.5 py-0.5 bg-black/80 text-white text-[10px] font-bold uppercase rounded">{{ $lesson->duration_minutes }} MIN</span>
                                        @endif
                                        @foreach($lesson->tags->take(1) as $tag)
                                            <span class="px-1.5 py-0.5 bg-black/80 text-white text-[10px] font-bold uppercase rounded">{{ $tag->name }}</span>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Info abajo sin background -->
                                <div class="pt-3">
                                    <h3 class="text-stone-800 font-semibold text-sm line-clamp-2 mb-1">{{ $lesson->title }}</h3>
                                    @if($lesson->instructor)
                                        <p class="text-stone-600 text-xs mt-1 font-medium">{{ $lesson->instructor->name }}</p>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            <button onclick="document.getElementById('coreografia-carousel').scrollBy({left: -290, behavior: 'smooth'})"
                    class="hidden md:flex absolute left-0 top-1/3 -translate-y-1/2 -translate-x-4 bg-white/90 hover:bg-white p-3 rounded-full shadow-lg transition z-10">
                <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button onclick="document.getElementById('coreografia-carousel').scrollBy({left: 290, behavior: 'smooth'})"
                    class="hidden md:flex absolute right-0 top-1/3 -translate-y-1/2 translate-x-4 bg-white/90 hover:bg-white p-3 rounded-full shadow-lg transition z-10">
                <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </div>
    @endif

    <!-- Saved Lessons Carousel -->
    @if($savedLessons->count() > 0)
    <div class="px-6 py-6 md:py-8">
        <h2 class="text-2xl font-bold text-stone-800 mb-6 tracking-tight border-l-4 border-blue-900 pl-3">Tus Clases Guardadas</h2>
        <div class="relative overflow-hidden">
            <div class="overflow-x-auto pb-4 -mx-4 px-4 scroll-smooth scrollbar-hide" id="saved-carousel">
                <div class="flex gap-3 md:gap-6" style="width: max-content;">
                    @foreach($savedLessons as $lesson)
                        <div class="group rounded-lg overflow-hidden" style="width: 280px; flex-shrink: 0;">
                            <a href="{{ route('lessons.show', $lesson) }}" wire:navigate class="block">
                                <div class="aspect-video relative rounded-xl overflow-hidden shadow-md border border-stone-100">
                                    @if($lesson->thumbnail)
                                        <img src="{{ $lesson->thumbnail_url }}" alt="{{ $lesson->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-purple-500 to-pink-500">
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <svg class="w-16 h-16 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Lock icon for premium lessons -->
                                    @if(!$lesson->is_trial && !auth()->user()->has_full_access)
                                        <div class="absolute top-2 left-2 bg-black/80 rounded-lg p-1.5">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @endif

                                    <!-- Tags en la parte inferior de la imagen -->
                                    <div class="absolute bottom-2 left-2 flex flex-wrap gap-1">
                                        @php
                                            $levelColors = [
                                                'principiante' => 'bg-green-500',
                                                'intermedio' => 'bg-blue-500',
                                                'avanzado' => 'bg-red-500'
                                            ];
                                            $courseLevel = $lesson->module?->course?->level;
                                            $levelColor = isset($courseLevel) ? ($levelColors[$courseLevel] ?? 'bg-gray-500') : 'bg-gray-500';
                                        @endphp
                                        @if($courseLevel)
                                            <span class="px-1.5 py-0.5 {{ $levelColor }} text-white text-[10px] font-bold uppercase rounded">{{ $courseLevel }}</span>
                                        @endif
                                        @if($lesson->duration_minutes && $lesson->video_type !== 'youtube')
                                            <span class="px-1.5 py-0.5 bg-black/80 text-white text-[10px] font-bold uppercase rounded">{{ $lesson->duration_minutes }} MIN</span>
                                        @endif
                                        @foreach($lesson->tags->take(1) as $tag)
                                            <span class="px-1.5 py-0.5 bg-black/80 text-white text-[10px] font-bold uppercase rounded">{{ $tag->name }}</span>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Info abajo sin background -->
                                <div class="pt-3">
                                    <h3 class="text-stone-800 font-semibold text-sm line-clamp-2 mb-1">{{ $lesson->title }}</h3>
                                    @if($lesson->instructor)
                                        <p class="text-stone-600 text-xs mt-1 font-medium">{{ $lesson->instructor->name }}</p>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            <button onclick="document.getElementById('saved-carousel').scrollBy({left: -290, behavior: 'smooth'})"
                    class="hidden md:flex absolute left-0 top-1/3 -translate-y-1/2 -translate-x-4 bg-white/90 hover:bg-white p-3 rounded-full shadow-lg transition z-10">
                <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button onclick="document.getElementById('saved-carousel').scrollBy({left: 290, behavior: 'smooth'})"
                    class="hidden md:flex absolute right-0 top-1/3 -translate-y-1/2 translate-x-4 bg-white/90 hover:bg-white p-3 rounded-full shadow-lg transition z-10">
                <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </div>
    @endif

    <!-- Trending Courses -->
    @if($trendingCourses->count() > 0)
    <div class="px-6 py-6 md:py-8 pb-12">
        <h2 class="text-2xl font-bold text-stone-800 mb-6 tracking-tight border-l-4 border-purple-900 pl-3">Cursos Destacados</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($trendingCourses as $course)
            <a href="{{ route('courses.show', $course) }}" wire:navigate class="bg-white rounded-2xl overflow-hidden shadow-lg border border-stone-100 flex flex-col md:flex-row h-auto md:h-64 group hover:shadow-xl transition-shadow">
                <div class="relative w-full md:w-1/3">
                    @if($course->image)
                        <!-- Course Image -->
                        <img src="{{ $course->image_url }}"
                             alt="{{ $course->title }}"
                             class="w-full h-full object-cover transition-all duration-500">
                    @else
                        <!-- Fallback Gradient -->
                        <div class="w-full h-full bg-gradient-to-br from-pink-500 via-purple-500 to-blue-500">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <svg class="w-20 h-20 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="p-8 flex-1 flex flex-col justify-center relative overflow-hidden">
                    <div class="flex items-center space-x-3 mb-3">
                        @php
                            $levelColors = [
                                'principiante' => 'bg-stone-100 text-stone-600 border-stone-200',
                                'intermedio' => 'bg-blue-100 text-blue-600 border-blue-200',
                                'avanzado' => 'bg-red-100 text-red-600 border-red-200'
                            ];
                            $levelColor = $levelColors[$course->level] ?? 'bg-stone-100 text-stone-600 border-stone-200';
                        @endphp
                        <span class="{{ $levelColor }} text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wide border">{{ $course->level }}</span>
                        <div class="flex items-center text-stone-600 text-xs font-semibold uppercase tracking-wide">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"></path>
                            </svg>
                            {{ $course->modules_count }} módulos
                        </div>
                    </div>
                    <h3 class="text-3xl font-bold text-stone-900 mb-4 tracking-tight group-hover:text-purple-900 transition-colors">{{ $course->title }}</h3>
                    @if($course->description)
                        <p class="text-stone-600 text-sm leading-relaxed mb-6 max-w-lg">{{ $course->description }}</p>
                    @endif
                    <div>
                        <button class="bg-stone-900 hover:bg-stone-800 text-white px-6 py-2.5 rounded-lg font-medium text-sm inline-flex items-center transition-all shadow-md border-b-2 border-stone-700 hover:border-stone-900 hover:translate-y-px">
                            Continuar Curso
                            <svg class="w-4 h-4 ml-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </a>
        @endforeach
        </div>
    </div>
    @endif
</div>
