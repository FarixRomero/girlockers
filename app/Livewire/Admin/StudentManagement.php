<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\AccessRequest;
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

    public function approveAccess($userId)
    {
        $user = User::findOrFail($userId);
        $user->grantFullAccess();

        // Update any pending access requests
        AccessRequest::where('user_id', $userId)
            ->where('status', 'pending')
            ->update(['status' => 'approved']);

        session()->flash('success', "Acceso completo otorgado a {$user->name}");
    }

    public function revokeAccess($userId)
    {
        $user = User::findOrFail($userId);
        $user->update([
            'has_full_access' => false,
            'access_granted_at' => null,
        ]);

        session()->flash('success', "Acceso revocado para {$user->name}");
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

        $students = $query->withCount(['comments', 'likes', 'accessRequests'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total' => User::where('role', 'student')->count(),
            'premium' => User::where('role', 'student')->where('has_full_access', true)->count(),
            'trial' => User::where('role', 'student')->where('has_full_access', false)->count(),
            'pending' => AccessRequest::where('status', 'pending')->count(),
        ];

        return view('livewire.admin.student-management', [
            'students' => $students,
            'stats' => $stats,
        ]);
    }
}
