<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pago Seguro - Girls Lockers</title>

    <!-- Izipay JavaScript SDK - DEBE cargarse ANTES del formulario -->
    <script
        type="text/javascript"
        src="https://static.micuentaweb.pe/static/js/krypton-client/V4.0/stable/kr-payment-form.min.js"
        kr-public-key="{{ $publicKey }}"
        kr-post-url-success="{{ route('payment.callback.success') }}">
    </script>

    <!-- Tema Neon -->
    <link rel="stylesheet" href="https://static.micuentaweb.pe/static/js/krypton-client/V4.0/ext/neon-reset.min.css">
    <script type="text/javascript" src="https://static.micuentaweb.pe/static/js/krypton-client/V4.0/ext/neon.js"></script>

    @vite(['resources/css/app.css'])

    <style>
        body {
            background: #f9fafb;
            min-height: 100vh;
            padding: 2rem 1rem;
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        .payment-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .kr-embedded {
            margin: 2rem 0;
            width: 100%;
        }

        /* Mejoras para mobile */
        @media (max-width: 1024px) {
            body {
                padding: 1rem 0.75rem;
            }

            .payment-grid {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <!-- Logo -->
        <div class="mb-8">
            <img src="{{ asset('images/girls_lockers_logo.png') }}" alt="Girls Lockers" class="h-12">
        </div>

        <!-- Two Column Layout -->
        <div class="payment-grid grid lg:grid-cols-2 gap-6 lg:gap-8">

            <!-- Left Column - Payment Details -->
            <div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 lg:p-10">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-2xl font-bold text-gray-900">Detalles de Pago</h2>
                        <div class="flex items-center text-gray-500 text-sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            SEGURO
                        </div>
                    </div>

                    @if($savedCards->count() > 0)
                    <!-- Tarjetas Guardadas -->
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Tarjetas guardadas</h3>
                        <div class="space-y-2">
                            @foreach($savedCards as $card)
                            <div class="border border-gray-200 rounded-lg p-3 hover:border-blue-500 hover:bg-blue-50 transition cursor-pointer"
                                 onclick="alert('Función de pago con tarjeta guardada en desarrollo')">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-6 bg-gradient-to-r from-blue-600 to-blue-700 rounded flex items-center justify-center text-white text-xs font-bold">
                                        {{ strtoupper(substr($card->card_brand, 0, 4)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $card->card_brand_name }} •••• {{ $card->card_last_four }}</p>
                                        <p class="text-xs text-gray-500">
                                            Vence: {{ str_pad($card->card_expiry_month, 2, '0', STR_PAD_LEFT) }}/{{ $card->card_expiry_year }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-3 text-center">
                            <p class="text-sm text-gray-500">o paga con una nueva tarjeta</p>
                        </div>
                    </div>
                    @endif

                    <!-- Formulario de pago embebido -->
                    <div class="kr-embedded" kr-form-token="{{ $formToken }}"></div>

                    <!-- Support & Security Info -->
                    <div class="mt-8 pt-8 border-t border-gray-100">
                        <div class="text-center space-y-3">
                            <p class="text-sm text-gray-600">
                                💬 Soporte: <a href="https://www.instagram.com/girls_lockers/" target="_blank" class="text-blue-600 hover:underline font-medium">Instagram</a> • <a href="https://wa.me/51" target="_blank" class="text-blue-600 hover:underline font-medium">WhatsApp</a>
                            </p>
                            <p class="text-xs text-gray-500">
                                Pago seguro procesado por Izipay
                            </p>
                            <p class="text-xs text-gray-400">
                                Al confirmar tu suscripción, autorizas a Girls Lockers a realizar el cargo.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Back Button -->
                <div class="mt-4">
                    <a href="{{ route('purchase-membership') }}"
                       class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver a planes
                    </a>
                </div>
            </div>

            <!-- Right Column - Subscription Summary -->
            <div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 lg:p-8 lg:sticky lg:top-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-1">
                        Resumen de <span class="text-blue-600">Suscripción</span>
                    </h2>
                    <p class="text-sm text-gray-500 mb-6">Revisa los detalles de tu plan antes de pagar.</p>

                    <!-- Membership Card with Image -->
                    <div class="relative rounded-lg overflow-hidden mb-6">
                        <img src="{{ $membershipType === 'monthly' ? asset('images/imagen.jpg') : asset('images/imagen4.jpg') }}"
                             alt="Membresía {{ $membershipType === 'monthly' ? 'Mensual' : 'Trimestral' }}"
                             class="w-full h-48 object-cover">
                        <div class="absolute top-3 right-3">
                            <span class="bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full">
                                PREMIUM
                            </span>
                        </div>
                    </div>

                    <!-- Membership Details -->
                    <div class="mb-6">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <h3 class="font-bold text-gray-900">Membresía Girls Lockers</h3>
                                <p class="text-sm text-gray-500">
                                    {{ $membershipType === 'monthly' ? 'Mensual • Acceso Ilimitado' : 'Trimestral • Acceso Ilimitado' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-xl font-bold text-blue-600">S/ {{ number_format($amount, 2) }}</p>
                            </div>
                        </div>

                        <!-- Features List -->
                        <div class="space-y-2 mt-4">
                            <div class="flex items-start text-sm">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-700">Acceso completo a todas las clases</span>
                            </div>
                            <div class="flex items-start text-sm">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-700">Más de 50 tutoriales grabados</span>
                            </div>
                            <div class="flex items-start text-sm">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-700">Cancela cuando quieras</span>
                            </div>
                        </div>
                    </div>

                    <!-- Price Breakdown -->
                    <div class="border-t border-gray-200 pt-4 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="text-gray-900">S/ {{ number_format($amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Impuesto (IGV 18%)</span>
                            <span class="text-gray-900">Incluido</span>
                        </div>
                        <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                            <span class="text-base font-bold text-gray-900">Total a pagar hoy</span>
                            <span class="text-2xl font-bold text-gray-900">S/ {{ number_format($amount, 2) }}</span>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</body>
</html>
