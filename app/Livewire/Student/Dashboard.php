<?php

namespace App\Livewire\Student;

use App\Services\DashboardService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Dashboard - Girls Lockers')]
class Dashboard extends Component
{
    public function render()
    {
        $dashboardService = app(DashboardService::class);
        $data = $dashboardService->getDashboardData(auth()->user());

        return view('livewire.student.dashboard', $data);
    }
}
