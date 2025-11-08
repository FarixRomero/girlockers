<div class="min-h-screen bg-gray-50">
    <!-- Sticky Header - Instagram Style -->
    <div class="sticky top-0 z-50 bg-white border-b border-gray-200">
        <div class="max-w-2xl mx-auto px-4 py-3 flex items-center justify-between">
            <button onclick="window.history.back()" class="text-gray-700 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <h1 class="text-base font-semibold text-gray-900">Editar lección</h1>
            <div class="w-6"></div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="max-w-2xl mx-auto px-4 mt-4">
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="max-w-2xl mx-auto px-4 mt-4">
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <div class="max-w-2xl mx-auto px-4 py-6">
        <!-- Video Preview (si existe Bunny.net video) -->
        @if($form->bunny_video_id)
            <div class="bg-white rounded-lg border border-gray-200 mb-4 overflow-hidden">
                <!-- Bunny.net Video Player -->
                <div class="aspect-video w-full bg-black">
                    <iframe
                        src="https://iframe.mediadelivery.net/embed/{{ config('bunny.library_id') }}/{{ $form->bunny_video_id }}"
                        loading="lazy"
                        style="border: none; width: 100%; height: 100%;"
                        allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;"
                        allowfullscreen="true">
                    </iframe>
                </div>
                <div class="p-4 border-t border-gray-100">
                    <p class="text-sm text-gray-600">
                        <span class="font-medium">Video ID:</span> {{ $form->bunny_video_id }}
                    </p>
                </div>
            </div>
        @endif

        <!-- Thumbnail Upload Area -->
        <div class="bg-white rounded-lg border border-gray-200 mb-4 overflow-hidden">
            <!-- Preview Area -->
            <div class="h-48 md:h-64 w-full bg-gray-100 flex items-center justify-center relative">
                @if($form->thumbnailPreview)
                    <img src="{{ $form->thumbnailPreview }}" class="w-full h-full object-cover" alt="Preview">
                @else
                    <div class="text-center px-4">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm text-gray-600 mb-2">Vista previa de miniatura</p>
                        <p class="text-xs text-gray-500">Sube una miniatura abajo</p>
                    </div>
                @endif
            </div>

            <!-- Upload Controls -->
            <div class="p-4 border-t border-gray-100">
                <label class="flex items-center justify-between cursor-pointer">
                    <span class="text-sm text-gray-700">Miniatura</span>
                    <input type="file" wire:model.live="form.thumbnail" class="hidden" accept="image/*">
                    <span class="text-sm text-purple-500 font-medium">Cambiar miniatura</span>
                </label>
                @error('form.thumbnail') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Form Fields - Instagram Style (sin card) -->
        <div class="space-y-3">
            <!-- Title -->
            <div>
                <input type="text"
                       wire:model="form.title"
                       placeholder="Título"
                       class="w-full px-4 py-3 text-base border-0 focus:outline-none focus:ring-0 placeholder-gray-400 bg-transparent">
                @error('form.title') <span class="text-xs text-red-500 mt-1 block px-4">{{ $message }}</span> @enderror
            </div>

            <!-- Description -->
            <div>
                <textarea wire:model="form.description"
                          rows="3"
                          placeholder="Descripción"
                          class="w-full px-4 py-3 text-base border-0 focus:outline-none focus:ring-0 placeholder-gray-400 resize-none bg-transparent"></textarea>
                @error('form.description') <span class="text-xs text-red-500 mt-1 block px-4">{{ $message }}</span> @enderror
            </div>

            <!-- Module Selection -->
            <div>
                <select wire:model="form.module_id"
                        class="w-full px-4 py-3 text-base border-0 focus:outline-none focus:ring-0 text-gray-900 bg-transparent appearance-none cursor-pointer"
                        style="background-image: url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'%236b7280\'%3E%3Cpath stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 5l7 7-7 7\'/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1.5rem;">
                    <option value="" class="text-gray-400">Módulo</option>
                    @foreach($courses as $course)
                        <optgroup label="{{ $course->title }}">
                            @foreach($course->modules as $module)
                                <option value="{{ $module->id }}">{{ $module->title }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                @error('form.module_id') <span class="text-xs text-red-500 mt-1 block px-4">{{ $message }}</span> @enderror
            </div>

            <!-- Instructor Selection -->
            <div>
                <select wire:model="form.instructor_id"
                        class="w-full px-4 py-3 text-base border-0 focus:outline-none focus:ring-0 text-gray-900 bg-transparent appearance-none cursor-pointer"
                        style="background-image: url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'%236b7280\'%3E%3Cpath stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 5l7 7-7 7\'/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1.5rem;">
                    @foreach($instructors as $instructor)
                        <option value="{{ $instructor->id }}">{{ $instructor->name }}</option>
                    @endforeach
                </select>
                @error('form.instructor_id') <span class="text-xs text-red-500 mt-1 block px-4">{{ $message }}</span> @enderror
            </div>

            <!-- Tags -->
            <div x-data="{ selectedTags: @entangle('form.selectedTags'), showTags: false }">
                <div @click="showTags = !showTags" class="flex items-center justify-between cursor-pointer px-4 py-3">
                    <span class="text-base" :class="selectedTags.length > 0 ? 'text-gray-900' : 'text-gray-400'">
                        <span x-show="selectedTags.length === 0">Etiquetas</span>
                        <span x-show="selectedTags.length > 0" x-text="selectedTags.length + ' etiqueta' + (selectedTags.length > 1 ? 's' : '') + ' seleccionada' + (selectedTags.length > 1 ? 's' : '')"></span>
                    </span>
                    <svg class="w-6 h-6 text-gray-400 transition-transform" :class="showTags ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
                <div x-show="showTags" x-collapse class="px-4 pb-3 flex flex-wrap gap-2">
                    @foreach($tags as $tag)
                        <button type="button"
                                @click="if(selectedTags.includes({{ $tag->id }})) { selectedTags = selectedTags.filter(id => id !== {{ $tag->id }}) } else { selectedTags.push({{ $tag->id }}) }"
                                :class="selectedTags.includes({{ $tag->id }}) ? 'bg-purple-50 border-purple-500 text-purple-700' : 'bg-white border-gray-200 text-gray-700 hover:border-gray-300'"
                                class="px-3 py-1.5 text-xs font-medium rounded-full border transition">
                            {{ $tag->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Duration (Hidden inputs, visible as detail) -->
            <input type="hidden" wire:model="form.duration_minutes">
            <input type="hidden" wire:model="form.duration_seconds">

            @if($form->duration_minutes > 0 || $form->duration_seconds > 0)
            <div class="px-4 py-2">
                <p class="text-xs text-gray-500">
                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Duración: {{ $form->duration_minutes }}:{{ str_pad($form->duration_seconds, 2, '0', STR_PAD_LEFT) }} min
                </p>
            </div>
            @endif

            <!-- Trial Lesson Toggle -->
            <div class="px-4 py-3">
                <label class="flex items-center justify-between cursor-pointer">
                    <span class="text-base text-gray-900">Clase Gratis</span>
                    <div class="relative inline-block w-11 h-6">
                        <input type="checkbox"
                               wire:model="form.is_trial"
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-500"></div>
                    </div>
                </label>
            </div>
        </div>

        <!-- Action Buttons - Sticky Bottom -->
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 lg:static lg:mt-6 lg:bg-transparent lg:border-0 lg:p-0">
            <div class="max-w-2xl mx-auto flex gap-3">
                <button type="button"
                        wire:click="saveDraft"
                        class="flex-1 px-4 py-3 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Ocultar
                </button>
                <button type="button"
                        wire:click="publish"
                        class="flex-1 px-4 py-3 text-sm font-semibold text-white bg-purple-500 rounded-lg hover:bg-purple-600 transition">
                    Publicar
                </button>
            </div>
        </div>

        <!-- Bottom Spacing for Fixed Buttons -->
        <div class="h-20 lg:hidden"></div>
    </div>

    <!-- Loading Indicator - Solo para acciones de guardar/publicar -->
    <div wire:loading.delay wire:target="saveDraft,publish" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <svg class="animate-spin h-5 w-5 text-purple-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-sm font-medium text-gray-700">Procesando...</span>
        </div>
    </div>

    @script
    <script>
        (function() {
            // Auto-fetch duration if missing
            const bunnyVideoId = @js($form->bunny_video_id ?? '');
            const currentDurationMinutes = @js($form->duration_minutes ?? 0);
            const currentDurationSeconds = @js($form->duration_seconds ?? 0);

            // Solo intentar obtener duración si:
            // 1. Tiene bunny_video_id
            // 2. No tiene duración (0 minutos y 0 segundos)
            if (bunnyVideoId && currentDurationMinutes === 0 && currentDurationSeconds === 0) {
                console.log('Lección sin duración detectada. Obteniendo de Bunny.net...');

                const getDuration = async (retries = 10, delay = 3000) => {
                    for (let i = 0; i < retries; i++) {
                        try {
                            // Esperar antes de intentar (excepto en el primer intento)
                            if (i > 0) {
                                console.log(`Reintentando obtener duración (${i + 1}/${retries})...`);
                                await new Promise(resolve => setTimeout(resolve, delay));
                            }

                            const durationResponse = await fetch('{{ route("admin.lessons.bunny.duration") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    video_id: bunnyVideoId
                                })
                            });

                            const durationData = await durationResponse.json();

                            if (durationData.success && durationData.duration && durationData.duration > 0) {
                                // Convertir segundos a minutos y segundos
                                const totalSeconds = parseInt(durationData.duration);
                                const minutes = Math.floor(totalSeconds / 60);
                                const seconds = totalSeconds % 60;

                                // Auto-llenar los campos de duración en el formulario Livewire
                                await @this.set('form.duration_minutes', minutes);
                                await @this.set('form.duration_seconds', seconds);
                                await @this.set('form.duration', totalSeconds);

                                console.log('✅ Duración obtenida:', minutes, 'minutos y', seconds, 'segundos');

                                // Guardar automáticamente en la base de datos
                                try {
                                    await @this.call('saveDuration');
                                    console.log('💾 Duración guardada automáticamente en la base de datos');
                                } catch (error) {
                                    console.error('❌ Error al guardar duración:', error);
                                }

                                return; // Éxito, salir del loop
                            } else {
                                console.log(`⏳ Duración aún no disponible (intento ${i + 1}/${retries}). El video podría estar procesándose...`);
                            }
                        } catch (error) {
                            console.warn(`❌ Error al obtener duración (intento ${i + 1}):`, error);
                        }
                    }
                    console.warn('⚠️  No se pudo obtener la duración después de ' + retries + ' intentos. El video podría estar procesándose en Bunny.net.');
                };

                // Llamar a la función de obtener duración
                getDuration();
            }
        })();
    </script>
    @endscript
</div>
