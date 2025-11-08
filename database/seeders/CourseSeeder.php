<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\Instructor;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener la instructora Tatiana Cerna
        $tatiana = Instructor::first();

        // Default images from S3 (relative paths)
        $defaultCourseImage = 'courses/nL4oVY3ZBeLzy9jox4JG8HdftxzIEkgxqO7Gi4w8.jpg';
        $defaultLessonThumbnail = 'lessons/thumbnails/0hM1AnAmePn00bIKF3ZxmaX9G44weDIltT8W9Bw2.jpg';

        // Locking Básico Course
        $lockingBasico = Course::create([
            'title' => 'Locking Básico',
            'slug' => 'locking-basico',
            'description' => 'Aprende los fundamentos del Locking desde cero. Este curso te enseñará las técnicas básicas y movimientos esenciales del estilo Locking, perfecto para principiantes que quieren dominar esta técnica de baile funk.',
            'level' => 'principiante',
            'image' => $defaultCourseImage,
            'is_published' => true,
        ]);

        // Módulo I: Pasos Fundamentales
        $module = Module::create([
            'course_id' => $lockingBasico->id,
            'title' => 'Módulo I : Pasos Fundamentales',
            'order' => 1,
        ]);

        // Sesiones del módulo
        $lessons = [
            ['title' => 'Sesión 1 : Lock', 'description' => 'Aprende el movimiento fundamental Lock', 'is_trial' => true],
            ['title' => 'Sesión 2 : Keeping Time', 'description' => 'Domina el ritmo y timing en Locking'],
            ['title' => 'Sesión 3 : Wrist Roll', 'description' => 'Técnica del giro de muñeca'],
            ['title' => 'Sesión 4 : Point', 'description' => 'El movimiento Point y sus variaciones'],
            ['title' => 'Sesión 5 : Back Clap', 'description' => 'Aprende el Back Clap'],
            ['title' => 'Sesión 6 : Lock Lock', 'description' => 'Domina el doble Lock'],
            ['title' => 'Sesión 7 : Give yourself five', 'description' => 'El movimiento Give yourself five'],
            ['title' => 'Sesión 8 : Prep up', 'description' => 'Técnica del Prep up'],
            ['title' => 'Sesión 9 : Coreografía 8 fundamentos', 'description' => 'Combina los 8 movimientos fundamentales en una coreografía'],
            ['title' => 'Sesión 10 : Iniciación al freestyle con los 8 fundamentos', 'description' => 'Aprende a improvisar usando los 8 fundamentos del Locking'],
        ];

        // Video de Bunny.net CDN
        $bunnyVideoId = 'ef02ab89-3eab-45e9-8106-3261f609602e';

        $lessonOrder = 1;
        foreach ($lessons as $lessonData) {
            Lesson::create([
                'module_id' => $module->id,
                'title' => $lessonData['title'],
                'description' => $lessonData['description'],
                'video_type' => 'bunny',
                'bunny_video_id' => $bunnyVideoId,
                'youtube_id' => null,
                'video_path' => null,
                'thumbnail' => $defaultLessonThumbnail,
                'duration' => 180, // 3 minutos por defecto
                'is_trial' => $lessonData['is_trial'] ?? false,
                'instructor_id' => $tatiana?->id,
                'order' => $lessonOrder++,
            ]);
        }
    }
}
