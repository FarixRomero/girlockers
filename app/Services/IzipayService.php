<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IzipayService
{
    protected string $username;
    protected string $password;
    protected string $publicKey;
    protected string $hmacKey;
    protected string $apiUrl;
    protected string $mode;

    public function __construct()
    {
        $this->mode = config('izipay.mode', 'test');
        $config = config("izipay.{$this->mode}");

        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->publicKey = $config['public_key'];
        $this->hmacKey = $config['hmac_key'];
        $this->apiUrl = $config['api_url'];
    }

    /**
     * Create payment and get formToken for embedded form
     */
    public function createPayment(array $paymentData): array
    {
        $endpoint = '/api-payment/V4/Charge/CreatePayment';

        $payload = [
            'amount' => $paymentData['amount'] * 100, // Convertir a centavos
            'currency' => config('izipay.currency', 'PEN'),
            'orderId' => $paymentData['order_id'],
            'customer' => [
                'email' => $paymentData['customer']['email'],
                'reference' => (string) $paymentData['customer']['reference'],
            ],
        ];

        // Activar guardado de tarjeta si se solicita
        if ($paymentData['save_card'] ?? true) {
            $payload['formAction'] = 'ASK_REGISTER_PAY';

            if (isset($paymentData['customer']['reference'])) {
                $payload['customer']['reference'] = (string) $paymentData['customer']['reference'];
            }
        }

        Log::info('Izipay CreatePayment Request', [
            'endpoint' => $endpoint,
            'payload' => $payload,
        ]);

        try {
            $response = Http::withBasicAuth($this->username, $this->password)
                ->timeout(config('izipay.timeout', 30))
                ->post($this->apiUrl . $endpoint, $payload);

            $data = $response->json();

            Log::info('Izipay CreatePayment Response', [
                'status' => $response->status(),
                'data' => $data,
            ]);

            if ($response->successful() && isset($data['answer']['formToken'])) {
                return [
                    'success' => true,
                    'formToken' => $data['answer']['formToken'],
                    'response' => $data,
                ];
            }

            return [
                'success' => false,
                'error' => $data['answer']['errorMessage'] ?? 'Error al crear el pago',
                'response' => $data,
            ];
        } catch (\Exception $e) {
            Log::error('Izipay CreatePayment Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error' => 'Error de conexión con Izipay: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Create payment with saved token (one-click payment)
     */
    public function createPaymentWithToken(array $paymentData): array
    {
        $endpoint = '/api-payment/V4/Charge/CreatePayment';
        $amountInCents = (int) round(((float) $paymentData['amount']) * 100);

        $payload = [
            'amount' => $amountInCents, // Convertir a centavos
            'currency' => config('izipay.currency', 'PEN'),
            'orderId' => $paymentData['order_id'],
            'customer' => [
                'email' => $paymentData['customer']['email'],
                'reference' => (string) $paymentData['customer']['reference'],
            ],
            'paymentMethodToken' => $paymentData['payment_method_token'],
        ];

        Log::info('Izipay CreatePaymentWithToken Request', [
            'endpoint' => $endpoint,
            'payload' => array_merge($payload, [
                'paymentMethodToken' => '***', // Ocultar token en logs
            ]),
        ]);

        try {
            $response = Http::withBasicAuth($this->username, $this->password)
                ->timeout(config('izipay.timeout', 30))
                ->post($this->apiUrl . $endpoint, $payload);

            $data = $response->json();

            Log::info('Izipay CreatePaymentWithToken Response', [
                'status' => $response->status(),
                'data' => $data,
            ]);

            if ($response->successful()) {
                $orderStatus = $data['answer']['orderStatus'] ?? null;
                $errorMessage = $data['answer']['errorMessage'] ?? null;
                $detailedErrorCode = $data['answer']['detailedErrorCode'] ?? null;
                $isPaid = $orderStatus === 'PAID';

                return [
                    'success' => $isPaid,
                    'orderStatus' => $orderStatus,
                    'error' => $isPaid
                        ? null
                        : ($errorMessage ?: ($orderStatus ? "Pago no completado (estado: {$orderStatus})" : 'Pago no completado')),
                    'detailedErrorCode' => $detailedErrorCode,
                    'response' => $data,
                ];
            }

            return [
                'success' => false,
                'error' => $data['answer']['errorMessage'] ?? 'Error al procesar el pago',
                'response' => $data,
            ];
        } catch (\Exception $e) {
            Log::error('Izipay CreatePaymentWithToken Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error' => 'Error de conexión con Izipay: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Validate kr-hash signature from payment form returns
     * Uses HMAC-SHA256 with hmacKey
     */
    public function validateKrHash(string $krAnswer, string $krHash): bool
    {
        // Limpiar escapes de "/" según documentación de Izipay
        $krAnswer = str_replace('\/', '/', $krAnswer);

        // Calcular hash
        $calculatedHash = hash_hmac('sha256', $krAnswer, $this->hmacKey);

        // Comparar de forma segura
        $isValid = hash_equals($calculatedHash, $krHash);

        if (!$isValid) {
            Log::warning('Izipay kr-hash validation failed', [
                'expected' => $calculatedHash,
                'received' => $krHash,
            ]);
        }

        return $isValid;
    }

    /**
     * Validate IPN signature from webhooks
     * Uses HMAC-SHA256 with password
     */
    public function validateIpnSignature(string $requestBody, string $signature): bool
    {
        // Calcular firma usando el password
        $calculatedSignature = hash_hmac('sha256', $requestBody, $this->password);

        // Comparar de forma segura
        $isValid = hash_equals($calculatedSignature, $signature);

        if (!$isValid) {
            Log::warning('Izipay IPN signature validation failed', [
                'expected' => $calculatedSignature,
                'received' => $signature,
            ]);
        }

        return $isValid;
    }

    /**
     * Get public key for frontend
     */
    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    /**
     * Get current mode (test/production)
     */
    public function getMode(): string
    {
        return $this->mode;
    }
}
