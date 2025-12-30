<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Pago Seguro - Izipay</title>

    <!-- Izipay JavaScript SDK -->
    <script
        type="text/javascript"
        src="https://static.micuentaweb.pe/static/js/krypton-client/V4.0/stable/kr-payment-form.min.js"
        kr-public-key="{{ $publicKey }}"
        kr-post-url-success="{{ route('payment.success.callback') }}">
    </script>

    <!-- Tema Neon -->
    <link rel="stylesheet" href="https://static.micuentaweb.pe/static/js/krypton-client/V4.0/ext/neon-reset.min.css">
    <script type="text/javascript" src="https://static.micuentaweb.pe/static/js/krypton-client/V4.0/ext/neon.js"></script>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: 0;
        }

        .payment-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 100%;
            padding: 40px;
            margin: auto;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .payment-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .payment-header h1 {
            color: #333;
            font-size: 28px;
            margin: 0 0 10px 0;
        }

        .payment-header p {
            color: #666;
            font-size: 16px;
            margin: 0;
        }

        .order-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .order-info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .order-info-row:last-child {
            margin-bottom: 0;
            padding-top: 10px;
            border-top: 2px solid #dee2e6;
            font-weight: bold;
            font-size: 18px;
        }

        .order-info-label {
            color: #666;
        }

        .order-info-value {
            color: #333;
            font-weight: 500;
        }

        .kr-embedded {
            margin-top: 20px;
        }

        .security-badge {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
        }

        .security-badge img {
            height: 30px;
            margin: 0 10px;
            opacity: 0.7;
        }

        .security-text {
            color: #666;
            font-size: 14px;
            margin-top: 10px;
        }

        .saved-cards-section {
            width: 100%;
            margin-bottom: 30px;
        }

        .saved-cards-section h3 {
            color: #333;
            font-size: 18px;
            margin-bottom: 15px;
        }

        .saved-cards-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .saved-card {
            background: #f8f9fa;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
        }

        .saved-card:hover {
            border-color: #667eea;
            background: #f0f0ff;
        }

        .saved-card input[type="radio"] {
            margin-right: 12px;
        }

        .saved-card label {
            flex: 1;
            cursor: pointer;
            margin: 0;
        }

        .card-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 5px;
        }

        .card-brand {
            font-weight: 600;
            color: #333;
        }

        .card-number {
            color: #666;
            font-family: 'Courier New', monospace;
        }

        .card-badge {
            background: #667eea;
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
        }

        .card-expiry {
            font-size: 14px;
            color: #666;
        }

        .card-expired {
            color: #dc3545;
            font-weight: 600;
            margin-left: 10px;
        }

        .btn-pay {
            width: 100%;
            padding: 15px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 15px;
            transition: background 0.2s;
        }

        .btn-pay:hover {
            background: #5568d3;
        }

        @media (max-width: 640px) {
            .payment-container {
                padding: 20px;
            }

            .payment-header h1 {
                font-size: 24px;
            }

            .card-info {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <div class="payment-header">
            <h1>🔒 Pago Seguro</h1>
            <p>Completa tu pago de forma segura con Izipay</p>
        </div>

        <div class="order-info">
            <div class="order-info-row">
                <span class="order-info-label">Pedido:</span>
                <span class="order-info-value">{{ substr($orderId, 0, 8) }}...</span>
            </div>
            <div class="order-info-row">
                <span class="order-info-label">Total a pagar:</span>
                <span class="order-info-value">S/ {{ number_format($amount, 2) }}</span>
            </div>
        </div>

        @if(count($savedCards) > 0)
            <!-- Tarjetas guardadas -->
            <!-- NOTA: En sandbox, intentar pagar con token puede generar error PSP_610 -->
            <!-- Este es un problema de configuración de la cuenta Izipay, no del código -->
            <div class="saved-cards-section">
                <h3>Mis tarjetas guardadas</h3>
                <div class="saved-cards-list">
                    @foreach($savedCards as $card)
                        <div class="saved-card" onclick="selectSavedCard('{{ $card->id }}')">
                            <input type="radio" name="payment_method" value="saved_{{ $card->id }}" id="card_{{ $card->id }}">
                            <label for="card_{{ $card->id }}">
                                <div class="card-info">
                                    <span class="card-brand">{{ $card->card_brand_name }}</span>
                                    <span class="card-number">{{ $card->masked_card }}</span>
                                    @if($card->is_default)
                                        <span class="card-badge">Predeterminada</span>
                                    @endif
                                </div>
                                <div class="card-expiry">
                                    Exp: {{ $card->card_expiry_month }}/{{ $card->card_expiry_year }}
                                    @if($card->isExpired())
                                        <span class="card-expired">Expirada</span>
                                    @endif
                                </div>
                            </label>
                        </div>
                    @endforeach

                    <!-- Opción de nueva tarjeta -->
                    <div class="saved-card" onclick="selectNewCard()">
                        <input type="radio" name="payment_method" value="new_card" id="new_card" checked>
                        <label for="new_card">
                            <div class="card-info">
                                <span class="card-brand">➕ Usar nueva tarjeta</span>
                            </div>
                        </label>
                    </div>
                </div>

                <button id="pay-with-saved-card" class="btn-pay" style="display:none;">Pagar con tarjeta seleccionada</button>
            </div>
        @endif

        <!-- Formulario de pago embebido -->
        <div id="new-card-form" class="kr-embedded" kr-form-token="{{ $formToken }}"></div>

        <div class="security-badge">
            <p class="security-text">
                🔐 Tu información está protegida con encriptación SSL
            </p>
        </div>
    </div>

    <script>
        let selectedCardId = null;

        function selectSavedCard(cardId) {
            selectedCardId = cardId;
            document.getElementById('new-card-form').style.display = 'none';
            document.getElementById('pay-with-saved-card').style.display = 'block';
        }

        function selectNewCard() {
            selectedCardId = null;
            document.getElementById('new-card-form').style.display = 'block';
            document.getElementById('pay-with-saved-card').style.display = 'none';
        }

        document.getElementById('pay-with-saved-card')?.addEventListener('click', function() {
            // Crear formulario oculto para POST
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ url("/payment/pay-with-saved-card") }}';

            // Token CSRF
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            // Card ID
            const cardInput = document.createElement('input');
            cardInput.type = 'hidden';
            cardInput.name = 'card_id';
            cardInput.value = selectedCardId;
            form.appendChild(cardInput);

            // Order ID
            const orderInput = document.createElement('input');
            orderInput.type = 'hidden';
            orderInput.name = 'order_id';
            orderInput.value = '{{ $orderId }}';
            form.appendChild(orderInput);

            document.body.appendChild(form);
            form.submit();
        });
    </script>
</body>
</html>
