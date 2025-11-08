<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="sticky top-0 z-50 bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Configuración del Landing Page</h1>
                    <p class="text-sm text-gray-600 mt-1">Personaliza los textos, precios y contenidos del landing</p>
                </div>
                <button wire:click="save"
                        class="px-6 py-3 bg-purple-500 hover:bg-purple-600 text-white font-semibold rounded-lg transition">
                    Guardar Cambios
                </button>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Tabs -->
    <div class="max-w-7xl mx-auto px-4 mt-6">
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="flex overflow-x-auto border-b border-gray-200">
                @foreach($groups as $groupKey => $groupLabel)
                    <button
                        wire:click="changeGroup('{{ $groupKey }}')"
                        class="px-6 py-4 text-sm font-medium whitespace-nowrap transition {{ $currentGroup === $groupKey ? 'text-purple-600 border-b-2 border-purple-600 bg-purple-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        {{ $groupLabel }}
                    </button>
                @endforeach
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="space-y-6">
                    @foreach($configs as $key => $config)
                        <div class="border border-gray-200 rounded-lg p-5 bg-gray-50">
                            <label class="block text-sm font-semibold text-gray-900 mb-1">
                                {{ $config['label'] }}
                            </label>
                            @if($config['description'])
                                <p class="text-xs text-gray-600 mb-3">{{ $config['description'] }}</p>
                            @endif

                            @if($config['type'] === 'text' || $config['type'] === 'url' || $config['type'] === 'number')
                                <input
                                    type="{{ $config['type'] === 'number' ? 'number' : 'text' }}"
                                    wire:model="configs.{{ $key }}.value"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">

                            @elseif($config['type'] === 'color')
                                <div class="flex gap-4 items-center">
                                    <input
                                        type="color"
                                        wire:model.live="configs.{{ $key }}.value"
                                        class="h-12 w-24 border border-gray-300 rounded-lg cursor-pointer">
                                    <input
                                        type="text"
                                        wire:model="configs.{{ $key }}.value"
                                        placeholder="#9333ea"
                                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent font-mono">
                                    <div class="w-12 h-12 rounded-lg border-2 border-gray-300" style="background-color: {{ $config['value'] ?? '#9333ea' }}"></div>
                                </div>

                            @elseif($config['type'] === 'image')
                                <div class="space-y-4">
                                    @if($config['value'])
                                        <div class="flex items-center gap-4 p-4 bg-white rounded-lg border border-gray-200">
                                            <img src="{{ asset('storage/' . $config['value']) }}" alt="Logo actual" class="h-16 object-contain">
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-700">Logo actual</p>
                                                <p class="text-xs text-gray-500">{{ $config['value'] }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="flex items-center gap-4">
                                        <input
                                            type="file"
                                            wire:model="logo"
                                            accept="image/*"
                                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                                        @if($logo)
                                            <div class="px-4 py-2 bg-green-50 text-green-700 rounded-lg text-sm font-medium">
                                                ✓ Listo para subir
                                            </div>
                                        @endif
                                    </div>
                                    @error('logo')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                    <p class="text-xs text-gray-500">Formatos: PNG, JPG, SVG. Tamaño máximo: 2MB</p>
                                </div>

                            @elseif($config['type'] === 'textarea')
                                <textarea
                                    wire:model="configs.{{ $key }}.value"
                                    rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"></textarea>

                            @elseif($config['type'] === 'json')
                                @if(str_contains($key, 'testimonial'))
                                    <!-- Testimonial JSON Editor -->
                                    <div class="space-y-3 bg-white p-4 rounded-lg border border-gray-200">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Iniciales</label>
                                            <input
                                                type="text"
                                                wire:model="configs.{{ $key }}.value.initials"
                                                maxlength="2"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Usuario</label>
                                            <input
                                                type="text"
                                                wire:model="configs.{{ $key }}.value.username"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Ubicación</label>
                                            <input
                                                type="text"
                                                wire:model="configs.{{ $key }}.value.location"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Testimonio</label>
                                            <textarea
                                                wire:model="configs.{{ $key }}.value.text"
                                                rows="3"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"></textarea>
                                        </div>
                                    </div>

                                @elseif(str_contains($key, 'features'))
                                    <!-- Features List Editor -->
                                    <div class="space-y-2">
                                        @if(is_array($config['value']))
                                            @foreach($config['value'] as $index => $feature)
                                                <div class="flex gap-2">
                                                    <input
                                                        type="text"
                                                        wire:model="configs.{{ $key }}.value.{{ $index }}"
                                                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                                        placeholder="Feature {{ $index + 1 }}">
                                                    <button
                                                        type="button"
                                                        wire:click="removeFeature('{{ $key }}', {{ $index }})"
                                                        class="px-3 py-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-sm font-medium">
                                                        Eliminar
                                                    </button>
                                                </div>
                                            @endforeach
                                        @endif
                                        <button
                                            type="button"
                                            wire:click="addFeature('{{ $key }}')"
                                            class="w-full px-4 py-2 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-lg text-sm font-medium">
                                            + Agregar Feature
                                        </button>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom spacing -->
    <div class="h-8"></div>

    <!-- Loading Indicator -->
    <div wire:loading.delay class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <svg class="animate-spin h-5 w-5 text-purple-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-sm font-medium text-gray-700">Guardando...</span>
        </div>
    </div>
</div>
