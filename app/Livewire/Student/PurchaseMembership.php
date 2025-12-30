<?php

namespace App\Livewire\Student;

use App\Models\MembershipPayment;
use App\Models\MembershipPlan;
use App\Models\PaymentToken;
use App\Services\IzipayService;
use App\Services\MembershipService;
use Livewire\Attributes\Layout;
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
        // Cargar planes activos
        $this->membershipPlans = MembershipPlan::where('is_active', true)->get();

        // Cargar tarjetas guardadas del usuario
        $this->savedCards = auth()->user()->paymentTokens;
    }

    /**
     * Seleccionar tipo de membresía
     */
    public function selectMembershipType(string $type)
    {
        $this->selectedMembershipType = $type;
        $this->resetPaymentForm();
    }

    /**
     * Crear pago pendiente y redirigir a página de formulario
     */
    public function createPaymentIntent()
    {
        try {
            $membershipService = app(MembershipService::class);

            // Crear pago pendiente
            $payment = $membershipService->createPendingPayment(
                auth()->user(),
                $this->selectedMembershipType
            );

            // Redirigir a la página del formulario de pago
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
            $membershipService = app(MembershipService::class);
            $izipayService = app(IzipayService::class);

            // Encontrar la tarjeta
            $card = PaymentToken::findOrFail($cardId);

            // Validar que la tarjeta pertenece al usuario y está activa
            $validation = $membershipService->validatePaymentToken($card, auth()->user());

            if (!$validation['valid']) {
                session()->flash('error', $validation['error']);
                return;
            }

            // Crear pago pendiente
            $payment = $membershipService->createPendingPayment(
                auth()->user(),
                $this->selectedMembershipType
            );

            // Procesar pago con token
            $result = $izipayService->createPaymentWithToken([
                'order_id' => $payment->order_id,
                'amount' => $payment->amount,
                'customer' => [
                    'email' => auth()->user()->email,
                    'reference' => auth()->user()->id,
                ],
                'payment_method_token' => $card->payment_method_token,
            ]);

            if ($result['success']) {
                // Procesar pago exitoso
                $membershipService->processSuccessfulPayment($payment, $result['response']['answer']);

                session()->flash('success', '¡Pago procesado exitosamente!');
                return redirect()->route('payment.success', ['payment_id' => $payment->id]);
            } else {
                $payment->markAsFailed();
                session()->flash('error', $result['error'] ?? 'Error al procesar el pago');
            }
        } catch (\Exception $e) {
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
