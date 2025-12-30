<?php

namespace App\Http\Controllers;

use App\Models\MembershipPayment;
use App\Services\IzipayService;
use App\Services\MembershipService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    public function __construct(
        protected IzipayService $izipayService,
        protected MembershipService $membershipService
    ) {}

    /**
     * Handle successful payment callback from Izipay
     */
    public function handleSuccess(Request $request)
    {
        try {
            // Extraer kr-answer y kr-hash del request
            $krAnswer = $request->input('kr-answer');
            $krHash = $request->input('kr-hash');

            if (!$krAnswer || !$krHash) {
                Log::error('Payment callback missing kr-answer or kr-hash');
                return $this->redirectToError('Datos de pago incompletos');
            }

            // Validar firma kr-hash
            if (!$this->izipayService->validateKrHash($krAnswer, $krHash)) {
                Log::error('Payment callback invalid kr-hash');
                return $this->redirectToError('La firma del pago no es válida');
            }

            // Decodificar respuesta de Izipay
            $krAnswer = str_replace('\/', '/', $krAnswer);
            $paymentData = json_decode($krAnswer, true);

            if (!$paymentData) {
                Log::error('Payment callback failed to decode kr-answer');
                return $this->redirectToError('Error al procesar los datos del pago');
            }

            $orderId = $paymentData['orderDetails']['orderId'] ?? null;
            $orderStatus = $paymentData['orderStatus'] ?? null;

            if (!$orderId) {
                Log::error('Payment callback missing orderId');
                return $this->redirectToError('ID de orden no encontrado');
            }

            Log::info('Payment callback received', [
                'order_id' => $orderId,
                'order_status' => $orderStatus,
            ]);

            // Encontrar el pago
            $payment = MembershipPayment::where('order_id', $orderId)->first();

            if (!$payment) {
                Log::error('Payment not found', ['order_id' => $orderId]);
                return $this->redirectToError('Pago no encontrado');
            }

            // Procesar pago exitoso
            if ($orderStatus === 'PAID') {
                // Evitar procesar dos veces
                if ($payment->isCompleted()) {
                    Log::info('Payment already completed', ['payment_id' => $payment->id]);
                    return redirect()->route('dashboard')
                        ->with('payment_success', true)
                        ->with('membership_type', $payment->membership_type);
                }

                // Procesar el pago
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

                Log::info('Payment processed successfully', [
                    'payment_id' => $payment->id,
                    'user_id' => $payment->user_id,
                ]);

                return redirect()->route('dashboard')
                    ->with('payment_success', true)
                    ->with('membership_type', $payment->membership_type);
            }

            // Si el estado no es PAID, marcar como fallido
            $payment->markAsFailed();

            Log::warning('Payment failed', [
                'payment_id' => $payment->id,
                'order_status' => $orderStatus,
            ]);

            return $this->redirectToError('El pago no se completó correctamente');

        } catch (\Exception $e) {
            Log::error('Payment callback error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->redirectToError('Error al procesar el pago: ' . $e->getMessage());
        }
    }

    /**
     * Handle payment error callback from Izipay
     */
    public function handleError(Request $request)
    {
        $krAnswer = $request->input('kr-answer');

        if ($krAnswer) {
            $krAnswer = str_replace('\/', '/', $krAnswer);
            $paymentData = json_decode($krAnswer, true);
            $orderId = $paymentData['orderDetails']['orderId'] ?? null;

            if ($orderId) {
                $payment = MembershipPayment::where('order_id', $orderId)->first();
                if ($payment) {
                    $payment->markAsFailed();
                }
            }

            Log::warning('Payment error callback received', [
                'order_id' => $orderId,
                'data' => $paymentData,
            ]);
        }

        return $this->redirectToError('El pago fue cancelado o rechazado');
    }

    /**
     * Redirect to error page with message
     */
    protected function redirectToError(string $message)
    {
        return redirect()->route('purchase-membership')
            ->with('error', $message);
    }
}
