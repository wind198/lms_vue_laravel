<?php

namespace Database\Seeders;

use App\Constants\AppConstants;
use App\Models\Generation;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenerationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Generation::factory()->count(3)->create();
    }
}
