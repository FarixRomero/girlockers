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
        // Default image from S3 (relative path)
        $defaultAvatar = 'instructors/690fa28e61be0_1762632334.jpg';

        $instructors = [
            [
                'name' => 'Tatiana Cerna',
                'description' => 'Bailarina profesional Maestra|Jurada|Difusora de Locking',
                'instagram' => '@tati.cerna',
                'avatar' => $defaultAvatar,
                'likes_count' => 0,
            ]
        ];

        foreach ($instructors as $instructor) {
            Instructor::create($instructor);
        }
    }
}
