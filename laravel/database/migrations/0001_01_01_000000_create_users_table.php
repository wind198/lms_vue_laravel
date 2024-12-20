<?php

use App\Constants\AppConstants;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', AppConstants::MAX_FIRST_NAME_LENGTH)->index();
            $table->string('last_name', AppConstants::MAX_FIRST_NAME_LENGTH);
            $table->string('full_name', AppConstants::MAX_FIRST_NAME_LENGTH * 2)->index();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable()->index();
            $table->string('password');
            $table->enum('user_type', [AppConstants::STUDENT_ROLE, AppConstants::TEACHER_ROLE, AppConstants::ROOT_ADMIN_ROLE])->index();
            $table->string('phone', AppConstants::MAX_PHONE_LENGTH)->nullable();
            $table->string('address', AppConstants::MAX_ADDRESS_LENGTH)->nullable();
            $table->enum('education_background', AppConstants::EDUCATION_BACKGROUNDS_LIST)->index();
            $table->enum('gender', AppConstants::GENDERS)->index();
            $table->date('dob')->nullable()->index();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
