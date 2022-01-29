<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\AuthController;

Route::get('/users/{user}/achievements', [AchievementController::class, 'unlockedAchievements']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

