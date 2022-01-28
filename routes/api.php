<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix'=> '/v1'], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});


Route::group(['prefix'=> '/v1', 'middleware' =>  'auth:api'], function () {
    //Badges
    Route::get('/badges', [BadgeController::class, 'index']);
    Route::post('/badges', [BadgeController::class, 'store']);

    //Achievements

    Route::get('/achievements', [AchievementController::class, 'index']);
    Route::post('/achievements', [AchievementController::class, 'store']);

    //Lessons

    Route::get('/lessons', [LessonController::class, 'index']);
    Route::post('/lessons', [LessonController::class, 'store']);
    Route::get('/lessons/assign/{lesson_id}/{user_id}', [LessonController::class, 'assignLesson']);
    Route::get('/lessons/watch/{lesson_id}', [LessonController::class, 'watchLesson']);

    //Comments

    Route::get('/comments', [CommentController::class, 'index']);
    Route::post('/comments', [CommentController::class, 'store']);

});

