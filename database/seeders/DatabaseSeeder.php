<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Limpiar archivos de storage/lessons al hacer reseed
        $this->cleanStorageFiles();

        // Create admin user
        $this->call(AdminSeeder::class);

        // Create tags
        $this->call(TagSeeder::class);

        // Create instructors
        $this->call(InstructorSeeder::class);

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

    /**
     * Limpiar archivos de storage al hacer reseed
     */
    private function cleanStorageFiles(): void
    {
        $this->command->info('Limpiando archivos de storage...');

        // Limpiar archivos de lessons en storage público
        $publicLessonsPath = public_path('storage/lessons');
        if (File::exists($publicLessonsPath)) {
            File::cleanDirectory($publicLessonsPath);
            $this->command->info('✓ Archivos de public/storage/lessons eliminados');
        }

        // Limpiar archivos de lessons en storage privado
        if (Storage::exists('lessons')) {
            Storage::deleteDirectory('lessons');
            Storage::makeDirectory('lessons');
            $this->command->info('✓ Archivos de storage/app/lessons eliminados');
        }

        // Limpiar archivos de cursos
        $publicCoursesPath = public_path('storage/courses');
        if (File::exists($publicCoursesPath)) {
            File::cleanDirectory($publicCoursesPath);
            $this->command->info('✓ Archivos de public/storage/courses eliminados');
        }

        if (Storage::exists('courses')) {
            Storage::deleteDirectory('courses');
            Storage::makeDirectory('courses');
            $this->command->info('✓ Archivos de storage/app/courses eliminados');
        }
    }
}
