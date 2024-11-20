<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourseConfig>
 */
class CourseConfigFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $course = Course::query()->get()->take(10)->random();

        return [
            'title' => fake()->sentence(5),
            'description' => fake()->sentence(50),
            'course_id' => $course->getKey()
        ];
    }
}
