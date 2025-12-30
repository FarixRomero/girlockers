<?php

namespace App\Http\Controllers;

use App\Models\MembershipPayment;
use App\Services\IzipayService;
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

        // Generar formToken si no existe
        $izipayService = app(IzipayService::class);
        $formToken = null;

        if (!isset($payment->izipay_response['formToken'])) {
            $result = $izipayService->createPayment([
                'order_id' => $payment->order_id,
                'amount' => $payment->amount,
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
                    ]),
                ]);
            }
        } else {
            $formToken = $payment->izipay_response['formToken'];
        }

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
}
