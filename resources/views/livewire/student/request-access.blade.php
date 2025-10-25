<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-3xl mx-auto">
        <!-- Success/Error Messages -->
        @if(session()->has('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-800 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ session('success') }}
                </p>
            </div>
        @endif

        @if(session()->has('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-red-800 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    {{ session('error') }}
                </p>
            </div>
        @endif

        @if($existingRequest)
            <!-- Pending Request Status -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden text-center py-12 px-6">
                <div class="w-20 h-20 rounded-full bg-orange-100 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>

                <h2 class="text-3xl font-bold text-gray-900 mb-4">Solicitud Pendiente</h2>
                <p class="text-gray-600 max-w-md mx-auto mb-8 text-lg">
                    Tu solicitud de acceso completo está siendo revisada. Te notificaremos por email cuando sea aprobada.
                </p>

                <div class="bg-gray-50 rounded-lg p-6 max-w-sm mx-auto mb-8">
                    <div class="flex items-center justify-between text-sm mb-3">
                        <span class="text-gray-600">Fecha de solicitud:</span>
                        <span class="text-gray-900 font-semibold">{{ $existingRequest->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Estado:</span>
                        <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-bold uppercase">
                            {{ ucfirst($existingRequest->status) }}
                        </span>
                    </div>
                </div>

                <div class="space-y-3">
                    <a href="{{ route('courses.index') }}" wire:navigate class="inline-flex items-center px-6 py-3 bg-gray-900 text-white font-semibold rounded-lg hover:bg-gray-800 transition">
                        Explorar Cursos
                    </a>
                    <div>
                        <a href="{{ route('dashboard') }}" wire:navigate class="text-gray-600 hover:text-gray-900 text-sm underline">
                            Volver al Dashboard
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- Request Form -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="bg-gradient-to-br from-purple-600 via-pink-600 to-purple-700 text-white text-center py-12 px-6">
                    <div class="w-20 h-20 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                        </svg>
                    </div>

                    <h2 class="text-4xl font-bold mb-4">
                        Desbloquea Todo el Contenido
                    </h2>
                    <p class="text-white/90 text-lg max-w-lg mx-auto">
                        Solicita acceso completo para disfrutar de todas nuestras lecciones premium, cursos avanzados y contenido exclusivo.
                    </p>
                </div>

                <div class="p-8">

                    <!-- Benefits -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        <div class="flex items-start p-4 bg-purple-50 rounded-lg border border-purple-100">
                            <div class="w-10 h-10 rounded-full bg-purple-600 flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-1">Acceso Ilimitado</h3>
                                <p class="text-gray-600 text-sm">A todas las lecciones y cursos de la plataforma</p>
                            </div>
                        </div>

                        <div class="flex items-start p-4 bg-pink-50 rounded-lg border border-pink-100">
                            <div class="w-10 h-10 rounded-full bg-pink-600 flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-1">Contenido Premium</h3>
                                <p class="text-gray-600 text-sm">Coreografías avanzadas y técnicas especializadas</p>
                            </div>
                        </div>

                        <div class="flex items-start p-4 bg-purple-50 rounded-lg border border-purple-100">
                            <div class="w-10 h-10 rounded-full bg-purple-600 flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-1">Comunidad Exclusiva</h3>
                                <p class="text-gray-600 text-sm">Interactúa con otras lockers premium</p>
                            </div>
                        </div>

                        <div class="flex items-start p-4 bg-pink-50 rounded-lg border border-pink-100">
                            <div class="w-10 h-10 rounded-full bg-pink-600 flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-1">Actualización Continua</h3>
                                <p class="text-gray-600 text-sm">Nuevo contenido agregado regularmente</p>
                            </div>
                        </div>
                    </div>

                    <!-- User Info -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-8 border border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Información de tu Cuenta</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Nombre:</span>
                                <span class="text-gray-900 font-semibold">{{ auth()->user()->name }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Email:</span>
                                <span class="text-gray-900 font-semibold">{{ auth()->user()->email }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Fecha de registro:</span>
                                <span class="text-gray-900 font-semibold">{{ auth()->user()->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <div class="text-center">
                        <button
                            wire:click="submitRequest"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                            class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold text-lg rounded-lg hover:from-purple-700 hover:to-pink-700 transition shadow-lg hover:shadow-xl">
                            <span wire:loading.remove wire:target="submitRequest" class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                Solicitar Acceso Ahora
                            </span>
                            <span wire:loading wire:target="submitRequest" class="flex items-center">
                                <svg class="animate-spin h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Enviando...
                            </span>
                        </button>

                        <p class="text-gray-600 text-sm mt-4">
                            Tu solicitud será revisada por nuestro equipo en un plazo de 24-48 horas
                        </p>

                        <div class="mt-6">
                            <a href="{{ route('dashboard') }}" wire:navigate class="text-gray-600 hover:text-gray-900 text-sm underline">
                                Volver al Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
