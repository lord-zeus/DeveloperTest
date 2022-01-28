<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AchievementsController;
use App\Http\Controllers\AuthController;

Route::get('/users/{user}/achievements', [AchievementsController::class, 'index']);

Route::group([], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

