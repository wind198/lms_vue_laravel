<?php

use App\Http\Controllers\Entities\GenerationsController;
use App\Http\Controllers\Entities\StudentsController;
use App\Http\Controllers\Entities\TeachersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'sanctum'
    ],
    function () {
        Route::apiResource('students', StudentsController::class);
        Route::controller(StudentsController::class)->prefix('students')->group(function () {
            Route::patch('update-many', 'updateMany')->name('students.update-many');
            Route::delete('destroy-many', 'destroyMany')->name('students.destroy-many');

        });
        Route::apiResource('teachers', TeachersController::class);
        Route::controller(TeachersController::class)->prefix('teachers')->group(function () {
            Route::patch('update-many', 'updateMany');
            Route::delete('destroy-many', 'destroyMany');

        });
        Route::apiResource('generations', GenerationsController::class);
        Route::controller(GenerationsController::class)->prefix('generations')->group(function () {
            // Route::patch('update-many', 'updateMany');
            Route::delete('destroy-many', 'destroyMany');

        });
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
    }
);

