<?php

use App\Constants\AppConstants;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('class_session_teacher', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->enum('role', [AppConstants::CLASS_SESSION_MENTOR_ROLE, AppConstants::CLASS_SESSION_TEACHER_ROLE])->index();
            $table->foreignId('class_session_id')->references('id')->on('class_sessions')->onDelete('cascade');
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_session_teacher');
    }
};
