<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Controllers\StageController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AuthController;

// Main entry point - Dashboard for authenticated users, GameRules for guests
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return app(AuthController::class)->showGameRules();
})->name('welcome');

// Game Rules page
Route::get('/game-rules', function () {
    return app(AuthController::class)->showGameRules();
})->name('game.rules');

Route::post('/accept-rules', [AuthController::class, 'acceptRules'])->name('rules.accept');
Route::get('/demographic-form', [AuthController::class, 'showDemographicForm'])->name('demographic.form');
Route::post('/complete-profile', [AuthController::class, 'completeProfile'])->name('profile.complete');

// Authentication routes for existing users
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Dashboard (Authenticated)
Route::get('/dashboard', [AuthController::class, 'dashboard'])->middleware(['auth'])->name('dashboard');



// Additional Pages (Authenticated)
Route::middleware(['auth'])->group(function () {
    Route::get('/game-intro', function () {
        return Inertia::render('GameIntro');
    })->name('game.intro');
    
    Route::get('/about', function () {
        return Inertia::render('About');
    })->name('about');
    
    Route::get('/profile/edit', function () {
        return Inertia::render('ProfileEdit');
    })->name('profile.edit');
    
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
    
    Route::get('/store', function () {
        return Inertia::render('Store');
    })->name('store');
    
    Route::get('/game', function () {
        $gameController = app(\App\Http\Controllers\GameController::class);
        $gameStatusResponse = $gameController->getGameStatus();
        $gameStatus = $gameStatusResponse->getData(true);
        
        return Inertia::render('Quiz/Index', [
            'gameStatus' => $gameStatus
        ]);
    })->name('game');
    
    Route::get('/statistics', function () {
        return Inertia::render('Statistics');
    })->name('statistics');
});

// Diabetes Quiz API Routes (Public Access)
Route::prefix('api')->group(function () {
    // Stage routes
    Route::get('stages', [StageController::class, 'index']);
    Route::get('stages/current', [StageController::class, 'current']);
    Route::get('stages/{stage}', [StageController::class, 'show']);
    Route::get('stages/{stage}/questions', [StageController::class, 'getQuestions']);
    
    // Question routes
    Route::get('questions/{question}', [QuestionController::class, 'show']);
    Route::post('questions/{question}/answer', [QuestionController::class, 'answer']);
    Route::post('stages/{stage}/reset', [QuestionController::class, 'reset']);
});

// Diabetes Quiz API Routes (Authenticated)
Route::middleware(['auth'])->prefix('api')->group(function () {
    // User-specific progress routes can be added here
});

// Game API Routes (Authenticated)
Route::middleware(['auth'])->prefix('api/game')->group(function () {
    Route::post('/start', [\App\Http\Controllers\GameController::class, 'startGame']);
    Route::get('/status', [\App\Http\Controllers\GameController::class, 'getGameStatus']);
    Route::get('/current-question', [\App\Http\Controllers\GameController::class, 'getCurrentQuestion']);
    Route::post('/answer', [\App\Http\Controllers\GameController::class, 'answerQuestion']);
    Route::post('/buy-hearts', [\App\Http\Controllers\GameController::class, 'buyHearts']);
    Route::get('/statistics', [\App\Http\Controllers\GameController::class, 'getStatistics']);
    
    // Heart-related routes
    Route::post('/use-heart', [\App\Http\Controllers\GameController::class, 'useHeart']);
    Route::post('/skip-question', [\App\Http\Controllers\GameController::class, 'skipQuestion']);
    Route::post('/remove-wrong-answers', [\App\Http\Controllers\GameController::class, 'removeWrongAnswers']);
});

// Shared function for both quiz and game routes
$getStagesData = function () {
    $stageController = new StageController();
    $stagesResponse = $stageController->index();
    $stages = $stagesResponse->getData(true);
    
    $data = ['stages' => $stages];
    
    // Add game status if user is authenticated
    if (Auth::check()) {
        try {
            $heartService = app(\App\Services\HeartService::class);
            $gameController = new \App\Http\Controllers\GameController($heartService);
            $gameStatusResponse = $gameController->getGameStatus();
            $gameStatus = $gameStatusResponse->getData(true);
            $data['gameStatus'] = $gameStatus;
        } catch (Exception $e) {
            // If game status fails, continue without it
            $data['gameStatus'] = null;
        }
    }
    
    return $data;
};

// Diabetes Quiz Frontend Routes (Public Access)
Route::get('/quiz', function () use ($getStagesData) {
    return Inertia::render('Quiz/Index', $getStagesData());
})->name('quiz.index');

Route::get('/quiz/stage/{stage}', function ($stage) {
    return Inertia::render('Quiz/Stage', ['stageId' => $stage]);
})->name('quiz.stage');

// Diabetes Quiz Frontend Routes (Authenticated)
Route::middleware(['auth'])->group(function () use ($getStagesData) {
    Route::get('/quiz/dashboard', function () {
        return Inertia::render('Quiz/Dashboard');
    })->name('quiz.dashboard');
    
    // Game routes (authenticated)
    Route::get('/game', function () use ($getStagesData) {
        return Inertia::render('Quiz/Index', $getStagesData());
    })->name('game.index');

    Route::get('/game/stage/{stage}', function ($stage) {
        return Inertia::render('Quiz/Stage', ['stageId' => $stage]);
    })->name('game.stage');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
