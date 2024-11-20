<?php

namespace Database\Seeders;

use App\Constants\AppConstants;
use App\Models\Generation;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $generation = Generation::query()->inRandomOrder()->first();
        User::factory()->count(50)->create([
            'user_type' => AppConstants::STUDENT_ROLE,
            'education_background' => AppConstants::HIGH_SCHOOL,
            'generation_id' => $generation->getKey()
        ]);
    }
}
