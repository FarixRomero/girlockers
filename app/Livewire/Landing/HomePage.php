<?php

namespace App\Livewire\Landing;

use App\Models\Instructor;
use App\Models\MembershipPlan;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Girls Lockers - Clases de Locking online')]
class HomePage extends Component
{
    public function render()
    {
        $instructors = collect();
        $pricing = [
            'currency_symbol' => 'S/',
            'monthly' => 30,
            'quarterly' => 50,
            'quarterly_original' => 60,
        ];

        try {
            if (Schema::hasTable('instructors')) {
                $instructors = Instructor::query()
                    ->orderByDesc('likes_count')
                    ->latest('id')
                    ->limit(3)
                    ->get();
            }

            if (Schema::hasTable('membership_plans')) {
                $monthlyPlan = MembershipPlan::query()
                    ->where('type', 'monthly')
                    ->where('currency', 'PEN')
                    ->where('is_active', true)
                    ->first();

                $quarterlyPlan = MembershipPlan::query()
                    ->where('type', 'quarterly')
                    ->where('currency', 'PEN')
                    ->where('is_active', true)
                    ->first();

                if ($monthlyPlan) {
                    $pricing['monthly'] = (float) $monthlyPlan->price;
                }

                if ($quarterlyPlan) {
                    $pricing['quarterly'] = (float) $quarterlyPlan->price;
                }
            }
        } catch (\Throwable $e) {}

        return view('livewire.landing.home-page', [
            'instructors' => $instructors,
            'pricing' => $pricing,
        ])->layout('layouts.master');
    }
}
