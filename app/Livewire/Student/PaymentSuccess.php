<?php

namespace App\Livewire\Student;

use App\Models\MembershipPayment;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class PaymentSuccess extends Component
{
    public ?MembershipPayment $payment = null;
    public $user;

    public function mount($payment_id = null)
    {
        $this->user = auth()->user();

        if ($payment_id) {
            $this->payment = MembershipPayment::with('user')
                ->where('id', $payment_id)
                ->where('user_id', $this->user->id)
                ->first();
        }

        // Si no hay pago o no es del usuario, redirigir
        if (!$this->payment) {
            return redirect()->route('dashboard')
                ->with('error', 'Pago no encontrado');
        }
    }

    public function render()
    {
        return view('livewire.student.payment-success');
    }
}
