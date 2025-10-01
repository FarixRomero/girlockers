<div>
    <x-slot name="header">
        Solicitar Acceso Completo
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <!-- Success/Error Messages -->
        @if(session()->has('success'))
            <div class="mb-6 p-4 bg-green-500/10 border border-green-500/30 rounded-lg">
                <p class="text-green-400 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ session('success') }}
                </p>
            </div>
        @endif

        @if(session()->has('error'))
            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/30 rounded-lg">
                <p class="text-red-400 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    {{ session('error') }}
                </p>
            </div>
        @endif

        @if($existingRequest)
            <!-- Pending Request Status -->
            <div class="card-premium text-center py-12">
                <div class="w-20 h-20 rounded-full bg-orange-500/20 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>

                <h2 class="font-display text-2xl text-cream mb-4">Solicitud Pendiente</h2>
                <p class="text-cream/80 max-w-md mx-auto mb-6">
                    Tu solicitud de acceso completo está siendo revisada. Te notificaremos por email cuando sea aprobada.
                </p>

                <div class="bg-purple-darker rounded-lg p-4 max-w-sm mx-auto">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-cream/70">Fecha de solicitud:</span>
                        <span class="text-cream font-medium">{{ $existingRequest->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm mt-2">
                        <span class="text-cream/70">Estado:</span>
                        <span class="px-3 py-1 bg-orange-500/20 text-orange-400 rounded-full text-xs font-bold">
                            {{ ucfirst($existingRequest->status) }}
                        </span>
                    </div>
                </div>

                <div class="mt-8">
                    <a href="{{ route('courses.index') }}" wire:navigate class="btn-secondary">
                        Explorar Lecciones Gratuitas
                    </a>
                </div>
            </div>
        @else
            <!-- Request Form -->
            <div class="card-premium">
                <div class="text-center mb-8">
                    <div class="w-20 h-20 rounded-full bg-gradient-pink flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-cream" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                        </svg>
                    </div>

                    <h2 class="font-display text-3xl text-cream mb-4">
                        Desbloquea Todo el Contenido
                    </h2>
                    <p class="text-cream/80 max-w-lg mx-auto">
                        Solicita acceso completo para disfrutar de todas nuestras lecciones premium, cursos avanzados y contenido exclusivo.
                    </p>
                </div>

                <!-- Benefits -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                    <div class="flex items-start p-4 bg-purple-darker rounded-lg">
                        <svg class="w-6 h-6 text-pink-vibrant mr-3 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <div>
                            <h3 class="font-display text-cream mb-1">Acceso Ilimitado</h3>
                            <p class="text-cream/70 text-sm">A todas las lecciones y cursos de la plataforma</p>
                        </div>
                    </div>

                    <div class="flex items-start p-4 bg-purple-darker rounded-lg">
                        <svg class="w-6 h-6 text-pink-vibrant mr-3 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <div>
                            <h3 class="font-display text-cream mb-1">Contenido Premium</h3>
                            <p class="text-cream/70 text-sm">Coreografías avanzadas y técnicas especializadas</p>
                        </div>
                    </div>

                    <div class="flex items-start p-4 bg-purple-darker rounded-lg">
                        <svg class="w-6 h-6 text-pink-vibrant mr-3 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <div>
                            <h3 class="font-display text-cream mb-1">Comunidad Exclusiva</h3>
                            <p class="text-cream/70 text-sm">Interactúa con otras lockers premium</p>
                        </div>
                    </div>

                    <div class="flex items-start p-4 bg-purple-darker rounded-lg">
                        <svg class="w-6 h-6 text-pink-vibrant mr-3 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <div>
                            <h3 class="font-display text-cream mb-1">Actualización Continua</h3>
                            <p class="text-cream/70 text-sm">Nuevo contenido agregado regularmente</p>
                        </div>
                    </div>
                </div>

                <!-- User Info -->
                <div class="bg-purple-darker rounded-lg p-6 mb-6">
                    <h3 class="font-display text-lg text-cream mb-4">Información de tu Cuenta</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-cream/70">Nombre:</span>
                            <span class="text-cream">{{ auth()->user()->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-cream/70">Email:</span>
                            <span class="text-cream">{{ auth()->user()->email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-cream/70">Fecha de registro:</span>
                            <span class="text-cream">{{ auth()->user()->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- CTA Button -->
                <div class="text-center">
                    <button
                        wire:click="submitRequest"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                        class="btn-primary btn-pulse text-lg px-8 py-4">
                        <span wire:loading.remove wire:target="submitRequest">
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

                    <p class="text-cream/60 text-sm mt-4">
                        Tu solicitud será revisada por nuestro equipo en un plazo de 24-48 horas
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>
