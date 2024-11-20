<?php

namespace Database\Seeders;

use App\Models\Klass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Klass::factory()->count(10)->create();
    }
}
