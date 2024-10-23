<?php

namespace Database\Factories;

use App\Constants\AppConstants;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $first_name = fake()->firstName();
        $last_name = fake()->lastName();
        $full_name = "{$first_name} {$last_name}";
        return [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'full_name' => $full_name,
            'email' => fake()->email(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'email_verified_at' => now(),
            'password' => Hash::make(static::$password ??= '123123'),
            'remember_token' => Str::random(10),
            'gender' => AppConstants::FEMALE,
            'dob' => fake()->date(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
