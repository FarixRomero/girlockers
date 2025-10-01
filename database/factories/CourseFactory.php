<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(5),
            'level' => fake()->randomElement(['beginner', 'intermediate', 'advanced']),
            'image' => null,
            'is_published' => false,
        ];
    }

    /**
     * Indicate that the course is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => true,
        ]);
    }

    /**
     * Set the course level to beginner.
     */
    public function beginner(): static
    {
        return $this->state(fn (array $attributes) => [
            'level' => 'beginner',
        ]);
    }

    /**
     * Set the course level to intermediate.
     */
    public function intermediate(): static
    {
        return $this->state(fn (array $attributes) => [
            'level' => 'intermediate',
        ]);
    }

    /**
     * Set the course level to advanced.
     */
    public function advanced(): static
    {
        return $this->state(fn (array $attributes) => [
            'level' => 'advanced',
        ]);
    }
}
