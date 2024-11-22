<?php

namespace Database\Factories;

use App\Models\Major;
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

        $major = Major::query()->get()->take(10)->random();

        return [
            'title' => fake()->words(3, true),
            'description' => fake()->sentence(50),
            'major_id' => $major->getKey()
        ];
    }
}
