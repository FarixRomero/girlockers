<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-6 sm:mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Obtén Acceso Premium</h1>
            <p class="text-sm sm:text-base text-gray-600">Desbloquea todo el contenido y clases exclusivas</p>
        </div>

        <!-- Error/Success Messages -->
        @if (session()->has('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        @if (session()->has('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Membership Plans Selection -->
        @if (!$showPaymentForm)
            <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 mb-4 sm:mb-6">
                <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-3 sm:mb-4">Selecciona tu Plan</h2>

                <div class="grid md:grid-cols-2 gap-3 sm:gap-4">
                    @foreach ($membershipPlans as $plan)
                        <div wire:click="selectMembershipType('{{ $plan->type }}')"
                             class="relative border-2 rounded-lg p-4 sm:p-6 cursor-pointer transition-all hover:shadow-md
                                    {{ $selectedMembershipType === $plan->type ? 'border-purple-600 bg-purple-50' : 'border-gray-200 hover:border-purple-300' }}">

                            @if ($plan->type === 'quarterly')
                                <span class="absolute top-2 right-2 bg-green-500 text-white text-xs px-2 py-1 rounded-full">
                                    ¡Ahorra S/ 10!
                                </span>
                            @endif

                            <div class="mb-3 sm:mb-4">
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900">
                                    {{ $plan->type === 'monthly' ? 'Plan Mensual' : 'Plan Trimestral' }}
                                </h3>
                                <p class="text-xs sm:text-sm text-gray-600 mt-1">{{ $plan->description }}</p>
                            </div>

                            <div class="mb-3 sm:mb-4">
                                <span class="text-2xl sm:text-3xl font-bold text-gray-900">S/ {{ number_format($plan->price, 2) }}</span>
                                <span class="text-sm sm:text-base text-gray-600">/ {{ $plan->type === 'monthly' ? 'mes' : '3 meses' }}</span>
                            </div>

                            <div class="flex items-center text-xs sm:text-sm text-gray-600">
                                @if ($selectedMembershipType === $plan->type)
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Plan Seleccionado
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Saved Cards Section -->
            @if ($savedCards->count() > 0)
                <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 mb-4 sm:mb-6">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-3 sm:mb-4">Pagar con Tarjeta Guardada</h2>

                    <div class="space-y-2 sm:space-y-3">
                        @foreach ($savedCards as $card)
                            <div class="border border-gray-200 rounded-lg p-3 sm:p-4 hover:border-purple-300 transition-all">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <div class="flex items-center space-x-3 sm:space-x-4">
                                        <div class="w-10 h-7 sm:w-12 sm:h-8 bg-gradient-to-r from-purple-600 to-pink-600 rounded flex items-center justify-center text-white text-xs font-bold">
                                            {{ strtoupper(substr($card->card_brand, 0, 4)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm sm:text-base font-semibold text-gray-900">{{ $card->card_brand_name }} •••• {{ $card->card_last_four }}</p>
                                            <p class="text-xs sm:text-sm text-gray-600">Vence: {{ str_pad($card->card_expiry_month, 2, '0', STR_PAD_LEFT) }}/{{ $card->card_expiry_year }}</p>
                                        </div>
                                    </div>
                                    <button wire:click="payWithSavedCard('{{ $card->id }}')"
                                            class="w-full sm:w-auto px-4 py-2 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition-colors">
                                        Pagar S/ {{ number_format($membershipPlans->where('type', $selectedMembershipType)->first()->price, 2) }}
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-3 sm:mt-4 text-center">
                        <p class="text-xs sm:text-sm text-gray-600">o paga con una nueva tarjeta</p>
                    </div>
                </div>
            @endif

            <!-- Pay with New Card Button -->
            <div class="text-center">
                <button wire:click="createPaymentIntent"
                        class="inline-flex items-center px-6 sm:px-8 py-3 sm:py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm sm:text-base font-semibold rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all shadow-lg">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    {{ $savedCards->count() > 0 ? 'Pagar con Nueva Tarjeta' : 'Continuar al Pago' }}
                </button>
            </div>
        @endif


        <!-- Benefits Section -->
        <div class="mt-6 sm:mt-8 bg-white rounded-lg shadow-sm p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-3 sm:mb-4">Qué incluye tu Membresía Premium</h3>
            <div class="grid md:grid-cols-2 gap-3 sm:gap-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-500 mr-2 sm:mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-sm sm:text-base font-semibold text-gray-900">Acceso Ilimitado</p>
                        <p class="text-xs sm:text-sm text-gray-600">Todas las clases y contenido premium</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-500 mr-2 sm:mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-sm sm:text-base font-semibold text-gray-900">Videos en HD</p>
                        <p class="text-xs sm:text-sm text-gray-600">Calidad profesional garantizada</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-500 mr-2 sm:mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-sm sm:text-base font-semibold text-gray-900">Nuevas Clases</p>
                        <p class="text-xs sm:text-sm text-gray-600">Contenido actualizado semanalmente</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-500 mr-2 sm:mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-sm sm:text-base font-semibold text-gray-900">Soporte Prioritario</p>
                        <p class="text-xs sm:text-sm text-gray-600">Ayuda personalizada cuando la necesites</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Info -->
        <div class="mt-4 sm:mt-6 text-center text-xs sm:text-sm text-gray-600">
            <p>🔒 Pago 100% seguro con encriptación SSL</p>
            <p class="mt-1">Cancela cuando quieras • Sin cargos ocultos</p>
        </div>
    </div>
</div>
