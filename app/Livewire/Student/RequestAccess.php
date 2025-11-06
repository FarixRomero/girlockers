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

        AccessRequest::create([
            'user_id' => auth()->id(),
            'status' => 'pending',
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
