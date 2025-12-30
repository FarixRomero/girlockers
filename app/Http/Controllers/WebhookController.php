<?php

namespace App\Http\Controllers;

use App\Models\MembershipPayment;
use App\Services\IzipayService;
use App\Services\MembershipService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function __construct(
        protected IzipayService $izipayService,
        protected MembershipService $membershipService
    ) {}

    /**
     * Handle Izipay IPN V4 webhook notifications
     */
    public function handleIpnV4(Request $request)
    {
        try {
            // Obtener el cuerpo del request y la firma
            $krAnswer = $request->getContent();
            $krHash = $request->header('kr-hash');

            if (!$krAnswer || !$krHash) {
                Log::error('IPN webhook missing kr-answer or kr-hash');
                return response()->json(['error' => 'Missing data'], 400);
            }

            // Validar firma IPN usando password
            if (!$this->izipayService->validateIpnSignature($krAnswer, $krHash)) {
                Log::error('IPN webhook invalid signature');
                return response()->json(['error' => 'Invalid signature'], 400);
            }

            Log::info('IPN webhook signature validated successfully');

            // Decodificar respuesta
            $krAnswer = str_replace('\/', '/', $krAnswer);
            $paymentData = json_decode($krAnswer, true);

            if (!$paymentData) {
                Log::error('IPN webhook failed to decode kr-answer');
                return response()->json(['error' => 'Invalid data'], 400);
            }

            $orderId = $paymentData['orderDetails']['orderId'] ?? null;
            $orderStatus = $paymentData['orderStatus'] ?? null;

            if (!$orderId) {
                Log::error('IPN webhook missing orderId');
                return response()->json(['error' => 'Missing orderId'], 400);
            }

            Log::info('IPN webhook received', [
                'order_id' => $orderId,
                'order_status' => $orderStatus,
            ]);

            // Encontrar el pago
            $payment = MembershipPayment::where('order_id', $orderId)->first();

            if (!$payment) {
                Log::warning('IPN webhook payment not found', ['order_id' => $orderId]);
                // No retornar error 404, ya que Izipay sigue intentando
                return response()->json(['status' => 'payment_not_found'], 200);
            }

            // Procesar pago exitoso (redundante pero seguro)
            if ($orderStatus === 'PAID' && !$payment->isCompleted()) {
                $this->membershipService->processSuccessfulPayment($payment, $paymentData);

                // Guardar token de pago si existe
                $paymentMethodToken = $paymentData['transactions'][0]['paymentMethodToken'] ?? null;

                if ($paymentMethodToken) {
                    $cardDetails = $paymentData['transactions'][0]['transactionDetails']['cardDetails'] ?? [];
                    $this->membershipService->savePaymentToken(
                        $payment->user,
                        $paymentMethodToken,
                        $cardDetails
                    );
                }

                Log::info('IPN webhook processed payment successfully', [
                    'payment_id' => $payment->id,
                    'user_id' => $payment->user_id,
                ]);
            } elseif ($payment->isCompleted()) {
                Log::info('IPN webhook payment already completed', [
                    'payment_id' => $payment->id,
                ]);
            }

            // Siempre retornar 200 OK para que Izipay no reintente
            return response()->json(['status' => 'success'], 200);

        } catch (\Exception $e) {
            Log::error('IPN webhook error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Retornar 200 para evitar reintentos de Izipay
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 200);
        }
    }
}
