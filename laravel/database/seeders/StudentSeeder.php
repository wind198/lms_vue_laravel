<?php

namespace Database\Seeders;

use App\Constants\AppConstants;
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
        User::factory()->count(500)->create([
            'user_type' => AppConstants::STUDENT_ROLE,
            'education_background' => AppConstants::HIGH_SCHOOL,
        ]);
    }
}
