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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            margin: 0;
        }

        .payment-wrapper {
            width: 100%;
            max-width: 42rem;
            margin: 0 auto;
        }

        .kr-embedded {
            margin: 1.5rem auto 1rem;
            max-width: 500px;
            width: 100%;
        }

        /* Centrar el contenido del formulario de Izipay */
        .kr-card-form {
            margin: 0 auto;
            max-width: 100%;
        }

        /* Mejoras para mobile */
        @media (max-width: 640px) {
            body {
                padding: 0.75rem;
                align-items: flex-start;
                padding-top: 2rem;
            }

            .payment-wrapper {
                max-width: 100%;
            }

            .kr-embedded {
                margin: 1rem auto;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="payment-wrapper">
        <!-- Header -->
        <div class="text-center mb-4 sm:mb-6">
            <a href="{{ route('dashboard') }}" class="inline-block mb-3 sm:mb-4">
                <img src="{{ asset('images/girls_lockers_logo.png') }}" alt="Girls Lockers" class="h-12 sm:h-16 w-auto mx-auto">
            </a>
            <h1 class="text-2xl sm:text-3xl font-bold text-white mb-1 sm:mb-2">🔒 Pago Seguro</h1>
            <p class="text-sm sm:text-base text-white/90">Completa tu pago de forma segura con Izipay</p>
        </div>

        <!-- Payment Container -->
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-2xl p-4 sm:p-8">
            <!-- Order Info -->
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg p-4 sm:p-6 mb-4 sm:mb-6 border border-purple-200">
                <div class="flex justify-between items-center mb-2 sm:mb-3">
                    <span class="text-sm sm:text-base text-gray-700 font-medium">Pedido:</span>
                    <span class="text-sm sm:text-base text-gray-900 font-semibold font-mono">{{ substr($orderId, 0, 13) }}...</span>
                </div>
                <div class="flex justify-between items-center mb-2 sm:mb-3">
                    <span class="text-sm sm:text-base text-gray-700 font-medium">Membresía:</span>
                    <span class="text-sm sm:text-base text-gray-900 font-semibold">
                        {{ $membershipType === 'monthly' ? 'Mensual (1 mes)' : 'Trimestral (3 meses)' }}
                    </span>
                </div>
                <div class="flex justify-between items-center pt-2 sm:pt-3 border-t-2 border-purple-200">
                    <span class="text-base sm:text-lg text-gray-900 font-bold">Total a pagar:</span>
                    <span class="text-xl sm:text-2xl font-bold text-purple-600">S/ {{ number_format($amount, 2) }}</span>
                </div>
            </div>

            @if($savedCards->count() > 0)
            <!-- Tarjetas Guardadas -->
            <div class="mb-4 sm:mb-6">
                <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-3 sm:mb-4">Mis tarjetas guardadas</h3>
                <div class="space-y-2 sm:space-y-3">
                    @foreach($savedCards as $card)
                    <div class="border-2 border-gray-200 rounded-lg p-3 sm:p-4 hover:border-purple-500 transition cursor-pointer"
                         onclick="alert('Función de pago con tarjeta guardada en desarrollo')">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3 sm:space-x-4">
                                <div class="w-10 h-7 sm:w-12 sm:h-8 bg-gradient-to-r from-purple-600 to-pink-600 rounded flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr($card->card_brand, 0, 4)) }}
                                </div>
                                <div>
                                    <p class="text-sm sm:text-base font-semibold text-gray-900">{{ $card->card_brand_name }} •••• {{ $card->card_last_four }}</p>
                                    <p class="text-xs sm:text-sm text-gray-600">
                                        Vence: {{ str_pad($card->card_expiry_month, 2, '0', STR_PAD_LEFT) }}/{{ $card->card_expiry_year }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-3 sm:mt-4 text-center">
                    <p class="text-xs sm:text-sm text-gray-600">o paga con una nueva tarjeta</p>
                </div>
            </div>
            @endif

            <!-- Formulario de pago embebido -->
            <div class="kr-embedded" kr-form-token="{{ $formToken }}"></div>

            <!-- Security Badge -->
            <div class="mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-gray-200 text-center">
                <p class="text-xs sm:text-sm text-gray-600 mb-1 sm:mb-2">
                    🔐 Tu información está protegida con encriptación SSL
                </p>
                <p class="text-xs text-gray-500">
                    Pago procesado por Izipay • Cancela cuando quieras
                </p>
            </div>
        </div>

        <!-- Back Button -->
        <div class="text-center mt-4 sm:mt-6">
            <a href="{{ route('purchase-membership') }}"
               class="inline-flex items-center text-sm sm:text-base text-white hover:text-gray-200 transition">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver a planes
            </a>
        </div>
    </div>
</body>
</html>
