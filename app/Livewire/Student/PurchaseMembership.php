<?php

namespace App\Livewire\Student;

use App\Models\MembershipPayment;
use App\Models\MembershipPlan;
use App\Models\PaymentToken;
use App\Services\IzipayService;
use App\Services\MembershipService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
class PurchaseMembership extends Component
{
    public string $selectedMembershipType = 'monthly';
    public ?MembershipPayment $pendingPayment = null;
    public ?string $formToken = null;
    public $savedCards;
    public $membershipPlans;
    public bool $showPaymentForm = false;
    public ?string $selectedCardId = null;

    public function mount()
    {
        // Determinar la moneda según el país del usuario
        $userCountry = auth()->user()->country ?? 'PE';
        $currency = $userCountry === 'PE' ? 'PEN' : 'USD';

        // Cargar planes activos en la moneda correspondiente
        $this->membershipPlans = MembershipPlan::where('is_active', true)
            ->where('currency', $currency)
            ->get();

        // Cargar tarjetas guardadas del usuario
        $this->savedCards = auth()->user()->paymentTokens;
    }

    /**
     * Seleccionar tipo de membresía y redirigir a pago
     */
    public function selectMembershipType(string $type)
    {
        try {
            $membershipService = app(MembershipService::class);

            // Crear pago pendiente
            $payment = $membershipService->createPendingPayment(
                auth()->user(),
                $type
            );

            // Redirigir directamente a la página del formulario de pago
            return redirect()->route('payment.form', ['paymentId' => $payment->id]);
        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }


    /**
     * Pagar con tarjeta guardada
     */
    public function payWithSavedCard(string $cardId)
    {
        try {
            \Log::info('=== INICIO PAGO CON TARJETA GUARDADA ===', [
                'card_id' => $cardId,
                'user_id' => auth()->id(),
                'membership_type' => $this->selectedMembershipType,
            ]);

            $membershipService = app(MembershipService::class);
            $izipayService = app(IzipayService::class);

            // Encontrar la tarjeta
            $card = PaymentToken::findOrFail($cardId);
            \Log::info('Tarjeta encontrada', [
                'token_id' => $card->id,
                'brand' => $card->card_brand,
                'last4' => $card->last_four_digits,
            ]);

            // Validar que la tarjeta pertenece al usuario y está activa
            $validation = $membershipService->validatePaymentToken($card, auth()->user());

            if (!$validation['valid']) {
                \Log::error('Validación de token falló', $validation);
                session()->flash('error', $validation['error']);
                return;
            }

            // Crear pago pendiente
            $payment = $membershipService->createPendingPayment(
                auth()->user(),
                $this->selectedMembershipType
            );

            \Log::info('Pago pendiente creado', [
                'payment_id' => $payment->id,
                'order_id' => $payment->order_id,
                'amount' => $payment->amount,
                'currency' => $payment->currency,
            ]);

            // Procesar pago con token
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

            \Log::info('Respuesta de Izipay con token', [
                'success' => $result['success'],
                'requires_interaction' => $result['requires_interaction'] ?? false,
                'orderStatus' => $result['orderStatus'] ?? null,
                'error' => $result['error'] ?? null,
                'detailedErrorCode' => $result['detailedErrorCode'] ?? null,
            ]);

            if ($result['success']) {
                // Procesar pago exitoso
                $membershipService->processSuccessfulPayment($payment, $result['response']['answer']);

                \Log::info('=== PAGO CON TOKEN EXITOSO ===', ['payment_id' => $payment->id]);
                session()->flash('success', '¡Pago procesado exitosamente!');
                return redirect()->route('payment.success', ['payment_id' => $payment->id]);
            } elseif ($result['requires_interaction'] ?? false) {
                // El pago requiere interacción del usuario (CVV/3DS)
                \Log::info('=== PAGO CON TOKEN REQUIERE CVV ===', ['payment_id' => $payment->id]);
                session()->flash('info', 'Por seguridad, necesitas ingresar el código de seguridad (CVV) de tu tarjeta.');

                // Redirigir al formulario de pago con el formToken
                return redirect()->route('payment.form', ['paymentId' => $payment->id])
                    ->with('formToken', $result['formToken']);
            } else {
                // Detectar error PSP_610 (OneClick no habilitado)
                $errorMessage = $result['error'] ?? null;
                $detailedErrorCode = $result['detailedErrorCode'] ?? null;
                $isPSP610 = $detailedErrorCode === 'PSP_610'
                    || ($errorMessage && str_contains(strtolower($errorMessage), 'merchant acceptance agreement'));

                \Log::warning('=== PAGO CON TOKEN RECHAZADO ===', [
                    'orderStatus' => $result['orderStatus'] ?? 'unknown',
                    'error' => $errorMessage,
                    'detailedErrorCode' => $detailedErrorCode,
                    'isPSP610' => $isPSP610,
                    'full_result' => $result,
                ]);

                $payment->markAsFailed();

                // Mensaje específico para PSP_610
                if ($isPSP610) {
                    session()->flash('error', 'Tu cuenta Izipay aún no tiene habilitado el pago con tarjeta guardada (token / OneClick). Usa "nueva tarjeta" o solicita a Izipay habilitar PSP_610 para tu comercio.');
                } else {
                    session()->flash('error', $errorMessage ?? 'Error al procesar el pago con tarjeta guardada');
                }
            }
        } catch (\Exception $e) {
            \Log::error('=== ERROR EN PAGO CON TOKEN ===', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Resetear formulario de pago
     */
    public function resetPaymentForm()
    {
        $this->showPaymentForm = false;
        $this->formToken = null;
        $this->pendingPayment = null;
        $this->selectedCardId = null;
    }

    public function render()
    {
        $izipayService = app(IzipayService::class);

        return view('livewire.student.purchase-membership', [
            'publicKey' => $izipayService->getPublicKey(),
            'mode' => $izipayService->getMode(),
        ]);
    }
}
