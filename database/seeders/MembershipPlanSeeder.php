<?php

namespace Database\Seeders;

use App\Models\MembershipPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MembershipPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'type' => 'monthly',
                'price' => 30.00,
                'currency' => 'PEN',
                'is_active' => true,
                'description' => 'Membresía mensual con acceso completo a todas las clases y contenido premium.',
            ],
            [
                'type' => 'quarterly',
                'price' => 80.00,
                'currency' => 'PEN',
                'is_active' => true,
                'description' => 'Membresía trimestral (3 meses) con acceso completo. ¡Ahorra S/ 10!',
            ],
        ];

        foreach ($plans as $plan) {
            MembershipPlan::updateOrCreate(
                ['type' => $plan['type']],
                $plan
            );
        }

        $this->command->info('Membership plans seeded successfully.');
    }
}
