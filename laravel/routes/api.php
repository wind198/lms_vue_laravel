<?php

use App\Http\Controllers\Entities\ClassController;
use App\Http\Controllers\Entities\ClassSessionController;
use App\Http\Controllers\Entities\CourseConfigController;
use App\Http\Controllers\Entities\CourseController;
use App\Http\Controllers\Entities\GenerationController;
use App\Http\Controllers\Entities\KlassController;
use App\Http\Controllers\Entities\MajorController;
use App\Http\Controllers\Entities\RoomController;
use App\Http\Controllers\Entities\StudentController;
use App\Http\Controllers\Entities\TeacherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'sanctum'
    ],
    function () {
        Route::controller(StudentController::class)->prefix('students')->group(function () {
            Route::get('{user}/representation', 'representation');
            Route::patch('update-many', 'updateMany')->name('students.update-many');
            Route::delete('destroy-many', 'destroyMany')->name('students.destroy-many');

        });
        Route::apiResource('students', StudentController::class)->parameters([
            'students' => 'user'
        ]);

        Route::controller(TeacherController::class)->prefix('teachers')->group(function () {
            Route::get('{user}/representation', 'representation');
            Route::patch('update-many', 'updateMany');
            Route::delete('destroy-many', 'destroyMany');

        });
        Route::apiResource('teachers', TeacherController::class)->parameters([
            'teachers' => 'user'
        ]);

        Route::controller(GenerationController::class)->prefix('generations')->group(function () {
            Route::delete('destroy-many', 'destroyMany');
            Route::get('{generation}/representation', 'representation');

        });
        Route::apiResource('generations', GenerationController::class);

        Route::controller(RoomController::class)->prefix('rooms')->group(function () {
            Route::delete('destroy-many', 'destroyMany');

            Route::get('{room}/representation', 'representation');

        });
        Route::apiResource('rooms', RoomController::class);

        Route::controller(MajorController::class)->prefix('majors')->group(function () {
            Route::delete('destroy-many', 'destroyMany');
            Route::get('{major}/representation', 'representation');


        });
        Route::apiResource('majors', MajorController::class);


        Route::controller(CourseController::class)->prefix('courses')->group(function () {
            Route::delete('destroy-many', 'destroyMany');
            Route::get('{course}/representation', 'representation');

        });
        Route::apiResource('courses', CourseController::class);

        Route::controller(ClassController::class)->prefix('classes')->group(function () {
            Route::delete('destroy-many', 'destroyMany');
            Route::get('{klass}/representation', 'representation');

        });
        Route::apiResource('classes', ClassController::class)->parameters([
            'classes' => 'klass'
        ]);


        Route::controller(CourseConfigController::class)->prefix('course-configs')->group(function () {
            Route::delete('destroy-many', 'destroyMany');
            Route::get('{course_config}/representation', 'representation');

        });
        Route::apiResource('course-configs', CourseConfigController::class)->parameters([]);


        Route::controller(ClassSessionController::class)->prefix('class-sessions')->group(function () {
            Route::delete('destroy-many', 'destroyMany');
            Route::get('{class_session}/representation', 'representation');

        });
        Route::apiResource('class-sessions', ClassSessionController::class)->parameters([]);

        Route::get('/user', function (Request $request) {
            return $request->user();
        });
    }
);

