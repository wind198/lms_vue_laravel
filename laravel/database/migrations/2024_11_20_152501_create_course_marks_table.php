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
        Schema::create('course_marks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('course_config_id');
            $table->foreign('course_config_id')->references('id')->on('course_configs')->onDelete('restrict');
            AppHelpers::addDescriptiveFieldToTable($table);
            $table->smallInteger('factor', false, true)->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_marks');
    }
};
