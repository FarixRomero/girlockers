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
            // Planes en PEN (Soles Peruanos) - Para usuarios de Perú
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
            // Planes en USD (Dólares) - Para usuarios de otros países
            [
                'type' => 'monthly',
                'price' => 8.00,
                'currency' => 'USD',
                'is_active' => true,
                'description' => 'Monthly membership with full access to all classes and premium content.',
            ],
            [
                'type' => 'quarterly',
                'price' => 21.00,
                'currency' => 'USD',
                'is_active' => true,
                'description' => 'Quarterly membership (3 months) with full access. Save $3!',
            ],
        ];

        foreach ($plans as $plan) {
            MembershipPlan::updateOrCreate(
                ['type' => $plan['type'], 'currency' => $plan['currency']],
                $plan
            );
        }

        $this->command->info('Membership plans seeded successfully.');
    }
}
