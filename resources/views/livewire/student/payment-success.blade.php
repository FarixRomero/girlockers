<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <!-- Success Icon and Message -->
        <div class="text-center mb-8">
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-4">
                <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">¡Pago Exitoso!</h1>
            <p class="text-lg text-gray-600">Tu membresía premium ha sido activada</p>
        </div>

        @if ($payment && $payment->isCompleted())
            <!-- Payment Details Card -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Detalles del Pago</h2>

                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tipo de Membresía:</span>
                        <span class="font-semibold text-gray-900">
                            {{ $payment->membership_type === 'monthly' ? 'Mensual (1 mes)' : 'Trimestral (3 meses)' }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Monto Pagado:</span>
                        <span class="font-semibold text-gray-900">S/ {{ number_format($payment->amount, 2) }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Fecha de Pago:</span>
                        <span class="font-semibold text-gray-900">
                            {{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}
                        </span>
                    </div>

                    @if ($payment->payment_method)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Método de Pago:</span>
                            <span class="font-semibold text-gray-900">
                                {{ $payment->payment_method }} •••• {{ $payment->card_last_four }}
                            </span>
                        </div>
                    @endif

                    <div class="flex justify-between">
                        <span class="text-gray-600">ID de Transacción:</span>
                        <span class="font-mono text-sm text-gray-900">{{ $payment->order_id }}</span>
                    </div>
                </div>
            </div>

            <!-- Membership Info Card -->
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg shadow-sm p-6 text-white mb-6">
                <h2 class="text-xl font-bold mb-4">Tu Membresía Premium</h2>

                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-purple-100">Estado:</span>
                        <span class="px-3 py-1 bg-green-500 rounded-full text-sm font-semibold">Activa</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-purple-100">Fecha de Inicio:</span>
                        <span class="font-semibold">{{ $user->access_granted_at ? $user->access_granted_at->format('d/m/Y') : now()->format('d/m/Y') }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-purple-100">Vence el:</span>
                        <span class="font-semibold">{{ $user->membership_expires_at ? $user->membership_expires_at->format('d/m/Y') : '' }}</span>
                    </div>

                    @if ($user->membership_expires_at)
                        <div class="mt-4 bg-white bg-opacity-20 rounded-lg p-3">
                            <p class="text-sm">
                                Tienes <strong>{{ $user->getDaysUntilExpiration() }} días</strong> de acceso completo a todo el contenido premium
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Benefits Unlocked -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Lo que has desbloqueado:</h2>

                <div class="space-y-3">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-gray-900">Acceso ilimitado a todas las clases</span>
                    </div>

                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-gray-900">Videos en alta calidad (HD)</span>
                    </div>

                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-gray-900">Contenido actualizado semanalmente</span>
                    </div>

                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-gray-900">Soporte prioritario</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('courses.index') }}"
                   wire:navigate
                   class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                    Explorar Cursos
                </a>

                <a href="{{ route('dashboard') }}"
                   wire:navigate
                   class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-white border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-all">
                    Ir al Dashboard
                </a>
            </div>

            <!-- Receipt Info -->
            <div class="mt-6 text-center text-sm text-gray-600">
                <p>Se ha enviado un recibo a tu correo: <strong>{{ $user->email }}</strong></p>
                <p class="mt-2">Si tienes alguna pregunta, contáctanos en soporte@girlslockers.com</p>
            </div>
        @else
            <!-- Error State -->
            <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                <p class="text-gray-600">No se encontró información del pago.</p>
                <a href="{{ route('dashboard') }}"
                   wire:navigate
                   class="mt-4 inline-block px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                    Volver al Dashboard
                </a>
            </div>
        @endif
    </div>
</div>
