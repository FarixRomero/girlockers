<?php

namespace Database\Seeders;

use App\Models\Instructor;
use Illuminate\Database\Seeder;

class InstructorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $instructors = [
            [
                'name' => 'María González',
                'description' => 'Bailarina profesional con más de 10 años de experiencia en danza urbana y coreografías.',
                'instagram' => '@maria.dance',
                'likes_count' => 0,
            ],
            [
                'name' => 'Laura Rodríguez',
                'description' => 'Especialista en técnica y fundamentos de baile, enfocada en el desarrollo de habilidades básicas.',
                'instagram' => '@laura.bailando',
                'likes_count' => 0,
            ],
            [
                'name' => 'Ana Martínez',
                'description' => 'Experta en workouts de baile y acondicionamiento físico a través del movimiento.',
                'instagram' => '@ana.fitness',
                'likes_count' => 0,
            ],
        ];

        foreach ($instructors as $instructor) {
            Instructor::create($instructor);
        }
    }
}
