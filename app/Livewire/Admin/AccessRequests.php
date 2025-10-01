<?php

namespace App\Livewire\Admin;

use App\Models\AccessRequest;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Solicitudes de Acceso - Admin')]
class AccessRequests extends Component
{
    use WithPagination;

    public $statusFilter = 'pending'; // pending, approved, rejected, all

    public function approveRequest($requestId)
    {
        $request = AccessRequest::with('user')->findOrFail($requestId);
        $request->approve();

        session()->flash('success', "Acceso aprobado para {$request->user->name}");
    }

    public function rejectRequest($requestId)
    {
        $request = AccessRequest::with('user')->findOrFail($requestId);
        $request->update(['status' => 'rejected']);

        session()->flash('success', "Solicitud rechazada para {$request->user->name}");
    }

    public function render()
    {
        $query = AccessRequest::with('user');

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $requests = $query->latest()->paginate(20);

        $stats = [
            'pending' => AccessRequest::where('status', 'pending')->count(),
            'approved' => AccessRequest::where('status', 'approved')->count(),
            'rejected' => AccessRequest::where('status', 'rejected')->count(),
        ];

        return view('livewire.admin.access-requests', [
            'requests' => $requests,
            'stats' => $stats,
        ]);
    }
}
