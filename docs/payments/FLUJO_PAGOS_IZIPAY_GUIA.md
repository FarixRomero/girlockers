# Documentación: Flujo Completo de Pagos con Izipay

## Tabla de Contenidos
1. [Arquitectura General](#arquitectura-general)
2. [Flujo 1: Pago con Nueva Tarjeta](#flujo-1-pago-con-nueva-tarjeta)
3. [Flujo 2: Pago con Tarjeta Guardada](#flujo-2-pago-con-tarjeta-guardada)
4. [Validación de Seguridad](#validación-de-seguridad)
5. [Webhook IPN](#webhook-ipn)
6. [Tokenización de Tarjetas](#tokenización-de-tarjetas)
7. [Modelos de Base de Datos](#modelos-de-base-de-datos)
8. [Configuración](#configuración)

---

## Arquitectura General

```
┌─────────────────┐
│   Cliente Web   │
└────────┬────────┘
         │
         ▼
┌─────────────────────────────────────────────────────────────┐
│                    Laravel Backend                           │
│                                                              │
│  ┌──────────────────┐         ┌──────────────────┐         │
│  │ PaymentIntent    │────────▶│ IzipayService    │         │
│  │ Controller       │         │                  │         │
│  └──────────────────┘         └─────────┬────────┘         │
│           │                              │                   │
│           ▼                              │                   │
│  ┌──────────────────┐                   │                   │
│  │ PaymentForm      │                   │                   │
│  │ Controller       │◀──────────────────┘                   │
│  └─────────┬────────┘                                       │
│            │                                                 │
│            ▼                                                 │
│  ┌──────────────────┐         ┌──────────────────┐         │
│  │ Webhook          │◀────────│ Izipay IPN       │         │
│  │ Controller       │         │                  │         │
│  └──────────────────┘         └──────────────────┘         │
│                                                              │
│  ┌──────────────────────────────────────────────┐          │
│  │         Base de Datos                         │          │
│  │  - orders                                     │          │
│  │  - payment_tokens                             │          │
│  │  - users                                      │          │
│  └──────────────────────────────────────────────┘          │
└─────────────────────────────────────────────────────────────┘
         │                              ▲
         ▼                              │
┌─────────────────┐                    │
│  Izipay API V4  │────────────────────┘
└─────────────────┘
```

---

## Flujo 1: Pago con Nueva Tarjeta

### 1.1 Creación de Intent de Pago

**Endpoint:** `POST /api/v1/payments/create-intent`

```json
{
  "order_id": "0199c180-xxxx-xxxx-xxxx-xxxxxxxxxxxx"
}
```

**Controller:** `PaymentIntentController::createIntent()`

**Proceso:**
1. Valida que la orden exista
2. Verifica que `payment_status` sea `pending`
3. Crea formToken con Izipay usando `IzipayService::createPayment()`
4. Retorna URL del formulario de pago

**Respuesta:**
```json
{
  "success": true,
  "payment_url": "http://localhost:8000/payment/form?order_id=xxx",
  "form_token": "xxxxxxxxxx",
  "order": {
    "id": "0199c180-xxxx",
    "payment_status": "pending",
    "total_amount": 13.48
  }
}
```

---

### 1.2 Visualización del Formulario de Pago

**Ruta:** `GET /payment/form?order_id=xxx`

**Controller:** `PaymentFormController::showPaymentForm()`

**Proceso:**
1. Busca la orden por `order_id`
2. Verifica estado de pago:
   - Si `completed` → muestra página de éxito
   - Si `processing` o `failed` → muestra error
3. Obtiene tarjetas guardadas del usuario (`customer_id`)
4. Genera nuevo `formToken` llamando a `IzipayService::createPayment()` con:
   ```php
   [
       'order_id' => $order->id,
       'amount' => $order->total_amount,
       'customer' => [
           'email' => $order->customer->email,
           'reference' => $order->customer_id,
       ],
       'save_card' => true, // Activa formAction: "ASK_REGISTER_PAY"
   ]
   ```
5. Renderiza vista `payment.form` con:
   - `formToken` (para Izipay embedded form)
   - `publicKey` (credencial de Izipay)
   - `orderId`
   - `amount`
   - `savedCards` (tarjetas previamente guardadas)

**Vista:** `resources/views/payment/form.blade.php`

**Componentes:**
- **Izipay Embedded Form SDK:**
  ```html
  <script src="https://static.micuentaweb.pe/static/js/krypton-client/V4.0/stable/kr-payment-form.min.js"
          kr-public-key="{{ $publicKey }}"
          kr-post-url-success="{{ url('/payment/success') }}">
  </script>
  ```
- **Formulario embebido:**
  ```html
  <div class="kr-embedded" kr-form-token="{{ $formToken }}"></div>
  ```
- **Lista de tarjetas guardadas** (si existen)
- **Selector de método de pago** (nueva tarjeta vs guardada)

---

### 1.3 Proceso de Pago (Cliente ingresa datos de tarjeta)

**Flujo en el navegador:**

1. **Usuario completa formulario Izipay:**
   - Número de tarjeta
   - Fecha de expiración
   - CVV
   - (Opcional) Checkbox "Guardar tarjeta"

2. **Izipay procesa el pago:**
   - Valida datos de tarjeta
   - Realiza autorización con banco
   - Genera `paymentMethodToken` si el usuario autorizó guardar

3. **POST a `/payment/success`:**
   - Izipay hace POST automático con:
     - `kr-answer` (JSON con datos del pago)
     - `kr-hash` (firma HMAC-SHA-256)

---

### 1.4 Retorno Exitoso del Pago

**Ruta:** `POST /payment/success`

**Controller:** `PaymentFormController::handlePaymentSuccess()`

**Proceso:**

1. **Valida firma kr-hash:**
   ```php
   $isValid = $this->izipayService->validateKrHash($krAnswer, $krHash);
   ```
   - Usa `hmacKey` para validar
   - Aplica `str_replace('\/', '/', $krAnswer)` según docs de Izipay

2. **Decodifica kr-answer:**
   ```php
   $paymentData = json_decode($krAnswer, true);
   $orderId = $paymentData['orderDetails']['orderId'];
   $orderStatus = $paymentData['orderStatus']; // "PAID"
   ```

3. **Actualiza orden inmediatamente:**
   ```php
   if ($orderStatus === 'PAID' && $order->payment_status !== 'completed') {
       $order->update([
           'payment_status' => 'completed',
           'status' => 'confirmed',
       ]);
   }
   ```

4. **Guarda token de pago (si existe):**
   ```php
   $transaction = $paymentData['transactions'][0] ?? null;
   $paymentMethodToken = $transaction['paymentMethodToken'] ?? null;

   if ($paymentMethodToken) {
       $this->savePaymentToken($order->customer_id, $paymentMethodToken, $transaction);
   }
   ```

5. **Redirect a GET:**
   ```php
   return redirect()->route('payment.success', ['order_id' => $orderId]);
   ```

---

### 1.5 Página de Confirmación

**Ruta:** `GET /payment/success?order_id=xxx`

**Controller:** `PaymentFormController::handlePaymentSuccess()` (mismo método, detecta GET)

**Vista:** `resources/views/payment/success.blade.php`

**Características:**
- Muestra estado del pago: `PAID` o `PENDING`
- Polling cada 1 segundo a `/payment/status/{orderId}`
- Timeout de 2 minutos
- Si el webhook actualiza el estado, muestra confirmación final

---

## Flujo 2: Pago con Tarjeta Guardada

### 2.1 Selección de Tarjeta

En `/payment/form?order_id=xxx`, el usuario ve:

```html
@foreach($savedCards as $card)
  <div class="saved-card">
    <input type="radio" name="payment_method" value="saved_{{ $card->id }}">
    <label>
      {{ $card->card_brand_name }} •••• {{ $card->card_last_four }}
      Exp: {{ $card->card_expiry_month }}/{{ $card->card_expiry_year }}
    </label>
  </div>
@endforeach
```

**JavaScript:**
```javascript
function selectSavedCard(cardId) {
    selectedCardId = cardId;
    document.getElementById('new-card-form').style.display = 'none';
    document.getElementById('pay-with-saved-card').style.display = 'block';
}
```

---

### 2.2 Procesamiento de Pago con Token

**Ruta:** `POST /payment/pay-with-saved-card`

**Controller:** `PaymentFormController::payWithSavedCard()`

**Validaciones:**

1. **Validar request:**
   ```php
   $validated = $request->validate([
       'card_id' => 'required|uuid|exists:payment_tokens,id',
       'order_id' => 'required|uuid|exists:orders,id',
   ]);
   ```

2. **Verificar propiedad:**
   ```php
   if ($card->user_id !== $order->customer_id) {
       return view('payment.error', ['error' => 'Tarjeta no pertenece al usuario']);
   }
   ```

3. **Verificar expiración:**
   ```php
   if ($card->isExpired()) {
       return view('payment.error', ['error' => 'Tarjeta expirada']);
   }
   ```

4. **Verificar estado activo:**
   ```php
   if (!$card->is_active) {
       return view('payment.error', ['error' => 'Tarjeta no está activa']);
   }
   ```

**Llamada a Izipay:**

```php
$result = $this->izipayService->createPaymentWithToken([
    'order_id' => $order->id,
    'amount' => $order->total_amount,
    'customer' => [
        'email' => $order->customer->email,
        'reference' => $order->customer_id,
    ],
    'payment_method_token' => $card->payment_method_token,
]);
```

**Service:** `IzipayService::createPaymentWithToken()`

**Endpoint Izipay:** `POST /api-payment/V4/Charge/CreatePayment`

**Payload:**
```json
{
  "amount": 1348,
  "currency": "PEN",
  "orderId": "0199c180-xxxx",
  "customer": {
    "email": "juan.cliente@gmail.com",
    "reference": "0199c17f-xxxx"
  },
  "paymentMethodToken": "d176f27a190145998dc6e617af4a56b0"
}
```

**Respuesta exitosa:**
```php
if ($result['success']) {
    $order->update([
        'payment_status' => 'completed',
        'status' => 'confirmed',
    ]);

    return redirect()->route('payment.success', ['order_id' => $order->id]);
}
```

**Status HTTP:** `302 Found` (redirect POST-Redirect-GET)

---

## Validación de Seguridad

### kr-hash Validation (Browser Returns)

**Método:** `IzipayService::validateKrHash()`

**Clave usada:** `hmacKey` (Clave HMAC-SHA-256)

**Algoritmo:**
```php
// Limpiar escapes de "/"
$krAnswer = str_replace('\/', '/', $krAnswer);

// Calcular hash
$calculatedHash = hash_hmac('sha256', $krAnswer, $this->hmacKey);

// Comparar de forma segura
return hash_equals($calculatedHash, $krHash);
```

**Usado en:**
- `PaymentFormController::handlePaymentSuccess()` (POST)

---

### IPN Signature Validation (Webhooks)

**Método:** `IzipayService::validateIpnSignature()`

**Clave usada:** `password` (API Password)

**Algoritmo:**
```php
$calculatedSignature = hash_hmac('sha256', $requestBody, $this->password);
return hash_equals($calculatedSignature, $signature);
```

**Usado en:**
- `WebhookController::handleIpnV4()`

---

## Webhook IPN

### Configuración en Izipay

**URL:** `https://tu-dominio.com/api/v1/webhook/izipay/ipn`

**Eventos recibidos:**
- Pago autorizado
- Pago capturado
- Pago rechazado
- Reembolso

---

### Procesamiento del Webhook

**Ruta:** `POST /api/v1/webhook/izipay/ipn`

**Controller:** `WebhookController::handleIpnV4()`

**Proceso:**

1. **Validar firma:**
   ```php
   if (!$this->izipayService->validateIpnSignature($krAnswer, $krHash)) {
       return response()->json(['error' => 'Invalid signature'], 400);
   }
   ```

2. **Decodificar kr-answer:**
   ```php
   $paymentData = json_decode($krAnswer, true);
   $orderId = $paymentData['orderDetails']['orderId'];
   ```

3. **Actualizar orden:**
   ```php
   $order->update([
       'payment_status' => 'completed',
       'status' => 'confirmed',
   ]);
   ```

4. **Guardar paymentMethodToken (si existe):**
   ```php
   $paymentMethodToken = $transactions[0]['paymentMethodToken'] ?? null;
   if ($paymentMethodToken && $order->customer_id) {
       $this->savePaymentToken($order->customer_id, $paymentMethodToken, $transactions[0]);
   }
   ```

5. **Responder a Izipay:**
   ```php
   return response()->json(['status' => 'success'], 200);
   ```

---

## Tokenización de Tarjetas

### Activación del Registro de Tarjetas

**En IzipayService::createPayment():**

```php
if ($orderData['save_card'] ?? true) {
    $payload['formAction'] = 'ASK_REGISTER_PAY';

    if (isset($orderData['customer']['reference'])) {
        $payload['customer']['reference'] = $orderData['customer']['reference'];
    }
}
```

**Efecto:**
- Izipay muestra checkbox "Guardar tarjeta para futuros pagos"
- Si usuario acepta, genera `paymentMethodToken`
- Token viene en `kr-answer` → `transactions[0]['paymentMethodToken']`

---

### Almacenamiento del Token

**Método:** `PaymentFormController::savePaymentToken()`

**Proceso:**

1. **Extraer datos de la transacción:**
   ```php
   $cardDetails = $transactionData['transactionDetails']['cardDetails'] ?? [];
   $cardBrand = $cardDetails['effectiveBrand']; // "VISA"
   $cardPan = $cardDetails['pan']; // "497011XXXXXX1003"
   $cardLast4 = substr($cardPan, -4); // "1003"
   $cardExpiryMonth = $cardDetails['expiryMonth']; // 12
   $cardExpiryYear = $cardDetails['expiryYear']; // 2025
   ```

2. **Verificar si ya existe:**
   ```php
   $existingToken = PaymentToken::where('user_id', $userId)
       ->where('payment_method_token', $paymentMethodToken)
       ->first();
   ```

3. **Crear token:**
   ```php
   $paymentToken = PaymentToken::create([
       'user_id' => $userId,
       'payment_method_token' => $paymentMethodToken,
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
   ```

4. **Marcar como predeterminada (si es la primera):**
   ```php
   $userTokensCount = PaymentToken::where('user_id', $userId)
       ->where('is_active', true)
       ->count();

   if ($userTokensCount === 1) {
       $paymentToken->setAsDefault();
   }
   ```

---

## Modelos de Base de Datos

### Tabla: `orders`

```php
Schema::create('orders', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->uuid('customer_id');
    $table->uuid('business_location_id');
    $table->decimal('total_amount', 10, 2);
    $table->decimal('subtotal', 10, 2);
    $table->decimal('tax_amount', 10, 2)->default(0);
    $table->decimal('delivery_fee', 10, 2)->default(0);
    $table->enum('status', ['pending', 'confirmed', 'preparing', 'ready', 'in_delivery', 'delivered', 'cancelled']);
    $table->enum('payment_status', ['pending', 'processing', 'completed', 'failed', 'refunded']);
    $table->timestamps();
    $table->softDeletes();
});
```

**Estados de pago:**
- `pending` - Orden creada, esperando pago
- `processing` - (NO SE USA) Reserved para futuros casos
- `completed` - Pago confirmado
- `failed` - Pago rechazado
- `refunded` - Reembolsado

---

### Tabla: `payment_tokens`

```php
Schema::create('payment_tokens', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->uuid('user_id');
    $table->string('payment_method_token'); // Token de Izipay
    $table->string('card_brand')->nullable(); // VISA, MASTERCARD
    $table->string('card_last_four', 4)->nullable(); // 1003
    $table->integer('card_expiry_month')->nullable(); // 12
    $table->integer('card_expiry_year')->nullable(); // 2025
    $table->boolean('is_default')->default(false);
    $table->boolean('is_active')->default(true);
    $table->json('metadata')->nullable();
    $table->timestamps();
    $table->softDeletes();

    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    $table->index(['user_id', 'is_active']);
});
```

---

### Modelo: `PaymentToken`

**Atributos calculados:**

```php
public function getMaskedCardAttribute(): string
{
    if (!$this->card_last_four) {
        return '•••• •••• •••• ••••';
    }
    return '•••• •••• •••• ' . $this->card_last_four;
}

public function getCardBrandNameAttribute(): string
{
    return match($this->card_brand) {
        'VISA' => 'Visa',
        'MASTERCARD' => 'Mastercard',
        'AMEX' => 'American Express',
        default => ucfirst(strtolower($this->card_brand ?? 'Tarjeta')),
    };
}
```

**Métodos:**

```php
public function isExpired(): bool
{
    if (!$this->card_expiry_month || !$this->card_expiry_year) {
        return false;
    }
    $expiryDate = Carbon::createFromFormat('Y-m', $this->card_expiry_year . '-' . $this->card_expiry_month)
        ->endOfMonth();
    return $expiryDate->isPast();
}

public function setAsDefault(): void
{
    static::where('user_id', $this->user_id)
        ->where('id', '!=', $this->id)
        ->update(['is_default' => false]);

    $this->update(['is_default' => true]);
}
```

---

## Configuración

### Archivo: `config/izipay.php`

```php
return [
    'mode' => env('IZIPAY_MODE', 'test'), // 'test' o 'production'

    'test' => [
        'username' => env('IZIPAY_TEST_USERNAME'),
        'password' => env('IZIPAY_TEST_PASSWORD'),
        'public_key' => env('IZIPAY_TEST_PUBLIC_KEY'),
        'hmac_key' => env('IZIPAY_TEST_HMAC_KEY'),
        'api_url' => 'https://api.micuentaweb.pe',
    ],

    'production' => [
        'username' => env('IZIPAY_PROD_USERNAME'),
        'password' => env('IZIPAY_PROD_PASSWORD'),
        'public_key' => env('IZIPAY_PROD_PUBLIC_KEY'),
        'hmac_key' => env('IZIPAY_PROD_HMAC_KEY'),
        'api_url' => 'https://api.micuentaweb.pe',
    ],

    'webhook_url' => env('IZIPAY_WEBHOOK_URL'),
    'currency' => 'PEN',
    'timeout' => 30,
];
```

---

### Variables de Entorno (.env)

```bash
# Izipay Configuration
IZIPAY_MODE=test

# Test Credentials
IZIPAY_TEST_USERNAME=18030334
IZIPAY_TEST_PASSWORD=testpassword_xxxxxxxxx
IZIPAY_TEST_PUBLIC_KEY=18030334:testpublickey_xxxxxxxxx
IZIPAY_TEST_HMAC_KEY=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx

# Production Credentials (cuando estés en producción)
IZIPAY_PROD_USERNAME=
IZIPAY_PROD_PASSWORD=
IZIPAY_PROD_PUBLIC_KEY=
IZIPAY_PROD_HMAC_KEY=

# Webhook
IZIPAY_WEBHOOK_URL=https://tu-dominio.com/api/v1/webhook/izipay/ipn
```

---

## Rutas Web

```php
// Payment Form Routes
Route::prefix('payment')->name('payment.')->group(function () {
    Route::get('/form', [PaymentFormController::class, 'showPaymentForm'])
        ->name('form');

    Route::post('/pay-with-saved-card', [PaymentFormController::class, 'payWithSavedCard'])
        ->name('pay-with-saved-card');

    Route::match(['get', 'post'], '/success', [PaymentFormController::class, 'handlePaymentSuccess'])
        ->name('success');

    Route::match(['get', 'post'], '/error', [PaymentFormController::class, 'handlePaymentError'])
        ->name('error');

    Route::get('/status/{orderId}', [PaymentFormController::class, 'checkPaymentStatus'])
        ->name('status');
});
```

---

## Rutas API

```php
// Payment Intent
Route::post('/payment/intent', [PaymentIntentController::class, 'createIntent']);

// Webhook
Route::post('/webhook/izipay/ipn', [WebhookController::class, 'handleIpnV4']);
```

---

## Exclusiones CSRF

**Archivo:** `bootstrap/app.php`

```php
$middleware->validateCsrfTokens(except: [
    'payment/success',  // POST desde Izipay
    'payment/error',    // POST desde Izipay
]);
```

---

## Diagrama de Flujo Completo

```
┌───────────────────────────────────────────────────────────────────────┐
│                         INICIO: Cliente crea orden                     │
└─────────────────────────────────┬─────────────────────────────────────┘
                                  │
                                  ▼
                    ┌──────────────────────────────────────┐
                    │ POST /api/v1/payments/create-intent │
                    │      { order_id: "xxx" }             │
                    └──────────────┬───────────────────────┘
                                  │
                                  ▼
                    ┌─────────────────────────────┐
                    │ PaymentIntentController     │
                    │ - Valida orden              │
                    │ - Llama IzipayService       │
                    └──────────────┬──────────────┘
                                  │
                                  ▼
                    ┌─────────────────────────────┐
                    │ IzipayService::createPayment│
                    │ POST /V4/Charge/CreatePayment
                    │ formAction: ASK_REGISTER_PAY│
                    └──────────────┬──────────────┘
                                  │
                                  ▼
                    ┌─────────────────────────────┐
                    │ Retorna formToken           │
                    └──────────────┬──────────────┘
                                  │
                                  ▼
                    ┌─────────────────────────────┐
                    │ GET /payment/form?order_id  │
                    └──────────────┬──────────────┘
                                  │
                                  ▼
                    ┌─────────────────────────────┐
                    │ PaymentFormController       │
                    │ - Carga orden               │
                    │ - Carga tarjetas guardadas  │
                    │ - Genera nuevo formToken    │
                    └──────────────┬──────────────┘
                                  │
                                  ▼
          ┌───────────────────────┴───────────────────────┐
          │                                               │
          ▼                                               ▼
┌──────────────────────┐                    ┌──────────────────────┐
│ OPCIÓN A:            │                    │ OPCIÓN B:            │
│ Nueva Tarjeta        │                    │ Tarjeta Guardada     │
└──────────┬───────────┘                    └──────────┬───────────┘
          │                                            │
          ▼                                            ▼
┌──────────────────────┐                    ┌──────────────────────┐
│ Usuario completa     │                    │ Usuario selecciona   │
│ formulario Izipay    │                    │ tarjeta y hace clic  │
│ embedded             │                    │ "Pagar"              │
└──────────┬───────────┘                    └──────────┬───────────┘
          │                                            │
          ▼                                            ▼
┌──────────────────────┐                    ┌──────────────────────┐
│ Izipay procesa pago  │                    │ POST /payment/       │
│ y hace POST a        │                    │ pay-with-saved-card  │
│ /payment/success     │                    └──────────┬───────────┘
│ con kr-answer        │                               │
│ y kr-hash            │                               ▼
└──────────┬───────────┘                    ┌──────────────────────┐
          │                                 │ PaymentFormController│
          │                                 │ ::payWithSavedCard() │
          │                                 │ - Valida propiedad   │
          │                                 │ - Valida expiración  │
          │                                 │ - Llama createPayment│
          │                                 │   WithToken()        │
          │                                 └──────────┬───────────┘
          │                                            │
          │                                            ▼
          │                                 ┌──────────────────────┐
          │                                 │ IzipayService::      │
          │                                 │ createPaymentWith    │
          │                                 │ Token()              │
          │                                 │ POST /V4/Charge/     │
          │                                 │ CreatePayment        │
          │                                 │ { paymentMethodToken}│
          │                                 └──────────┬───────────┘
          │                                            │
          │                                            ▼
          │                                 ┌──────────────────────┐
          │                                 │ Actualiza orden a    │
          │                                 │ completed            │
          │                                 └──────────┬───────────┘
          │                                            │
          └────────────────────────────────────────────┘
                                  │
                                  ▼
                    ┌─────────────────────────────┐
                    │ POST /payment/success       │
                    │ kr-answer + kr-hash         │
                    └──────────────┬──────────────┘
                                  │
                                  ▼
                    ┌─────────────────────────────┐
                    │ validateKrHash()            │
                    │ (usa hmacKey)               │
                    └──────────────┬──────────────┘
                                  │
                                  ▼
                    ┌─────────────────────────────┐
                    │ Decodifica kr-answer        │
                    │ Extrae orderId, orderStatus │
                    └──────────────┬──────────────┘
                                  │
                                  ▼
                    ┌─────────────────────────────┐
                    │ if orderStatus == "PAID":   │
                    │   order.payment_status =    │
                    │     'completed'             │
                    └──────────────┬──────────────┘
                                  │
                                  ▼
                    ┌─────────────────────────────┐
                    │ if paymentMethodToken:      │
                    │   savePaymentToken()        │
                    └──────────────┬──────────────┘
                                  │
                                  ▼
                    ┌─────────────────────────────┐
                    │ 302 Redirect to             │
                    │ GET /payment/success        │
                    │ ?order_id=xxx               │
                    └──────────────┬──────────────┘
                                  │
                                  ▼
                    ┌─────────────────────────────┐
                    │ Vista payment.success       │
                    │ - Muestra "Pago completado" │
                    │ - Polling a /payment/status │
                    │   (cada 1 seg, 2 min max)   │
                    └──────────────┬──────────────┘
                                  │
                                  ▼
                    ┌─────────────────────────────┐
                    │ (En paralelo)               │
                    │ Izipay envía IPN webhook    │
                    └──────────────┬──────────────┘
                                  │
                                  ▼
                    ┌─────────────────────────────┐
                    │ POST /api/v1/webhook/       │
                    │ izipay/ipn                  │
                    └──────────────┬──────────────┘
                                  │
                                  ▼
                    ┌─────────────────────────────┐
                    │ validateIpnSignature()      │
                    │ (usa password)              │
                    └──────────────┬──────────────┘
                                  │
                                  ▼
                    ┌─────────────────────────────┐
                    │ Actualiza orden (redundante)│
                    │ Guarda token (redundante)   │
                    └──────────────┬──────────────┘
                                  │
                                  ▼
                    ┌─────────────────────────────┐
                    │ Return 200 OK               │
                    └─────────────────────────────┘
```

---

## Casos de Error

### Error: Orden no encontrada
```php
return view('payment.error', [
    'error' => 'Orden no encontrada. Por favor, inicia el proceso de pago nuevamente.',
]);
```

### Error: Orden ya pagada
```php
if ($order->payment_status === 'completed') {
    return view('payment.success', [
        'orderStatus' => 'PAID',
        'orderId' => $order->id,
        'currentPaymentStatus' => $order->payment_status,
    ]);
}
```

### Error: Pago en proceso o fallido
```php
if (in_array($order->payment_status, ['processing', 'failed'])) {
    return view('payment.error', [
        'error' => $order->payment_status === 'failed'
            ? 'El pago anterior falló. Por favor, contacta con soporte.'
            : 'El pago está siendo procesado. Por favor, espera unos momentos.',
    ]);
}
```

### Error: kr-hash inválido
```php
if (!$isValid) {
    Log::error('Izipay Payment Success: Invalid kr-hash');
    return view('payment.error', [
        'error' => 'La firma del pago no es válida',
    ]);
}
```

### Error: Tarjeta expirada
```php
if ($card->isExpired()) {
    return view('payment.error', [
        'error' => 'La tarjeta seleccionada ha expirado',
    ]);
}
```

---

## Testing

### Tarjetas de prueba Izipay

**Visa exitosa:**
- Número: `4970 1000 0000 1003`
- Expiración: `12/25`
- CVV: `123`

**Mastercard exitosa:**
- Número: `5434 9865 2901 6023`
- Expiración: `12/25`
- CVV: `123`

**Rechazo por fondos insuficientes:**
- Número: `4970 1000 0000 0003`
- Expiración: `12/25`
- CVV: `123`

---

## Logs Importantes

**Crear pago:**
```
[INFO] Izipay CreatePayment Request
[INFO] Izipay CreatePayment Response
```

**Pago con token:**
```
[INFO] Izipay CreatePaymentWithToken Request
[INFO] Izipay CreatePaymentWithToken Response
```

**Validación kr-hash:**
```
[INFO] Izipay Payment Success Return
[INFO] Order payment confirmed from kr-answer validation
```

**Guardar token:**
```
[INFO] Payment token saved successfully from payment form
```

**Webhook:**
```
[INFO] Izipay IPN received
[INFO] Izipay: kr-hash validated successfully
[INFO] Izipay IPN V4: kr-hash validated successfully
```

---

## Seguridad

### Protecciones Implementadas

1. **HMAC-SHA-256** para validar todas las respuestas de Izipay
2. **UUID** no predecibles para order_id
3. **CSRF tokens** en formularios Laravel
4. **Validación de propiedad** de tarjetas antes de usar
5. **Verificación de expiración** de tarjetas
6. **Verificación de estado activo** de tarjetas
7. **hash_equals()** para comparaciones seguras
8. **HTTPS** obligatorio en producción
9. **Sanitización** de datos de tarjeta (solo últimos 4 dígitos)

---

## Mejoras Futuras

1. **Rate limiting** en endpoints de pago
2. **Retry logic** para llamadas a Izipay
3. **Circuit breaker** si Izipay está caído
4. **Notificaciones** por email al completar pago
5. **Dashboard** para administrar tarjetas guardadas
6. **Eliminación** de tarjetas expiradas automáticamente
7. **3D Secure** para mayor seguridad
8. **Logs estructurados** con correlation IDs
9. **Métricas** de éxito/fallo de pagos
10. **Tests automatizados** E2E del flujo completo

---

## Contacto y Soporte

**Documentación Izipay:** https://secure.micuentaweb.pe/doc/

**Soporte técnico Izipay:** soporte@izipay.pe

---

**Última actualización:** 2025-10-07
**Versión Izipay API:** V4
**Laravel Version:** 11.x