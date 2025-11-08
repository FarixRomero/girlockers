<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\AccessRequest;
use App\Services\AccessService;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Gestión de Estudiantes - Admin')]
class StudentManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $filterAccess = 'all'; // all, trial, premium, pending
    public $activeTab = 'users'; // users, requests
    public $statusFilter = 'pending'; // pending, approved, rejected, all

    public $selectedMembershipType = 'monthly';
    public $showMembershipModal = false;
    public $selectedUserId = null;
    public $selectedRequestId = null;

    public function approveAccess($userId, $membershipType = 'monthly')
    {
        $user = User::findOrFail($userId);
        $accessService = app(AccessService::class);

        $result = $accessService->grantAccess($user, $membershipType);
        $action = $result['action'] === 'extended' ? 'extendido' : 'otorgado';

        $duration = $membershipType === 'quarterly' ? '3 meses' : '1 mes';
        session()->flash('success', "Acceso {$action} para {$user->name} por {$duration}");
    }

    public function showApproveModal($userId)
    {
        $this->selectedUserId = $userId;
        $this->selectedMembershipType = 'monthly';
        $this->showMembershipModal = true;
    }

    public function confirmApproval()
    {
        if ($this->selectedUserId) {
            $this->approveAccess($this->selectedUserId, $this->selectedMembershipType);
        }
        $this->showMembershipModal = false;
        $this->selectedUserId = null;
    }

    public function revokeAccess($userId)
    {
        $user = User::findOrFail($userId);
        $accessService = app(AccessService::class);

        $accessService->revokeAccess($user);

        session()->flash('success', "Acceso revocado para {$user->name}");
    }

    public function showApproveRequestModal($requestId)
    {
        $this->selectedRequestId = $requestId;
        $request = AccessRequest::findOrFail($requestId);
        $this->selectedMembershipType = $request->membership_type ?? 'monthly';
        $this->showMembershipModal = true;
    }

    public function confirmRequestApproval()
    {
        if ($this->selectedRequestId) {
            $request = AccessRequest::with('user')->findOrFail($this->selectedRequestId);
            $request->update(['membership_type' => $this->selectedMembershipType]);
            $request->approve();

            $duration = $this->selectedMembershipType === 'quarterly' ? '3 meses' : '1 mes';
            $type = $request->isRenewal() ? 'renovado' : 'otorgado';
            session()->flash('success', "Acceso {$type} para {$request->user->name} por {$duration}");
        }
        $this->showMembershipModal = false;
        $this->selectedRequestId = null;
    }

    public function rejectRequest($requestId)
    {
        $request = AccessRequest::with('user')->findOrFail($requestId);
        $accessService = app(AccessService::class);

        $accessService->rejectRequest($request);

        session()->flash('success', "Solicitud rechazada para {$request->user->name}");
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function render()
    {
        $query = User::where('role', 'student');

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // Filter
        if ($this->filterAccess === 'trial') {
            $query->where('has_full_access', false);
        } elseif ($this->filterAccess === 'premium') {
            $query->where('has_full_access', true);
        } elseif ($this->filterAccess === 'pending') {
            $query->whereHas('accessRequests', function ($q) {
                $q->where('status', 'pending');
            });
        }

        $students = $query->withCount([
                'comments',
                'likes',
                'accessRequests as pending_requests_count' => function ($q) {
                    $q->where('status', 'pending');
                }
            ])
            ->latest()
            ->paginate(20, ['*'], 'studentsPage');

        // Access requests query
        $requestsQuery = AccessRequest::with('user');
        if ($this->statusFilter !== 'all') {
            $requestsQuery->where('status', $this->statusFilter);
        }
        $requests = $requestsQuery->latest()->paginate(20, ['*'], 'requestsPage');

        $accessService = app(AccessService::class);
        $stats = $accessService->getAccessStats();
        $requestStats = $accessService->getRequestStats();

        return view('livewire.admin.student-management', [
            'students' => $students,
            'stats' => $stats,
            'requests' => $requests,
            'requestStats' => $requestStats,
        ]);
    }
}
