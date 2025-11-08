<?php

namespace App\Livewire\Admin;

use App\Livewire\Traits\ManagesUserProfile;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Gestión del Perfil - Admin')]
class AdminProfile extends Component
{
    use ManagesUserProfile;

    public function mount(): void
    {
        $this->mountProfile();
    }

    public function render()
    {
        return view('livewire.admin.admin-profile', [
            'user' => Auth::user(),
        ]);
    }
}
