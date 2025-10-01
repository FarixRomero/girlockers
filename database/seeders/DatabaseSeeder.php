<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $this->call(AdminSeeder::class);

        // Create real courses with CourseSeeder
        $this->call(CourseSeeder::class);

        // Create test students
        $students = User::factory(10)->create();
        $premiumStudents = User::factory(5)->withFullAccess()->create();

        // Get all lessons to add comments and likes
        $lessons = Lesson::all();

        foreach ($lessons as $lesson) {
            // Add some comments to lessons
            if (rand(0, 1)) {
                Comment::factory(rand(1, 5))->create([
                    'lesson_id' => $lesson->id,
                    'user_id' => $students->random()->id,
                ]);
            }

            // Add some likes to lessons
            $likesCount = rand(0, 10);
            if ($likesCount > 0) {
                $likers = $students->random(min($likesCount, $students->count()));
                foreach ($likers as $liker) {
                    $lesson->likes()->attach($liker->id);
                }
                $lesson->update(['likes_count' => $likesCount]);
            }
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin credentials: admin@girlockers.com / password');
    }
}
