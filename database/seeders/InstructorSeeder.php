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
                'name' => 'Tatiana Cerna',
                'description' => 'Bailarina profesional Maestra|Jurada|Difusora de Locking',
                'instagram' => '@tati.cerna',
                'likes_count' => 0,
            ]
        ];

        foreach ($instructors as $instructor) {
            Instructor::create($instructor);
        }
    }
}
