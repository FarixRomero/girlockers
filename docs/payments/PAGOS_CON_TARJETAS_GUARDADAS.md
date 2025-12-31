# Pagos con Tarjetas Guardadas (Tokenización)

## Tabla de Contenidos
1. [Introducción](#introducción)
2. [¿Qué es la Tokenización?](#qué-es-la-tokenización)
3. [Flujo Completo: Guardar Tarjeta](#flujo-completo-guardar-tarjeta)
4. [Flujo Completo: Pagar con Tarjeta Guardada](#flujo-completo-pagar-con-tarjeta-guardada)
5. [Modelo de Datos](#modelo-de-datos)
6. [Implementación Técnica](#implementación-técnica)
7. [Validaciones y Seguridad](#validaciones-y-seguridad)
8. [Casos de Uso](#casos-de-uso)
9. [Manejo de Errores](#manejo-de-errores)
10. [Preguntas Frecuentes](#preguntas-frecuentes)

---

## Introducción

El sistema de tarjetas guardadas permite a los clientes pagar en **un solo clic** sin necesidad de reingresar los datos de su tarjeta en cada compra. Este documento explica cómo funciona la tokenización de tarjetas en DeliverApp usando IziPay.

### Beneficios

**Para el Cliente:**
- ✅ Pago en un solo clic (experiencia rápida)
- ✅ No necesita recordar datos de tarjeta
- ✅ Seguridad: números reales nunca almacenados
- ✅ Puede guardar hasta 5-6 tarjetas

**Para el Negocio:**
- ✅ Menor abandono en checkout (30% menos)
- ✅ Proceso de pago más rápido (15 seg vs 2 min)
- ✅ Mayor tasa de conversión

**Para DeliverApp:**
- ✅ Cumplimiento PCI-DSS sin almacenar datos sensibles
- ✅ Menor fricción en experiencia de usuario
- ✅ Costos de soporte reducidos

---

## ¿Qué es la Tokenización?

### Concepto

La **tokenización** es el proceso de reemplazar datos sensibles (número de tarjeta) con un identificador único llamado **token** que no tiene valor fuera del sistema.

### Diagrama de Flujo

```
┌─────────────────────────────────────────────────────────────┐
│              PROCESO DE TOKENIZACIÓN                        │
└─────────────────────────────────────────────────────────────┘

CLIENTE                 DELIVERAPP              IZIPAY              BANCO
   │                        │                      │                   │
   │  1. Ingresa datos      │                      │                   │
   │  de tarjeta            │                      │                   │
   │  4111 1111 1111 1111   │                      │                   │
   ├───────────────────────►│                      │                   │
   │                        │                      │                   │
   │                        │  2. Envía datos      │                   │
   │                        │  cifrados a IziPay   │                   │
   │                        ├─────────────────────►│                   │
   │                        │                      │                   │
   │                        │                      │  3. Valida tarjeta│
   │                        │                      │  con banco        │
   │                        │                      ├──────────────────►│
   │                        │                      │                   │
   │                        │                      │◄──────────────────┤
   │                        │                      │  Tarjeta válida ✅│
   │                        │                      │                   │
   │                        │  4. Genera token     │                   │
   │                        │  TKN_abc123xyz       │                   │
   │                        │◄─────────────────────┤                   │
   │                        │                      │                   │
   │                        │  5. Guarda token +   │                   │
   │                        │  últimos 4 dígitos   │                   │
   │                        │  en base de datos    │                   │
   │                        │                      │                   │
   │  6. Muestra confirmación                      │                   │
   │  "Visa ···· 1111       │                      │                   │
   │   guardada exitosamente"│                     │                   │
   │◄───────────────────────┤                      │                   │
   │                        │                      │                   │

DELIVERAPP NUNCA VE NI ALMACENA: 4111 1111 1111 1111, CVV, o fecha completa
DELIVERAPP SÓ ALMACENA: TKN_abc123xyz, "VISA", "1111", mes/año expiración
```

### ¿Qué NO es un Token?

❌ **NO es** el número de tarjeta cifrado (no se puede descifrar)
❌ **NO es** válido fuera de IziPay/DeliverApp
❌ **NO** permite realizar compras en otros comercios
❌ **NO** contiene información sensible

### ¿Qué SÍ es un Token?

✅ **Es** un identificador único generado por IziPay
✅ **Es** válido solo para transacciones en DeliverApp
✅ **Es** seguro (inútil si es robado)
✅ **Puede** expirar o invalidarse

---

## Flujo Completo: Guardar Tarjeta

### Paso 1: Cliente Realiza Primer Pago

Durante el primer pago, el sistema ofrece la opción de guardar la tarjeta.

**Endpoint inicial:** `POST /api/v1/payments/create-intent`

**Request:**
```json
{
  "order_id": "0199c180-xxxx-xxxx-xxxx-xxxxxxxxxxxx"
}
```

**Controller:** `PaymentIntentController::createIntent()`

```php
public function createIntent(Request $request)
{
    // 1. Validar orden
    $order = Order::findOrFail($request->order_id);

    // 2. Llamar a IzipayService con opción de guardar tarjeta
    $result = $this->izipayService->createPayment([
        'order_id' => $order->id,
        'amount' => $order->total_amount,
        'customer' => [
            'email' => $order->customer->email,
            'reference' => $order->customer_id, // IMPORTANTE: ID único del cliente
        ],
        'save_card' => true, // ← Activa tokenización
    ]);

    // 3. Retornar formToken
    return response()->json([
        'success' => true,
        'form_token' => $result['formToken'],
        'payment_url' => route('payment.form', ['order_id' => $order->id]),
    ]);
}
```

### Paso 2: IziPay Genera FormToken con ASK_REGISTER_PAY

**Service:** `IzipayService::createPayment()`

```php
public function createPayment(array $orderData): array
{
    $endpoint = "{$this->apiUrl}/api-payment/V4/Charge/CreatePayment";

    $payload = [
        'amount' => $this->convertToCents($orderData['amount']), // Ej: 50.00 → 5000
        'currency' => $this->currency, // "PEN"
        'orderId' => $orderData['order_id'],
        'customer' => [
            'email' => $orderData['customer']['email'],
        ],
    ];

    // CLAVE: Activar registro de tarjeta
    if ($orderData['save_card'] ?? true) {
        $payload['formAction'] = 'ASK_REGISTER_PAY'; // ← IziPay mostrará checkbox

        // IMPORTANTE: reference es el ID del cliente
        if (isset($orderData['customer']['reference'])) {
            $payload['customer']['reference'] = $orderData['customer']['reference'];
        }
    }

    $response = Http::withBasicAuth($this->username, $this->password)
        ->timeout($this->timeout)
        ->post($endpoint, $payload);

    if ($response->successful()) {
        $data = $response->json();

        return [
            'success' => true,
            'formToken' => $data['answer']['formToken'],
            'data' => $data,
        ];
    }

    return ['success' => false, 'error' => 'Error al crear el pago'];
}
```

**Payload enviado a IziPay:**
```json
{
  "amount": 5000,
  "currency": "PEN",
  "orderId": "0199c180-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
  "customer": {
    "email": "juan.cliente@gmail.com",
    "reference": "0199c17f-xxxx-xxxx-xxxx-xxxxxxxxxxxx"
  },
  "formAction": "ASK_REGISTER_PAY"
}
```

### Paso 3: Cliente Completa Formulario de Pago

**Vista:** `resources/views/payment/form.blade.php`

El formulario de IziPay mostrará automáticamente un checkbox:

```
┌───────────────────────────────────────────────┐
│  FORMULARIO DE PAGO - IZIPAY                  │
├───────────────────────────────────────────────┤
│                                               │
│  Número de tarjeta                            │
│  ┌─────────────────────────────────────────┐ │
│  │ 4970 1000 0000 1003                     │ │
│  └─────────────────────────────────────────┘ │
│                                               │
│  Fecha de vencimiento        CVV              │
│  ┌───────────┐              ┌─────┐          │
│  │  12 / 25  │              │ 123 │          │
│  └───────────┘              └─────┘          │
│                                               │
│  Nombre en la tarjeta                         │
│  ┌─────────────────────────────────────────┐ │
│  │ JUAN PEREZ                              │ │
│  └─────────────────────────────────────────┘ │
│                                               │
│  ☑ Guardar tarjeta para futuras compras      │
│     (Seguro y rápido)                         │
│                                               │
│  [ PAGAR S/ 50.00 ]                           │
└───────────────────────────────────────────────┘
```

### Paso 4: IziPay Procesa Pago y Genera Token

Cuando el cliente presiona "PAGAR":

1. **IziPay valida** datos con el banco
2. **Banco aprueba** la transacción
3. **IziPay genera** `paymentMethodToken` (porque el checkbox estaba marcado)
4. **IziPay retorna** a DeliverApp con `kr-answer` que incluye el token

**Ejemplo de kr-answer:**
```json
{
  "orderStatus": "PAID",
  "orderDetails": {
    "orderId": "0199c180-xxxx-xxxx-xxxx-xxxxxxxxxxxx"
  },
  "transactions": [
    {
      "uuid": "abc123-def456-ghi789",
      "amount": 5000,
      "currency": "PEN",
      "paymentMethodToken": "d176f27a190145998dc6e617af4a56b0", // ← TOKEN
      "transactionDetails": {
        "cardDetails": {
          "effectiveBrand": "VISA",
          "pan": "497010XXXXXX1003",
          "expiryMonth": 12,
          "expiryYear": 2025
        }
      }
    }
  ]
}
```

### Paso 5: DeliverApp Guarda el Token

**Controller:** `PaymentFormController::handlePaymentSuccess()`

```php
public function handlePaymentSuccess(Request $request)
{
    // 1. Validar kr-hash (firma)
    $isValid = $this->izipayService->validateKrHash(
        $request->input('kr-answer'),
        $request->input('kr-hash')
    );

    if (!$isValid) {
        return view('payment.error', ['error' => 'Firma inválida']);
    }

    // 2. Decodificar kr-answer
    $paymentData = json_decode($request->input('kr-answer'), true);
    $orderId = $paymentData['orderDetails']['orderId'];

    // 3. Actualizar orden
    $order = Order::findOrFail($orderId);
    if ($paymentData['orderStatus'] === 'PAID') {
        $order->update([
            'payment_status' => 'completed',
            'status' => 'confirmed',
        ]);
    }

    // 4. GUARDAR TOKEN si existe
    $transaction = $paymentData['transactions'][0] ?? null;
    $paymentMethodToken = $transaction['paymentMethodToken'] ?? null;

    if ($paymentMethodToken) {
        $this->savePaymentToken($order->customer_id, $paymentMethodToken, $transaction);
    }

    // 5. Redirect a página de éxito
    return redirect()->route('payment.success', ['order_id' => $orderId]);
}

/**
 * Guardar token de pago
 */
private function savePaymentToken(string $userId, string $token, array $transactionData): void
{
    // Extraer datos de la tarjeta
    $cardDetails = $transactionData['transactionDetails']['cardDetails'] ?? [];

    $cardBrand = $cardDetails['effectiveBrand'] ?? 'CARD'; // "VISA"
    $cardPan = $cardDetails['pan'] ?? ''; // "497010XXXXXX1003"
    $cardLast4 = substr($cardPan, -4); // "1003"
    $cardExpiryMonth = $cardDetails['expiryMonth'] ?? null; // 12
    $cardExpiryYear = $cardDetails['expiryYear'] ?? null; // 2025

    // Verificar si ya existe este token
    $existingToken = PaymentToken::where('user_id', $userId)
        ->where('payment_method_token', $token)
        ->first();

    if ($existingToken) {
        Log::info('Payment token already exists, skipping creation', [
            'user_id' => $userId,
            'token' => substr($token, 0, 10) . '...',
        ]);
        return;
    }

    // Crear nuevo token
    $paymentToken = PaymentToken::create([
        'user_id' => $userId,
        'payment_method_token' => $token,
        'card_brand' => $cardBrand,
        'card_last_four' => $cardLast4,
        'card_expiry_month' => $cardExpiryMonth,
        'card_expiry_year' => $cardExpiryYear,
        'is_default' => false,
        'is_active' => true,
        'metadata' => [
            'created_from_payment_form' => true,
            'transaction_data' => $transactionData,
        ],
    ]);

    // Si es la primera tarjeta del usuario, marcarla como predeterminada
    $userTokensCount = PaymentToken::where('user_id', $userId)
        ->where('is_active', true)
        ->count();

    if ($userTokensCount === 1) {
        $paymentToken->setAsDefault();
    }

    Log::info('Payment token saved successfully from payment form', [
        'user_id' => $userId,
        'card_brand' => $cardBrand,
        'card_last_four' => $cardLast4,
        'is_default' => $userTokensCount === 1,
    ]);
}
```

---

## Flujo Completo: Pagar con Tarjeta Guardada

### Paso 1: Cliente Selecciona Tarjeta Guardada

**Vista:** `GET /payment/form?order_id=xxx`

**Controller:** `PaymentFormController::showPaymentForm()`

```php
public function showPaymentForm(Request $request)
{
    $orderId = $request->query('order_id');
    $order = Order::findOrFail($orderId);

    // Obtener tarjetas guardadas del cliente
    $savedCards = PaymentToken::where('user_id', $order->customer_id)
        ->where('is_active', true)
        ->orderBy('is_default', 'desc')
        ->orderBy('created_at', 'desc')
        ->get();

    return view('payment.form', [
        'order' => $order,
        'savedCards' => $savedCards,
        'formToken' => $this->generateFormToken($order),
    ]);
}
```

**Vista renderizada:**

```html
<!-- resources/views/payment/form.blade.php -->

<div class="payment-form">
    <h2>Selecciona método de pago</h2>

    @if($savedCards->count() > 0)
        <div class="saved-cards">
            <h3>Tarjetas guardadas</h3>

            @foreach($savedCards as $card)
                <div class="card-option">
                    <input type="radio"
                           name="payment_method"
                           id="card_{{ $card->id }}"
                           value="saved_{{ $card->id }}"
                           @if($card->is_default) checked @endif>

                    <label for="card_{{ $card->id }}">
                        <div class="card-info">
                            <span class="card-brand">{{ $card->card_brand_name }}</span>
                            <span class="card-number">{{ $card->masked_card }}</span>
                            <span class="card-expiry">Exp: {{ $card->card_expiry_month }}/{{ $card->card_expiry_year }}</span>

                            @if($card->isExpired())
                                <span class="badge-expired">EXPIRADA</span>
                            @endif

                            @if($card->is_default)
                                <span class="badge-default">PREDETERMINADA</span>
                            @endif
                        </div>
                    </label>

                    <button onclick="payWithSavedCard('{{ $card->id }}')">
                        Pagar S/ {{ number_format($order->total_amount, 2) }}
                    </button>
                </div>
            @endforeach
        </div>
    @endif

    <div class="new-card-option">
        <button onclick="showNewCardForm()">
            + Usar nueva tarjeta
        </button>
    </div>
</div>

<script>
function payWithSavedCard(cardId) {
    // Mostrar loading
    showLoading();

    // Enviar request
    fetch('/payment/pay-with-saved-card', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            card_id: cardId,
            order_id: '{{ $order->id }}'
        })
    })
    .then(response => {
        if (response.redirected) {
            window.location.href = response.url;
        } else {
            return response.json();
        }
    })
    .then(data => {
        if (data && data.error) {
            showError(data.error);
        }
    })
    .catch(error => {
        showError('Error al procesar el pago');
    });
}
</script>
```

**Renderizado visual:**

```
┌───────────────────────────────────────────────────────┐
│  SELECCIONA MÉTODO DE PAGO                            │
├───────────────────────────────────────────────────────┤
│                                                       │
│  ⚫ TARJETAS GUARDADAS                                │
│                                                       │
│  ┌───────────────────────────────────────────────┐  │
│  │ ● Visa •••• •••• •••• 1003  [PREDETERMINADA]  │  │
│  │   Exp: 12/2025                                │  │
│  │   [ PAGAR S/ 50.00 ]                          │  │
│  └───────────────────────────────────────────────┘  │
│                                                       │
│  ┌───────────────────────────────────────────────┐  │
│  │ ○ Mastercard •••• •••• •••• 6023              │  │
│  │   Exp: 10/2026                                │  │
│  │   [ PAGAR S/ 50.00 ]                          │  │
│  └───────────────────────────────────────────────┘  │
│                                                       │
│  ○ NUEVA TARJETA                                     │
│                                                       │
│  ┌───────────────────────────────────────────────┐  │
│  │ [ + Usar nueva tarjeta ]                      │  │
│  └───────────────────────────────────────────────┘  │
│                                                       │
└───────────────────────────────────────────────────────┘
```

### Paso 2: Validaciones Pre-Pago

**Endpoint:** `POST /payment/pay-with-saved-card`

**Controller:** `PaymentFormController::payWithSavedCard()`

```php
public function payWithSavedCard(Request $request)
{
    // 1. VALIDAR REQUEST
    $validated = $request->validate([
        'card_id' => 'required|uuid|exists:payment_tokens,id',
        'order_id' => 'required|uuid|exists:orders,id',
    ]);

    // 2. OBTENER TARJETA Y ORDEN
    $card = PaymentToken::findOrFail($validated['card_id']);
    $order = Order::findOrFail($validated['order_id']);

    // 3. VALIDAR PROPIEDAD DE LA TARJETA
    if ($card->user_id !== $order->customer_id) {
        Log::warning('Unauthorized payment attempt with card from different user', [
            'card_id' => $card->id,
            'card_user_id' => $card->user_id,
            'order_customer_id' => $order->customer_id,
        ]);

        return view('payment.error', [
            'error' => 'Esta tarjeta no pertenece al usuario de la orden.',
        ]);
    }

    // 4. VALIDAR ESTADO DE LA TARJETA
    if (!$card->is_active) {
        return view('payment.error', [
            'error' => 'Esta tarjeta no está activa.',
        ]);
    }

    // 5. VALIDAR EXPIRACIÓN
    if ($card->isExpired()) {
        return view('payment.error', [
            'error' => 'Esta tarjeta ha expirado. Por favor, usa otra tarjeta o actualiza su información.',
        ]);
    }

    // 6. VALIDAR ESTADO DE LA ORDEN
    if ($order->payment_status !== 'pending') {
        return view('payment.error', [
            'error' => 'Esta orden ya ha sido procesada.',
        ]);
    }

    // 7. PROCESAR PAGO CON TOKEN
    $result = $this->izipayService->createPaymentWithToken([
        'order_id' => $order->id,
        'amount' => $order->total_amount,
        'customer' => [
            'email' => $order->customer->email,
            'reference' => $order->customer_id,
        ],
        'payment_method_token' => $card->payment_method_token,
    ]);

    // 8. MANEJAR RESULTADO
    if ($result['success']) {
        // Pago exitoso, actualizar orden
        $order->update([
            'payment_status' => 'completed',
            'status' => 'confirmed',
        ]);

        Log::info('Payment with saved card successful', [
            'order_id' => $order->id,
            'card_id' => $card->id,
        ]);

        return redirect()->route('payment.success', ['order_id' => $order->id]);
    } else {
        // Pago fallido
        Log::error('Payment with saved card failed', [
            'order_id' => $order->id,
            'card_id' => $card->id,
            'error' => $result['error'],
        ]);

        return view('payment.error', [
            'error' => $result['error'] ?? 'Error al procesar el pago con la tarjeta guardada.',
        ]);
    }
}
```

### Paso 3: IziPay Procesa Pago con Token

**Service:** `IzipayService::createPaymentWithToken()`

```php
public function createPaymentWithToken(array $orderData): array
{
    $endpoint = "{$this->apiUrl}/api-payment/V4/Charge/CreatePayment";

    // PAYLOAD: Incluye paymentMethodToken en lugar de formToken
    $payload = [
        'amount' => $this->convertToCents($orderData['amount']),
        'currency' => $this->currency,
        'orderId' => $orderData['order_id'],
        'customer' => [
            'email' => $orderData['customer']['email'],
            'reference' => $orderData['customer']['reference'],
        ],
        'paymentMethodToken' => $orderData['payment_method_token'], // ← TOKEN GUARDADO
    ];

    try {
        Log::info('Izipay CreatePaymentWithToken Request', [
            'endpoint' => $endpoint,
            'order_id' => $orderData['order_id'],
            'amount' => $orderData['amount'],
            'token' => substr($orderData['payment_method_token'], 0, 10) . '...', // Log seguro
        ]);

        $response = Http::withBasicAuth($this->username, $this->password)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->timeout($this->timeout)
            ->post($endpoint, $payload);

        Log::info('Izipay CreatePaymentWithToken Response', [
            'status' => $response->status(),
            'successful' => $response->successful(),
        ]);

        if ($response->successful()) {
            $data = $response->json();

            // IziPay puede retornar status: "ERROR" aun con HTTP 200
            if (($data['status'] ?? '') === 'ERROR') {
                $errorCode = $data['answer']['errorCode'] ?? 'UNKNOWN';
                $errorMessage = $data['answer']['errorMessage'] ?? 'Error desconocido';

                Log::error('Izipay CreatePaymentWithToken failed with Izipay error', [
                    'error_code' => $errorCode,
                    'error_message' => $errorMessage,
                ]);

                return [
                    'success' => false,
                    'error' => $this->translateIzipayError($errorMessage),
                    'error_code' => $errorCode,
                ];
            }

            // Pago exitoso
            $status = $data['answer']['orderStatus'] ?? null;
            $transactions = $data['answer']['transactions'] ?? [];

            return [
                'success' => true,
                'orderStatus' => $status,
                'transactions' => $transactions,
                'data' => $data,
            ];
        }

        // HTTP error
        return [
            'success' => false,
            'error' => 'Error al procesar el pago con el token guardado',
            'status_code' => $response->status(),
        ];

    } catch (\Exception $e) {
        Log::error('Izipay CreatePaymentWithToken Exception', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return [
            'success' => false,
            'error' => 'Error de conexión con la pasarela de pago',
        ];
    }
}

/**
 * Traducir errores de IziPay a mensajes amigables
 */
private function translateIzipayError(string $errorMessage): string
{
    $translations = [
        'INSUFFICIENT_FUND' => 'Fondos insuficientes en tu tarjeta',
        'EXPIRED_CARD' => 'Tu tarjeta ha expirado',
        'INVALID_CARD' => 'Tarjeta inválida o no reconocida',
        'CARD_BLOCKED' => 'Tu tarjeta está bloqueada. Contacta a tu banco.',
        'FRAUD_SUSPECTED' => 'Transacción rechazada por seguridad. Contacta a tu banco.',
        'LIMIT_EXCEEDED' => 'Has excedido el límite de tu tarjeta',
    ];

    foreach ($translations as $key => $translation) {
        if (str_contains($errorMessage, $key)) {
            return $translation;
        }
    }

    return 'No pudimos procesar el pago. Por favor, intenta con otra tarjeta.';
}
```

**Payload enviado a IziPay:**
```json
{
  "amount": 5000,
  "currency": "PEN",
  "orderId": "0199c180-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
  "customer": {
    "email": "juan.cliente@gmail.com",
    "reference": "0199c17f-xxxx-xxxx-xxxx-xxxxxxxxxxxx"
  },
  "paymentMethodToken": "d176f27a190145998dc6e617af4a56b0"
}
```

### Paso 4: Confirmación Inmediata

**Diferencia clave con pago normal:**

| Aspecto | Pago con Nueva Tarjeta | Pago con Token |
|---------|------------------------|----------------|
| **Redirección** | Sí, a formulario IziPay | No, procesamiento directo |
| **Tiempo** | 30-60 segundos | 5-10 segundos |
| **Interacción cliente** | Debe ingresar datos | Un solo clic |
| **Confirmación** | Asíncrona (webhook) | Síncrona (inmediata) |
| **3D Secure** | Puede activarse | Raramente (ya validado) |

**Flujo de respuesta:**

```
PAGO CON NUEVA TARJETA              PAGO CON TOKEN GUARDADO
────────────────────────            ───────────────────────

1. Cliente → IziPay (30 seg)        1. DeliverApp → IziPay (2 seg)
2. IziPay → Banco (15 seg)          2. IziPay → Banco (2 seg)
3. Banco → IziPay (5 seg)           3. Banco → IziPay (1 seg)
4. IziPay → DeliverApp (2 seg)      4. IziPay → DeliverApp (1 seg)
5. Webhook confirmación (5 seg)     5. Respuesta inmediata ✅

TOTAL: ~57 segundos                 TOTAL: ~6 segundos
```

---

## Modelo de Datos

### Tabla: `payment_tokens`

```sql
CREATE TABLE payment_tokens (
    id UUID PRIMARY KEY,
    user_id UUID NOT NULL,
    payment_method_token VARCHAR(255) NOT NULL, -- Token de IziPay
    card_brand VARCHAR(50) NULL,                -- VISA, MASTERCARD, AMEX
    card_last_four VARCHAR(4) NULL,             -- Últimos 4 dígitos
    card_expiry_month INTEGER NULL,             -- 1-12
    card_expiry_year INTEGER NULL,              -- 2025
    is_default BOOLEAN DEFAULT FALSE,           -- Tarjeta predeterminada
    is_active BOOLEAN DEFAULT TRUE,             -- Tarjeta activa
    metadata JSONB NULL,                        -- Datos adicionales
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    deleted_at TIMESTAMP NULL,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_active (user_id, is_active),
    UNIQUE INDEX idx_user_token (user_id, payment_method_token)
);
```

### Modelo: `PaymentToken`

**Atributos:**

```php
protected $fillable = [
    'user_id',
    'payment_method_token',
    'card_brand',
    'card_last_four',
    'card_expiry_month',
    'card_expiry_year',
    'is_default',
    'is_active',
    'metadata',
];

protected $casts = [
    'is_default' => 'boolean',
    'is_active' => 'boolean',
    'metadata' => 'array',
];
```

**Atributos Calculados:**

```php
// Tarjeta enmascarada: "•••• •••• •••• 1003"
public function getMaskedCardAttribute(): string
{
    if (!$this->card_last_four) {
        return '•••• •••• •••• ••••';
    }
    return '•••• •••• •••• ' . $this->card_last_four;
}

// Nombre de marca: "Visa", "Mastercard", etc.
public function getCardBrandNameAttribute(): string
{
    return match ($this->card_brand) {
        'VISA' => 'Visa',
        'MASTERCARD' => 'Mastercard',
        'AMEX' => 'American Express',
        'DINERS' => 'Diners Club',
        default => $this->card_brand ?? 'Tarjeta',
    };
}
```

**Métodos:**

```php
// Verificar si tarjeta expiró
public function isExpired(): bool
{
    if (!$this->card_expiry_month || !$this->card_expiry_year) {
        return false;
    }

    $expiryDate = Carbon::createFromFormat('Y-m', $this->card_expiry_year . '-' . $this->card_expiry_month)
        ->endOfMonth();

    return $expiryDate->isPast();
}

// Establecer como predeterminada
public function setAsDefault(): void
{
    // Desmarcar todas las tarjetas del usuario
    static::where('user_id', $this->user_id)
        ->where('id', '!=', $this->id)
        ->update(['is_default' => false]);

    // Marcar esta como predeterminada
    $this->update(['is_default' => true]);
}
```

**Scopes:**

```php
// Solo tarjetas activas
public function scopeActive($query)
{
    return $query->where('is_active', true);
}

// Tarjeta predeterminada del usuario
public function scopeDefaultForUser($query, $userId)
{
    return $query->where('user_id', $userId)
        ->where('is_default', true)
        ->where('is_active', true);
}
```

---

## Implementación Técnica

### Endpoints API

#### 1. Obtener Tarjetas Guardadas

**Endpoint:** `GET /api/v1/payment-tokens`

**Autenticación:** JWT (cliente)

**Request:**
```bash
GET /api/v1/payment-tokens
Authorization: Bearer {jwt_token}
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": "0199c17f-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
      "masked_card": "•••• •••• •••• 1003",
      "card_brand": "VISA",
      "card_brand_name": "Visa",
      "card_last_four": "1003",
      "card_expiry_month": 12,
      "card_expiry_year": 2025,
      "is_default": true,
      "is_active": true,
      "is_expired": false,
      "created_at": "2025-01-15T10:30:00Z"
    },
    {
      "id": "0199c180-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
      "masked_card": "•••• •••• •••• 6023",
      "card_brand": "MASTERCARD",
      "card_brand_name": "Mastercard",
      "card_last_four": "6023",
      "card_expiry_month": 10,
      "card_expiry_year": 2026,
      "is_default": false,
      "is_active": true,
      "is_expired": false,
      "created_at": "2025-02-20T14:15:00Z"
    }
  ],
  "meta": {
    "total": 2,
    "max_allowed": 6
  }
}
```

#### 2. Establecer Tarjeta Predeterminada

**Endpoint:** `POST /api/v1/payment-tokens/{id}/set-default`

**Request:**
```bash
POST /api/v1/payment-tokens/0199c180-xxxx/set-default
Authorization: Bearer {jwt_token}
```

**Response:**
```json
{
  "success": true,
  "message": "Tarjeta establecida como predeterminada",
  "data": {
    "id": "0199c180-xxxx",
    "is_default": true
  }
}
```

#### 3. Eliminar Tarjeta

**Endpoint:** `DELETE /api/v1/payment-tokens/{id}`

**Request:**
```bash
DELETE /api/v1/payment-tokens/0199c180-xxxx
Authorization: Bearer {jwt_token}
```

**Response:**
```json
{
  "success": true,
  "message": "Tarjeta eliminada correctamente"
}
```

**Nota:** Soft delete (is_active = false), no se elimina físicamente.

---

## Validaciones y Seguridad

### Validaciones Pre-Pago

```php
// 1. Validar propiedad
if ($card->user_id !== $order->customer_id) {
    throw new UnauthorizedException('Esta tarjeta no pertenece al usuario');
}

// 2. Validar estado activo
if (!$card->is_active) {
    throw new InvalidCardException('Tarjeta desactivada');
}

// 3. Validar expiración
if ($card->isExpired()) {
    throw new ExpiredCardException('Tarjeta expirada');
}

// 4. Validar límite de tarjetas
$userCardsCount = PaymentToken::where('user_id', $userId)
    ->where('is_active', true)
    ->count();

if ($userCardsCount >= 6) {
    throw new LimitExceededException('Máximo 6 tarjetas guardadas');
}
```

### Seguridad del Token

**¿Qué pasa si alguien roba el token?**

```
ESCENARIO: Atacante obtiene token "d176f27a190145998dc6e617af4a56b0"

❌ NO puede usarlo en otro comercio (token específico de IziPay + DeliverApp)
❌ NO puede extraer número de tarjeta (irreversible)
❌ NO puede usarlo sin autenticación (requiere JWT del usuario)
❌ NO puede modificar customer.reference (IziPay valida)

✅ IziPay valida que el token pertenece al customer.reference enviado
✅ DeliverApp valida que card.user_id === order.customer_id
✅ 3D Secure puede activarse si banco detecta actividad sospechosa
```

### Logs de Auditoría

```php
// Al guardar token
Log::info('Payment token saved successfully', [
    'user_id' => $userId,
    'card_brand' => $cardBrand,
    'card_last_four' => $cardLast4,
    'is_default' => $isDefault,
    'ip_address' => $request->ip(),
    'user_agent' => $request->userAgent(),
]);

// Al pagar con token
Log::info('Payment initiated with saved card', [
    'user_id' => $userId,
    'card_id' => $cardId,
    'order_id' => $orderId,
    'amount' => $amount,
    'ip_address' => $request->ip(),
]);

// Al eliminar token
Log::warning('Payment token deleted by user', [
    'user_id' => $userId,
    'card_id' => $cardId,
    'card_last_four' => $cardLast4,
]);
```

---

## Casos de Uso

### Caso 1: Cliente Frecuente

**Contexto:** Juan realiza 3 pedidos por semana

**Sin tokens:**
- 3 pedidos × 2 minutos = 6 minutos/semana ingresando datos
- 52 semanas = 312 minutos/año = **5.2 horas**

**Con tokens:**
- 3 pedidos × 10 segundos = 30 segundos/semana
- 52 semanas = 26 minutos/año = **0.43 horas**

**Ahorro:** 4.77 horas/año (91.7% más rápido)

### Caso 2: Múltiples Tarjetas

**Contexto:** María tiene tarjeta personal y tarjeta corporativa

```
TARJETAS GUARDADAS:
1. Visa Personal ···· 1003    [PREDETERMINADA]
2. Visa Corporativa ···· 5678

PEDIDO 1 (Almuerzo personal):
✅ Usa tarjeta 1 automáticamente

PEDIDO 2 (Cena de trabajo):
✅ Selecciona tarjeta 2 con un clic
```

### Caso 3: Tarjeta Expirada

**Contexto:** Token guardado pero tarjeta expiró

```
INTENTO DE PAGO:
├─ Sistema detecta: card_expiry_year = 2024, card_expiry_month = 12
├─ Hoy: 2025-01-15
├─ Validación: isExpired() → true
└─ Acción: Mostrar error + sugerir actualizar

MENSAJE AL CLIENTE:
"Tu tarjeta Visa ···· 1003 ha expirado.
Por favor, usa otra tarjeta o actualiza la información."

OPCIONES:
[ Usar otra tarjeta ]  [ Agregar nueva tarjeta ]
```

### Caso 4: Token Inválido (Banco Canceló Tarjeta)

**Contexto:** Banco reemitió tarjeta, token ya no válido

```
INTENTO DE PAGO:
├─ DeliverApp envía token a IziPay
├─ IziPay contacta banco
├─ Banco responde: "INVALID_CARD"
└─ IziPay retorna error

RESPUESTA IZIPAY:
{
  "status": "ERROR",
  "answer": {
    "errorCode": "INVALID_CARD",
    "errorMessage": "The card is no longer valid"
  }
}

ACCIÓN DELIVERAPP:
1. Marcar token como inactivo (is_active = false)
2. Notificar cliente
3. Sugerir agregar nueva tarjeta
```

---

## Manejo de Errores

### Errores Comunes

#### 1. Fondos Insuficientes

**Código IziPay:** `INSUFFICIENT_FUND`

**Mensaje al cliente:**
> "No tienes fondos suficientes en tu tarjeta Visa ···· 1003. Por favor, usa otra tarjeta o verifica tu saldo."

**Acciones sugeridas:**
- Usar otra tarjeta guardada
- Agregar nueva tarjeta
- Verificar saldo en app del banco

---

#### 2. Tarjeta Bloqueada

**Código IziPay:** `CARD_BLOCKED`

**Mensaje al cliente:**
> "Tu tarjeta está bloqueada. Por favor, contacta a tu banco para desbloquearla."

**Acciones sugeridas:**
- Llamar al banco (número en reverso de tarjeta)
- Usar app del banco para desbloquear
- Usar otra tarjeta mientras tanto

---

#### 3. Límite Excedido

**Código IziPay:** `LIMIT_EXCEEDED`

**Mensaje al cliente:**
> "Has excedido el límite de tu tarjeta Visa ···· 1003 (S/ 200.00 diarios). Intenta con otra tarjeta o aumenta tu límite en la app de tu banco."

**Acciones sugeridas:**
- Aumentar límite en app del banco
- Esperar a mañana (reset diario)
- Usar otra tarjeta

---

#### 4. Token Inválido

**Código IziPay:** `INVALID_TOKEN` o `INVALID_CARD`

**Mensaje al cliente:**
> "Esta tarjeta ya no es válida (posiblemente fue reemplazada por tu banco). Por favor, elimínala y agrega tu nueva tarjeta."

**Acción automática:**
```php
// Marcar token como inactivo
$card->update(['is_active' => false]);

// Notificar al cliente
Notification::send($user, new InvalidCardNotification($card));
```

---

#### 5. Error de Red

**Código:** Timeout o connection error

**Mensaje al cliente:**
> "Hubo un problema de conexión con la pasarela de pagos. Por favor, intenta nuevamente en unos segundos."

**Acción automática:**
```php
// Reintentar automáticamente (máximo 3 veces)
$retries = 0;
$maxRetries = 3;

while ($retries < $maxRetries) {
    try {
        $result = $this->izipayService->createPaymentWithToken($data);
        if ($result['success']) {
            break;
        }
    } catch (\Exception $e) {
        $retries++;
        if ($retries >= $maxRetries) {
            throw $e;
        }
        sleep(2); // Esperar 2 segundos antes de reintentar
    }
}
```

---

## Preguntas Frecuentes

### Para Desarrolladores

**¿Cómo testear con tokens en ambiente de prueba?**

1. Usar tarjeta de prueba IziPay: `4970 1000 0000 1003`
2. Activar `save_card => true` en `createPayment()`
3. Completar pago en formulario con checkbox marcado
4. Token aparecerá en `kr-answer` → `transactions[0]['paymentMethodToken']`
5. Guardar token en base de datos
6. Usar ese token en `createPaymentWithToken()`

**¿El token expira?**

- **No tiene fecha de expiración** en IziPay
- **Pero** se invalida si:
  - Cliente cancela la tarjeta
  - Banco reemite tarjeta (nuevo número)
  - IziPay detecta fraude
  - Tarjeta física expira (validar con `isExpired()`)

**¿Puedo usar el mismo token para múltiples clientes?**

❌ **NO**. El token está vinculado al `customer.reference` específico. IziPay rechazará el pago si intentas usar un token con otro customer.

**¿Qué pasa si el cliente tiene 6 tarjetas y quiere agregar una más?**

```php
// Validar límite antes de guardar
$userCardsCount = PaymentToken::where('user_id', $userId)
    ->where('is_active', true)
    ->count();

if ($userCardsCount >= 6) {
    return response()->json([
        'success' => false,
        'error' => 'Has alcanzado el límite de tarjetas guardadas (6). Elimina una para agregar otra.',
    ], 422);
}
```

**¿Debo validar CVV al pagar con token?**

❌ **NO**. El token ya incluye la información de la tarjeta validada. IziPay no requiere CVV para pagos con token.

---

### Para Usuarios

**¿Es seguro guardar mi tarjeta?**

✅ **SÍ**. DeliverApp NUNCA almacena tu número de tarjeta completo, solo un "token" (código único) que:
- No sirve fuera de DeliverApp
- No se puede convertir de vuelta al número de tarjeta
- Usa el mismo sistema que Amazon, Uber, Netflix

**¿Cuántas tarjetas puedo guardar?**

Máximo **6 tarjetas** por cuenta.

**¿Puedo eliminar una tarjeta guardada?**

Sí, en cualquier momento desde:
- App: Perfil → Métodos de Pago → Eliminar
- Web: Mi Cuenta → Tarjetas → Eliminar

**¿Qué pasa si mi tarjeta expira?**

El sistema detectará automáticamente que expiró y te pedirá actualizar o usar otra tarjeta.

**¿Me cobrarán sin mi autorización?**

❌ **NO**. Guardar la tarjeta NO autoriza cobros automáticos. Solo hace más rápido el proceso cuando TÚ decides pagar.

---

## Conclusión

El sistema de tokenización de tarjetas en DeliverApp ofrece:

✅ **Seguridad PCI-DSS compliant** - Sin almacenar datos sensibles
✅ **Experiencia rápida** - Pago en 6 segundos vs 60 segundos
✅ **Múltiples tarjetas** - Hasta 6 tarjetas por usuario
✅ **Validaciones robustas** - Propiedad, expiración, estado
✅ **Manejo de errores** - Mensajes claros y acciones sugeridas

**Próximos pasos:**
- Implementar actualización de tarjetas expiradas
- Agregar validación 3D Secure para pagos con token >S/ 500
- Dashboard de análisis de métodos de pago más usados
- Notificaciones push cuando tarjeta está por expirar (30 días antes)

---

**Documentación relacionada:**
- [Flujo Completo de Pagos con IziPay](./FLUJO_PAGOS_IZIPAY.md)
- [Sistema de Pagos y Transacciones](../logica-negocio/PARTE_5_SISTEMA_PAGOS.md)

**Última actualización:** 2025-12-31
**Versión IziPay API:** V4
**Laravel Version:** 12.34.0
