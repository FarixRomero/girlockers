<?php

namespace App\Livewire\Admin;

use App\Models\MembershipPlan;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class MembershipManagement extends Component
{
    public $plans;
    public $editingPlan = null;
    public $type;
    public $price;
    public $currency;
    public $description;
    public $is_active;

    protected $rules = [
        'price' => 'required|numeric|min:0',
        'currency' => 'required|string|max:3',
        'description' => 'nullable|string|max:500',
        'is_active' => 'boolean',
    ];

    public function mount()
    {
        $this->loadPlans();
    }

    public function loadPlans()
    {
        $this->plans = MembershipPlan::orderBy('type')->get();
    }

    public function editPlan($planId)
    {
        $plan = MembershipPlan::findOrFail($planId);

        $this->editingPlan = $plan->id;
        $this->type = $plan->type;
        $this->price = $plan->price;
        $this->currency = $plan->currency;
        $this->description = $plan->description;
        $this->is_active = $plan->is_active;
    }

    public function updatePlan()
    {
        $this->validate();

        $plan = MembershipPlan::findOrFail($this->editingPlan);

        $plan->update([
            'price' => $this->price,
            'currency' => $this->currency,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ]);

        $this->cancelEdit();
        $this->loadPlans();

        session()->flash('success', 'Plan actualizado exitosamente.');
    }

    public function toggleStatus($planId)
    {
        $plan = MembershipPlan::findOrFail($planId);
        $plan->update(['is_active' => !$plan->is_active]);

        $this->loadPlans();

        session()->flash('success', 'Estado del plan actualizado.');
    }

    public function cancelEdit()
    {
        $this->reset(['editingPlan', 'type', 'price', 'currency', 'description', 'is_active']);
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.membership-management');
    }
}
