<?php

namespace Database\Seeders;

use App\Constants\AppConstants;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RootAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(1)->create([
            'user_type' => AppConstants::ROOT_ADMIN_ROLE,
            'education_background' => AppConstants::GRADUATED,
            'first_name' => "Lê",
            'last_name' => "Tuấn",
            'full_name' => "Lê Tuấn",
            'email' => 'tuanbk1908@gmail.com',
            'password' => 'abc123'
        ]);
    }
}
