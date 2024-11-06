<?php

use App\Http\Controllers\Entities\StudentsController;
use App\Http\Controllers\Entities\TeachersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'sanctum'
    ],
    function () {
        Route::controller(StudentsController::class)->group(function () {
            Route::get('/students', 'index')->name(StudentsController::INDEX_ROUTE);
            Route::get('/students/{user}', 'show')->name(StudentsController::SHOW_ROUTE);
            Route::post('/students', 'create')->name(StudentsController::CREATE_ROUTE);
            Route::delete('/students/delete-many', 'deleteMany')->name(StudentsController::DELETE_MANY_ROUTE);
            Route::delete('/students/{user}', 'delete')->name(StudentsController::DELETE_ROUTE);
            Route::patch('/students/update-many', 'updateMany')->name(StudentsController::UPDATE_MANY_ROUTE);
            Route::patch('/students/{user}', 'update')->name(StudentsController::UPDATE_ROUTE);
        });
        Route::controller(TeachersController::class)->group(function () {
            Route::get('/teachers', 'index')->name(TeachersController::INDEX_ROUTE);
            Route::get('/teachers/{user}', 'show')->name(TeachersController::SHOW_ROUTE);
            Route::post('/teachers', 'create')->name(TeachersController::CREATE_ROUTE);
            Route::delete('/teachers/delete-many', 'deleteMany')->name(TeachersController::DELETE_MANY_ROUTE);
            Route::delete('/teachers/{user}', 'delete')->name(TeachersController::DELETE_ROUTE);
            Route::patch('/teachers/update-many', 'updateMany')->name(TeachersController::UPDATE_MANY_ROUTE);
            Route::patch('/teachers/{user}', 'update')->name(TeachersController::UPDATE_ROUTE);
        });
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
    }
);

