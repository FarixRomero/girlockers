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
        // Locking Básico Course
        $lockingBasico = Course::create([
            'title' => 'Locking Básico',
            'slug' => 'locking-basico',
            'description' => 'Aprende los fundamentos del Locking desde cero. Este curso te enseñará las técnicas básicas y movimientos esenciales del estilo Locking, perfecto para principiantes que quieren dominar esta técnica de baile funk.',
            'level' => 'principiante',
            'image' => null,
            'is_published' => true,
        ]);

        // Módulos para Locking Básico
        $modulosBasicos = [
            [
                'title' => 'Lock',
                'lessons' => [
                    ['title' => 'Introducción al Lock', 'description' => 'Historia y fundamentos del movimiento Lock', 'is_trial' => true],
                    ['title' => 'Posición y Postura', 'description' => 'Aprende la postura correcta para ejecutar el Lock'],
                    ['title' => 'Lock en Movimiento', 'description' => 'Integra el Lock con desplazamientos'],
                    ['title' => 'Timing del Lock', 'description' => 'Domina el timing musical para el Lock'],
                ]
            ],
            [
                'title' => 'Point',
                'lessons' => [
                    ['title' => 'Point Básico', 'description' => 'La técnica fundamental del Point', 'is_trial' => true],
                    ['title' => 'Double Point', 'description' => 'Aprende a hacer points con ambas manos'],
                    ['title' => 'Point con Dirección', 'description' => 'Points hacia diferentes direcciones'],
                    ['title' => 'Point con Actitud', 'description' => 'Agrega personalidad a tus Points'],
                ]
            ],
            [
                'title' => 'Wrist Rolls',
                'lessons' => [
                    ['title' => 'Wrist Roll Básico', 'description' => 'Aprende el giro de muñeca básico'],
                    ['title' => 'Wrist Roll Doble', 'description' => 'Wrist rolls con ambas manos'],
                    ['title' => 'Velocidad en Wrist Rolls', 'description' => 'Aumenta la velocidad y fluidez'],
                    ['title' => 'Wrist Rolls con Brazos', 'description' => 'Integra brazos completos en el movimiento'],
                ]
            ],
            [
                'title' => 'Back Slap',
                'lessons' => [
                    ['title' => 'Back Slap Básico', 'description' => 'Técnica correcta del Back Slap'],
                    ['title' => 'Back Slap con Ritmo', 'description' => 'Sincroniza el Back Slap con la música'],
                    ['title' => 'Variaciones de Back Slap', 'description' => 'Diferentes formas de ejecutar el movimiento'],
                    ['title' => 'Combinaciones con Back Slap', 'description' => 'Integra el Back Slap en secuencias'],
                ]
            ],
        ];

        $order = 1;
        foreach ($modulosBasicos as $moduloData) {
            $module = Module::create([
                'course_id' => $lockingBasico->id,
                'title' => $moduloData['title'],
                'order' => $order++,
            ]);

            $lessonOrder = 1;
            // Video de Bunny.net CDN
            $bunnyVideoId = '676c63ed-da5d-44e0-b9a5-35a75cafbb0c';
            foreach ($moduloData['lessons'] as $lessonData) {
                Lesson::create([
                    'module_id' => $module->id,
                    'title' => $lessonData['title'],
                    'description' => $lessonData['description'],
                    'video_type' => 'bunny',
                    'bunny_video_id' => $bunnyVideoId,
                    'youtube_id' => null,
                    'video_path' => null,
                    'duration' => 180, // 3 minutos por defecto
                    'is_trial' => $lessonData['is_trial'] ?? false,
                    'instructor_id' => $tatiana?->id,
                    'order' => $lessonOrder++,
                ]);
            }
        }

        // Locking Intermedio Course
        $lockingIntermedio = Course::create([
            'title' => 'Locking Intermedio',
            'slug' => 'locking-intermedio',
            'description' => 'Lleva tu Locking al siguiente nivel con variaciones avanzadas. Aprende a agregar estilo personal y complejidad a los movimientos básicos que ya dominas.',
            'level' => 'intermedio',
            'image' => null,
            'is_published' => true,
        ]);

        // Módulos para Locking Intermedio
        $modulosIntermedios = [
            [
                'title' => 'Lock (Variaciones)',
                'lessons' => [
                    ['title' => 'Fast Lock', 'description' => 'Locks rápidos y precisos', 'is_trial' => true],
                    ['title' => 'Slow Motion Lock', 'description' => 'Control en cámara lenta'],
                    ['title' => 'Lock con Niveles', 'description' => 'Locks en diferentes alturas'],
                    ['title' => 'Lock con Rotación', 'description' => 'Agrega giros a tus Locks'],
                    ['title' => 'Musical Lock', 'description' => 'Locks que siguen diferentes instrumentos'],
                ]
            ],
            [
                'title' => 'Point (Variaciones)',
                'lessons' => [
                    ['title' => 'Point con Footwork', 'description' => 'Combina Points con trabajo de pies'],
                    ['title' => 'Sequential Points', 'description' => 'Secuencias de Points fluidos'],
                    ['title' => 'Point con Saltos', 'description' => 'Agrega saltos a tus Points'],
                    ['title' => 'Cross Body Points', 'description' => 'Points cruzando el cuerpo'],
                    ['title' => 'Point Combinations', 'description' => 'Combinaciones avanzadas de Points'],
                ]
            ],
            [
                'title' => 'Wrist Rolls (Variaciones)',
                'lessons' => [
                    ['title' => 'Reverse Wrist Rolls', 'description' => 'Wrist Rolls en sentido contrario'],
                    ['title' => 'Asymmetric Wrist Rolls', 'description' => 'Cada mano con diferente velocidad'],
                    ['title' => 'Wrist Rolls con Desplazamiento', 'description' => 'Muévete mientras haces Wrist Rolls'],
                    ['title' => 'Wrist Rolls con Niveles', 'description' => 'Cambia de altura durante el movimiento'],
                    ['title' => 'Extended Wrist Rolls', 'description' => 'Wrist Rolls de brazo completo avanzados'],
                ]
            ],
            [
                'title' => 'Back Slap (Variaciones)',
                'lessons' => [
                    ['title' => 'Double Back Slap', 'description' => 'Back Slaps consecutivos'],
                    ['title' => 'Back Slap con Spin', 'description' => 'Agrega giros al Back Slap'],
                    ['title' => 'Traveling Back Slap', 'description' => 'Back Slap en movimiento'],
                    ['title' => 'Back Slap Combinations', 'description' => 'Combina Back Slap con otros movimientos'],
                    ['title' => 'Musical Back Slap', 'description' => 'Sincroniza con breaks musicales'],
                ]
            ],
        ];

        $order = 1;
        foreach ($modulosIntermedios as $moduloData) {
            $module = Module::create([
                'course_id' => $lockingIntermedio->id,
                'title' => $moduloData['title'],
                'order' => $order++,
            ]);

            $lessonOrder = 1;
            // Video de Bunny.net CDN
            $bunnyVideoId = '676c63ed-da5d-44e0-b9a5-35a75cafbb0c';
            foreach ($moduloData['lessons'] as $lessonData) {
                Lesson::create([
                    'module_id' => $module->id,
                    'title' => $lessonData['title'],
                    'description' => $lessonData['description'],
                    'video_type' => 'bunny',
                    'bunny_video_id' => $bunnyVideoId,
                    'youtube_id' => null,
                    'video_path' => null,
                    'duration' => 180, // 3 minutos por defecto
                    'is_trial' => $lessonData['is_trial'] ?? false,
                    'instructor_id' => $tatiana?->id,
                    'order' => $lessonOrder++,
                ]);
            }
        }
    }
}
