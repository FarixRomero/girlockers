<?php

namespace App\Livewire\Student;

use App\Models\AccessRequest;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Solicitar Acceso Completo - Girls Lockers')]
class RequestAccess extends Component
{
    public $existingRequest = null;
    public string $countryCode = '+51';
    public string $phoneNumber = '';
    public string $selectedMembershipType = 'monthly';

    public function mount()
    {
        // Check if user already has full access
        if (auth()->user()->hasFullAccess()) {
            return redirect()->route('courses.index')
                ->with('message', 'Ya tienes acceso completo a todos los cursos.');
        }

        // Check for existing pending request
        $this->existingRequest = AccessRequest::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->first();
    }

    public function submitRequest()
    {
        // Prevent duplicate requests
        if ($this->existingRequest) {
            session()->flash('error', 'Ya tienes una solicitud pendiente.');
            return;
        }

        // Validate phone number
        $this->validate([
            'countryCode' => ['required', 'string'],
            'phoneNumber' => ['required', 'string', 'min:6', 'max:15', 'regex:/^[0-9]+$/'],
            'selectedMembershipType' => ['required', 'in:monthly,quarterly'],
        ], [
            'phoneNumber.required' => 'El número de teléfono es requerido.',
            'phoneNumber.min' => 'El número de teléfono debe tener al menos 6 dígitos.',
            'phoneNumber.max' => 'El número de teléfono no debe exceder 15 dígitos.',
            'phoneNumber.regex' => 'El número de teléfono solo debe contener números.',
        ]);

        AccessRequest::create([
            'user_id' => auth()->id(),
            'status' => 'pending',
            'request_type' => 'new',
            'membership_type' => $this->selectedMembershipType,
            'country_code' => $this->countryCode,
            'phone_number' => $this->phoneNumber,
        ]);

        $this->existingRequest = AccessRequest::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->first();

        session()->flash('success', '¡Solicitud enviada! Te notificaremos cuando sea aprobada.');
    }

    public function render()
    {
        return view('livewire.student.request-access')
            ->title('Solicitar Acceso Completo');
    }
}
