<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            GenerationSeeder::class,
            RootAdminSeeder::class,
            TeacherSeeder::class,
            StudentSeeder::class,
            MajorSeeder::class,
            CourseSeeder::class,
            RoomSeeder::class,
            ClassSeeder::class,
        ]);
    }
}
