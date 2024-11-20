<?php

namespace Database\Seeders;

use App\Models\CourseConfig;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CourseConfig::factory()->count(3)->create();
    }
}
