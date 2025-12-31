<div class="min-h-screen bg-white py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <!-- Stepper -->
        <div class="flex items-center justify-center mb-12">
            <div class="flex items-center gap-4">
                <!-- Step 1 - Active -->
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                        1
                    </div>
                    <span class="text-base font-semibold text-gray-900">Plan</span>
                </div>

                <!-- Divider -->
                <div class="w-20 h-0.5 bg-gray-300"></div>

                <!-- Step 2 - Inactive -->
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <span class="text-base font-medium text-gray-400">Pago seguro</span>
                </div>
            </div>
        </div>

        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl sm:text-5xl font-black text-gray-900 mb-3">Elige tu experiencia</h1>
            <p class="text-base sm:text-lg text-gray-600 max-w-2xl mx-auto">
                Selecciona el plan que mejor se adapte a tu ritmo y comienza a dominar el locking.
            </p>
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

        <!-- Membership Plans -->
        <div class="grid md:grid-cols-2 gap-6 mb-12">
            @foreach ($membershipPlans as $plan)
                <div wire:click="selectMembershipType('{{ $plan->type }}')"
                     class="relative bg-white rounded-3xl overflow-hidden cursor-pointer transition-all hover:scale-[1.02] {{ $plan->type === 'quarterly' ? 'border-4 border-purple-600 shadow-2xl' : 'border-2 border-gray-200 shadow-lg hover:shadow-xl' }}">

                    @if ($plan->type === 'quarterly')
                        <div class="absolute top-4 right-4 z-10">
                            <span class="bg-gradient-to-r from-purple-600 to-pink-600 text-white text-xs font-bold px-4 py-2 rounded-full shadow-lg uppercase">
                                Recomendado
                            </span>
                        </div>
                    @endif

                    <!-- Image -->
                    <div class="relative h-64 overflow-hidden">
                        @if ($plan->type === 'monthly')
                            <img src="{{ asset('images/imagen.jpg') }}"
                                 alt="Plan Mensual"
                                 class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-b from-transparent to-white/90"></div>
                        @else
                            <img src="{{ asset('images/imagen4.jpg') }}"
                                 alt="Plan Trimestral"
                                 class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-purple-500/20 to-white"></div>
                        @endif
                    </div>

                    <div class="p-8 pb-10">
                        <!-- Plan Title -->
                        <h3 class="text-2xl font-black text-gray-900 text-center mb-3">
                            {{ $plan->type === 'monthly' ? 'Mensual' : 'Trimestral' }}
                        </h3>

                        <!-- Price -->
                        <div class="text-center mb-2">
                            <div class="text-6xl font-black text-gray-900 tracking-tight" style="font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; font-variant-numeric: tabular-nums;">
                                @if($plan->currency === 'USD')
                                    ${{ number_format($plan->price, 2) }}
                                @elseif($plan->currency === 'EUR')
                                    €{{ number_format($plan->price, 2) }}
                                @else
                                    S/ {{ number_format($plan->price, 2) }}
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 mt-2">
                                {{ $plan->type === 'monthly' ? 'facturado cada mes' : 'cada 3 meses' }}
                                @if ($plan->type === 'quarterly')
                                    <span class="text-purple-600 font-bold">(Ahorras 11%)</span>
                                @endif
                            </p>
                        </div>

                        <!-- Benefits -->
                        <div class="space-y-3 my-8">
                            @if ($plan->type === 'monthly')
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-5 h-5 rounded-full border-2 border-purple-600 flex items-center justify-center mt-0.5">
                                        <div class="w-2 h-2 bg-purple-600 rounded-full"></div>
                                    </div>
                                    <span class="text-sm text-gray-700">Acceso completo a videos</span>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-5 h-5 rounded-full border-2 border-purple-600 flex items-center justify-center mt-0.5">
                                        <svg class="w-3 h-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm text-gray-700">Todas las clases básicas</span>
                                </div>
                            @else
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-5 h-5 rounded-full bg-purple-100 flex items-center justify-center mt-0.5">
                                        <svg class="w-3 h-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm text-gray-700">Acceso <strong class="font-bold">ilimitado</strong></span>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-5 h-5 rounded-full bg-purple-100 flex items-center justify-center mt-0.5">
                                        <svg class="w-3 h-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm text-gray-700">Todas las clases y niveles</span>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-5 h-5 rounded-full bg-purple-100 flex items-center justify-center mt-0.5">
                                        <svg class="w-3 h-3 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm text-purple-600 font-bold">Mejor valor</span>
                                </div>
                            @endif
                        </div>

                        <!-- CTA Button -->
                        <button class="w-full py-4 rounded-xl font-bold text-base transition-all {{ $plan->type === 'quarterly' ? 'bg-gradient-to-r from-purple-600 to-pink-600 text-white shadow-lg hover:shadow-xl hover:scale-[1.02]' : 'bg-gray-100 text-gray-900 hover:bg-gray-200' }}">
                            {{ $plan->type === 'quarterly' ? 'Elegir plan trimestral' : 'Elegir plan mensual' }}
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Support Info -->
        <div class="text-center mb-6">
            <p class="text-sm text-gray-600 flex items-center justify-center gap-2">
                <svg class="w-5 h-5 text-pink-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
                </svg>
                ¿Dudas? Escríbenos por <a href="https://www.instagram.com/girls_lockers/" target="_blank" class="font-semibold text-gray-900 underline hover:text-purple-600 transition">Instagram</a>
            </p>
        </div>

        <!-- Security Info -->
        <div class="text-center pb-8">
            <p class="text-xs text-gray-500 flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                </svg>
                Pago 100% seguro • Cancela cuando quieras
            </p>
        </div>
    </div>
</div>
