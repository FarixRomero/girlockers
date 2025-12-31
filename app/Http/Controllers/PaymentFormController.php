<?php

namespace App\Http\Controllers;

use App\Models\MembershipPayment;
use App\Models\PaymentToken;
use App\Services\IzipayService;
use App\Services\MembershipService;
use Illuminate\Http\Request;

class PaymentFormController extends Controller
{
    public function show(Request $request, $paymentId)
    {
        // Obtener el pago pendiente
        $payment = MembershipPayment::where('id', $paymentId)
            ->where('user_id', auth()->id())
            ->where('payment_status', 'pending')
            ->firstOrFail();

        // Obtener tarjetas guardadas del usuario
        $savedCards = auth()->user()->paymentTokens()
            ->where('is_active', true)
            ->get();

        // Obtener formToken (desde sesión si viene de pago con token, o generarlo)
        $izipayService = app(IzipayService::class);
        $formToken = session('formToken'); // Desde redirect de pago con token

        \Log::info('PaymentFormController - formToken sources', [
            'from_session' => $formToken ? 'yes' : 'no',
            'from_session_value' => $formToken ? substr($formToken, 0, 20) . '...' : null,
            'from_payment' => isset($payment->izipay_response['formToken']) ? 'yes' : 'no',
            'session_all_keys' => array_keys(session()->all()),
        ]);

        // Si no hay formToken en sesión, intentar obtenerlo del pago guardado
        if (!$formToken && isset($payment->izipay_response['formToken'])) {
            $formToken = $payment->izipay_response['formToken'];
        }

        // Si aún no hay formToken, generar uno nuevo
        if (!$formToken) {
            \Log::info('Generating new formToken for payment', [
                'payment_id' => $payment->id,
                'reason' => 'No formToken in session or payment record',
            ]);

            $result = $izipayService->createPayment([
                'order_id' => $payment->order_id,
                'amount' => $payment->amount,
                'currency' => $payment->currency,
                'customer' => [
                    'email' => auth()->user()->email,
                    'reference' => auth()->user()->id,
                ],
                'save_card' => true,
            ]);

            if ($result['success']) {
                $formToken = $result['formToken'];
                // Guardar el formToken en el pago
                $payment->update([
                    'izipay_response' => array_merge($payment->izipay_response ?? [], [
                        'formToken' => $formToken,
                        'formToken_created_at' => now()->toIso8601String(),
                        'formToken_type' => 'new_card',
                    ]),
                ]);
            } else {
                // Error al crear el pago en Izipay
                \Log::error('Failed to create Izipay payment', [
                    'payment_id' => $payment->id,
                    'error' => $result['error'] ?? 'Unknown error',
                    'response' => $result['response'] ?? null,
                ]);

                return redirect()
                    ->route('purchase-membership')
                    ->with('error', 'Error al procesar el pago: ' . ($result['error'] ?? 'Error desconocido. Por favor intenta de nuevo.'));
            }
        } else {
            \Log::info('Using existing formToken', [
                'source' => session('formToken') ? 'session' : 'payment_record',
                'token_preview' => substr($formToken, 0, 20) . '...',
            ]);
        }

        \Log::info('Using formToken', ['has_token' => !empty($formToken)]);

        return view('payment.form', [
            'payment' => $payment,
            'formToken' => $formToken,
            'orderId' => $payment->order_id,
            'amount' => $payment->amount,
            'membershipType' => $payment->membership_type,
            'savedCards' => $savedCards,
            'publicKey' => $izipayService->getPublicKey(),
        ]);
    }

    public function payWithSavedCard(Request $request)
    {
        $validated = $request->validate([
            'card_id' => 'required|uuid|exists:payment_tokens,id',
            'payment_id' => 'required|uuid|exists:membership_payments,id',
        ]);

        $membershipService = app(MembershipService::class);
        $izipayService = app(IzipayService::class);

        // Obtener el pago pendiente
        $payment = MembershipPayment::where('id', $validated['payment_id'])
            ->where('user_id', auth()->id())
            ->where('payment_status', 'pending')
            ->firstOrFail();

        // Encontrar la tarjeta y validar que pertenece al usuario
        $card = PaymentToken::where('id', $validated['card_id'])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $validation = $membershipService->validatePaymentToken($card, auth()->user());

        if (!$validation['valid']) {
            return redirect()
                ->route('payment.form', ['paymentId' => $payment->id])
                ->with('error', $validation['error']);
        }

        $result = $izipayService->createPaymentWithToken([
            'order_id' => $payment->order_id,
            'amount' => $payment->amount,
            'currency' => $payment->currency,
            'customer' => [
                'email' => auth()->user()->email,
                'reference' => auth()->user()->id,
            ],
            'payment_method_token' => $card->payment_method_token,
        ]);

        if ($result['success']) {
            $membershipService->processSuccessfulPayment($payment, $result['response']['answer'] ?? []);

            return redirect()
                ->route('dashboard')
                ->with('payment_success', true)
                ->with('membership_type', $payment->membership_type);
        }

        $errorMessage = $result['error'] ?? null;
        $detailedErrorCode = $result['detailedErrorCode'] ?? null;
        $isPSP610 = $detailedErrorCode === 'PSP_610'
            || ($errorMessage && str_contains(strtolower($errorMessage), 'merchant acceptance agreement'));

        \Log::warning('Saved card payment failed', [
            'payment_id' => $payment->id,
            'order_id' => $payment->order_id,
            'user_id' => auth()->id(),
            'order_status' => $result['orderStatus'] ?? null,
            'detailed_error_code' => $detailedErrorCode,
            'error' => $errorMessage,
        ]);

        return redirect()
            ->route('payment.form', ['paymentId' => $payment->id])
            ->with('error', $isPSP610
                ? 'Tu cuenta Izipay aún no tiene habilitado el pago con tarjeta guardada (token / OneClick). Usa “nueva tarjeta” o solicita a Izipay habilitar PSP_610 para tu comercio.'
                : ($errorMessage ?? 'Error al procesar el pago con tarjeta guardada'));
    }
}
