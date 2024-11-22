<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Klass>
 */
class ClassFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $course = Course::query()->inRandomOrder()->first();
        return [
            'title' => fake()->words(3, true),
            'description' => fake()->sentence(50),
            'course_id' => $course->getKey()
        ];
        ;
    }
}
