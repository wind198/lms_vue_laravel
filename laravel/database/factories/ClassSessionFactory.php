<?php

namespace Database\Factories;

use App\Models\Klass;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassSession>
 */
class ClassSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startTime = $this->faker->dateTimeBetween('-1 month', '+1 month');
        $endTime = (clone $startTime)->modify('+2 hours');

        $class = Klass::query()->inRandomOrder()->first();
        return [
            'start_time' => $startTime,
            'end_time' => $endTime,
            'class_id' => $class->getKey(), // Assuming you have a factory for the "classes" table
        ];
    }
}
