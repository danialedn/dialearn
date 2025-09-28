<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\HeartController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\StageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('web');

// Heart-related routes moved to web.php for proper session authentication

// Game API routes
Route::middleware('web')->group(function () {
    // Game status and management
    Route::get('/game/status', [GameController::class, 'getGameStatus']);
    Route::post('/game/start', [GameController::class, 'startGame']);
    Route::get('/game/current-question', [GameController::class, 'getCurrentQuestion']);
    Route::post('/game/answer', [GameController::class, 'answerQuestion']);
    Route::post('/game/buy-hearts', [GameController::class, 'buyHearts']);
    Route::get('/game/statistics', [GameController::class, 'getStatistics']);
    
    // Heart management routes
    Route::get('/hearts/status', [HeartController::class, 'getHeartStatus']);
    Route::post('/hearts/purchase', [HeartController::class, 'purchaseHearts']);
    Route::post('/hearts/add', [HeartController::class, 'addHearts']);
    Route::get('/hearts/statistics', [HeartController::class, 'getStatistics']);
    Route::post('/hearts/reset-daily-limits', [HeartController::class, 'resetDailyLimits']);
    Route::post('/hearts/regenerate-all', [HeartController::class, 'regenerateAllHearts']);

    
    // Existing stage and question routes
    Route::get('/stages', [StageController::class, 'index']);
    Route::get('/stages/current', [StageController::class, 'current']);
    Route::get('/stages/{stage}', [StageController::class, 'show']);
    Route::post('/stages/{stage}/reset', [QuestionController::class, 'reset']);
    
    Route::get('/questions/{question}', [QuestionController::class, 'show']);
    Route::post('/questions/{question}/answer', [QuestionController::class, 'answer']);
    
    // Offline questions API
    Route::get('/questions/offline/all', [QuestionController::class, 'getAllForOffline']);
    Route::get('/questions/offline/stage/{stage}', [QuestionController::class, 'getStageQuestionsForOffline']);
});