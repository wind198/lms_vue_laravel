<?php

namespace Database\Seeders;

use App\Constants\AppConstants;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(50)->create([
            'user_type' => AppConstants::TEACHER_ROLE,
            'education_background' => AppConstants::GRADUATED,
        ]);
    }
}
