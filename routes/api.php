<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DemonController;
use App\Http\Controllers\Api\TeamController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/demons', [DemonController::class, 'index'])->name('demons.index');
        Route::get('/demons/{demon}', [DemonController::class, 'show'])->name('demons.show');
        Route::apiResource('/teams', TeamController::class)->names('api.teams');
    });
});