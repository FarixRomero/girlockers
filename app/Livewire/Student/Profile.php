<?php

namespace App\Livewire\Student;

use App\Models\AccessRequest;
use App\Livewire\Traits\ManagesUserProfile;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Mi Perfil - Girls Lockers')]
class Profile extends Component
{
    use ManagesUserProfile;

    public bool $showRenewalModal = false;
    public string $selectedMembershipType = 'monthly';
    public string $countryCode = '+51';
    public string $phoneNumber = '';

    public function mount(): void
    {
        $this->mountProfile();
    }

    public function requestRenewal()
    {
        $user = Auth::user();

        // Validate phone number
        $this->validate([
            'countryCode' => ['required', 'string'],
            'phoneNumber' => ['required', 'string', 'min:6', 'max:15', 'regex:/^[0-9]+$/'],
        ], [
            'phoneNumber.required' => 'El número de teléfono es requerido.',
            'phoneNumber.min' => 'El número de teléfono debe tener al menos 6 dígitos.',
            'phoneNumber.max' => 'El número de teléfono no debe exceder 15 dígitos.',
            'phoneNumber.regex' => 'El número de teléfono solo debe contener números.',
        ]);

        // Check if there's already a pending renewal request
        $existingRequest = AccessRequest::where('user_id', $user->id)
            ->where('request_type', 'renewal')
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            session()->flash('renewal-error', 'Ya tienes una solicitud de renovación pendiente.');
            return;
        }

        // Create renewal request
        AccessRequest::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'request_type' => 'renewal',
            'membership_type' => $this->selectedMembershipType,
            'country_code' => $this->countryCode,
            'phone_number' => $this->phoneNumber,
        ]);

        $this->showRenewalModal = false;
        $this->reset(['phoneNumber', 'countryCode', 'selectedMembershipType']);
        $this->countryCode = '+51';
        session()->flash('renewal-success', '¡Solicitud de renovación enviada! El equipo se pondrá en contacto contigo pronto.');
    }

    public function showRenewalForm()
    {
        $this->selectedMembershipType = 'monthly';
        $this->countryCode = '+51';
        $this->phoneNumber = '';
        $this->showRenewalModal = true;
    }

    public function render()
    {
        $user = Auth::user();

        // Check for pending renewal requests
        $hasPendingRenewal = AccessRequest::where('user_id', $user->id)
            ->where('request_type', 'renewal')
            ->where('status', 'pending')
            ->exists();

        return view('livewire.student.profile', [
            'user' => $user,
            'hasPendingRenewal' => $hasPendingRenewal,
        ]);
    }
}
