<?php

namespace Database\Seeders;

use App\Models\LandingConfig;
use Illuminate\Database\Seeder;

class LandingConfigSeeder extends Seeder
{
    public function run(): void
    {
        $configs = [
            // Hero Section
            [
                'key' => 'hero_title_1',
                'value' => 'TU ESPACIO.',
                'type' => 'text',
                'group' => 'hero',
                'label' => 'Título Hero 1',
                'description' => 'Primera línea del título principal (color blanco)',
            ],
            [
                'key' => 'hero_title_2',
                'value' => 'TU RITMO.',
                'type' => 'text',
                'group' => 'hero',
                'label' => 'Título Hero 2',
                'description' => 'Segunda línea del título principal (color blanco)',
            ],
            [
                'key' => 'hero_title_3',
                'value' => 'TU PODER.',
                'type' => 'text',
                'group' => 'hero',
                'label' => 'Título Hero 3',
                'description' => 'Tercera línea del título principal (color morado)',
            ],
            [
                'key' => 'hero_subtitle',
                'value' => 'La primera comunidad y plataforma de aprendizaje de locking, creada por y para mujeres.',
                'type' => 'textarea',
                'group' => 'hero',
                'label' => 'Subtítulo Hero',
                'description' => 'Texto descriptivo debajo del título',
            ],
            [
                'key' => 'hero_video_url',
                'value' => 'HefC_rMCs-Q',
                'type' => 'text',
                'group' => 'hero',
                'label' => 'ID de Video de YouTube',
                'description' => 'Solo el ID del video (ej: HefC_rMCs-Q)',
            ],
            [
                'key' => 'hero_button_primary',
                'value' => 'ÚNETE A LA COMUNIDAD',
                'type' => 'text',
                'group' => 'hero',
                'label' => 'Botón Primario',
                'description' => 'Texto del botón morado',
            ],
            [
                'key' => 'hero_button_secondary',
                'value' => 'EXPLORA LA BÓVEDA',
                'type' => 'text',
                'group' => 'hero',
                'label' => 'Botón Secundario',
                'description' => 'Texto del botón transparente',
            ],

            // Pricing
            [
                'key' => 'pricing_monthly_price',
                'value' => '30',
                'type' => 'number',
                'group' => 'pricing',
                'label' => 'Precio Mensual',
                'description' => 'Precio del plan mensual (sin S/)',
            ],
            [
                'key' => 'pricing_quarterly_price',
                'value' => '50',
                'type' => 'number',
                'group' => 'pricing',
                'label' => 'Precio Trimestral',
                'description' => 'Precio del plan trimestral (sin S/)',
            ],
            [
                'key' => 'pricing_quarterly_original_price',
                'value' => '60',
                'type' => 'number',
                'group' => 'pricing',
                'label' => 'Precio Trimestral Original',
                'description' => 'Precio tachado del plan trimestral (sin S/)',
            ],
            [
                'key' => 'pricing_monthly_features',
                'value' => json_encode([
                    'Acceso a todas las clases',
                    'La Bóveda completa',
                    'Comunidad privada',
                    'Contenido nuevo semanal',
                ]),
                'type' => 'json',
                'group' => 'pricing',
                'label' => 'Features Plan Mensual',
                'description' => 'Lista de beneficios del plan mensual',
            ],
            [
                'key' => 'pricing_quarterly_features',
                'value' => json_encode([
                    'Acceso a todas las clases',
                    'La Bóveda completa',
                    'Comunidad privada',
                    'Contenido nuevo semanal',
                    'Sesiones exclusivas en vivo',
                    'Feedback personalizado',
                ]),
                'type' => 'json',
                'group' => 'pricing',
                'label' => 'Features Plan Trimestral',
                'description' => 'Lista de beneficios del plan trimestral',
            ],

            // Videos (Bóveda)
            [
                'key' => 'video_1_url',
                'value' => 'HefC_rMCs-Q',
                'type' => 'text',
                'group' => 'videos',
                'label' => 'Video 1 - ID YouTube',
                'description' => 'ID del primer video de la bóveda',
            ],
            [
                'key' => 'video_1_title',
                'value' => 'Los Orígenes del Locking',
                'type' => 'text',
                'group' => 'videos',
                'label' => 'Video 1 - Título',
                'description' => 'Título del primer video',
            ],
            [
                'key' => 'video_1_tag',
                'value' => 'Historia',
                'type' => 'text',
                'group' => 'videos',
                'label' => 'Video 1 - Etiqueta',
                'description' => 'Etiqueta del primer video',
            ],
            [
                'key' => 'video_2_url',
                'value' => '8b18KD5O3y8',
                'type' => 'text',
                'group' => 'videos',
                'label' => 'Video 2 - ID YouTube',
                'description' => 'ID del segundo video de la bóveda',
            ],
            [
                'key' => 'video_2_title',
                'value' => 'Momentos Legendarios',
                'type' => 'text',
                'group' => 'videos',
                'label' => 'Video 2 - Título',
                'description' => 'Título del segundo video',
            ],
            [
                'key' => 'video_2_tag',
                'value' => 'Batalla',
                'type' => 'text',
                'group' => 'videos',
                'label' => 'Video 2 - Etiqueta',
                'description' => 'Etiqueta del segundo video',
            ],

            // Testimonials
            [
                'key' => 'testimonial_1',
                'value' => json_encode([
                    'initials' => 'MG',
                    'username' => '@LockerGirl_Lima',
                    'location' => 'Lima, Perú',
                    'text' => 'Aquí encontré la confianza para empezar a batallar. La comunidad es increíble y las instructoras son top nivel.',
                ]),
                'type' => 'json',
                'group' => 'testimonials',
                'label' => 'Testimonio 1',
                'description' => 'Datos del primer testimonio',
            ],
            [
                'key' => 'testimonial_2',
                'value' => json_encode([
                    'initials' => 'SK',
                    'username' => '@Soul_Locker_Mx',
                    'location' => 'Ciudad de México',
                    'text' => 'Por fin un espacio donde puedo ser yo misma. Las lecciones son claras y la progresión es perfecta para principiantes.',
                ]),
                'type' => 'json',
                'group' => 'testimonials',
                'label' => 'Testimonio 2',
                'description' => 'Datos del segundo testimonio (destacado)',
            ],
            [
                'key' => 'testimonial_3',
                'value' => json_encode([
                    'initials' => 'LP',
                    'username' => '@LaPunkera',
                    'location' => 'Buenos Aires',
                    'text' => 'La bóveda de recursos es oro puro. Videos históricos que nunca había visto. Esto es cultura locker de verdad.',
                ]),
                'type' => 'json',
                'group' => 'testimonials',
                'label' => 'Testimonio 3',
                'description' => 'Datos del tercer testimonio',
            ],

            // Social Links
            [
                'key' => 'social_instagram',
                'value' => '#',
                'type' => 'url',
                'group' => 'social',
                'label' => 'Instagram URL',
                'description' => 'Link a perfil de Instagram',
            ],
            [
                'key' => 'social_tiktok',
                'value' => '#',
                'type' => 'url',
                'group' => 'social',
                'label' => 'TikTok URL',
                'description' => 'Link a perfil de TikTok',
            ],
            [
                'key' => 'social_youtube',
                'value' => '#',
                'type' => 'url',
                'group' => 'social',
                'label' => 'YouTube URL',
                'description' => 'Link a canal de YouTube',
            ],

            // Stats
            [
                'key' => 'stat_lockers',
                'value' => '500+',
                'type' => 'text',
                'group' => 'stats',
                'label' => 'Cantidad de Lockers',
                'description' => 'Estadística de lockers en la comunidad',
            ],
            [
                'key' => 'stat_lessons',
                'value' => '50+',
                'type' => 'text',
                'group' => 'stats',
                'label' => 'Cantidad de Lecciones',
                'description' => 'Estadística de lecciones disponibles',
            ],
            [
                'key' => 'stat_access',
                'value' => '24/7',
                'type' => 'text',
                'group' => 'stats',
                'label' => 'Acceso',
                'description' => 'Tipo de acceso (24/7)',
            ],

            // Branding
            [
                'key' => 'branding_primary_color',
                'value' => '#9333ea',
                'type' => 'color',
                'group' => 'branding',
                'label' => 'Color Principal',
                'description' => 'Color principal de la marca (morado por defecto)',
            ],
        ];

        foreach ($configs as $config) {
            LandingConfig::updateOrCreate(
                ['key' => $config['key']],
                $config
            );
        }
    }
}
