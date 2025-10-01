<?php

namespace Database\Factories;

use App\Models\Module;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lesson>
 */
class LessonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'module_id' => Module::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'video_type' => 'youtube',
            'youtube_id' => fake()->regexify('[A-Za-z0-9_-]{11}'),
            'local_path' => null,
            'thumbnail' => null,
            'is_trial' => false,
            'order' => 0,
            'likes_count' => 0,
        ];
    }

    /**
     * Indicate that the lesson is a trial lesson.
     */
    public function trial(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_trial' => true,
        ]);
    }

    /**
     * Indicate that the lesson is premium (not trial).
     */
    public function premium(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_trial' => false,
        ]);
    }

    /**
     * Indicate that the lesson uses local video.
     */
    public function local(): static
    {
        return $this->state(fn (array $attributes) => [
            'video_type' => 'local',
            'youtube_id' => null,
            'local_path' => 'videos/' . fake()->uuid() . '.mp4',
        ]);
    }

    /**
     * Indicate that the lesson uses YouTube video.
     */
    public function youtube(): static
    {
        return $this->state(fn (array $attributes) => [
            'video_type' => 'youtube',
            'youtube_id' => fake()->regexify('[A-Za-z0-9_-]{11}'),
            'local_path' => null,
        ]);
    }
}
