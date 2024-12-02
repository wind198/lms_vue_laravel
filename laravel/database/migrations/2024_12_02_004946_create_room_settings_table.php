<?php

use App\Helpers\AppHelpers;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('room_settings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            AppHelpers::addDescriptiveFieldToTable($table);
            $table->boolean('is_global')->default(false);
            $table->json
            ('dates_off')->nullable();// date index of year, from 0 -> 365
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_settings');
    }
};
