<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Stage;
use App\Models\User;
use App\Models\GameSession;
use App\Services\HeartService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class GameController extends Controller
{
    const QUESTIONS_PER_STAGE = 7;
    const MAX_MISTAKES_PER_STAGE = 1;
    const MAX_STAGES = 30;

    protected HeartService $heartService;

    public function __construct(HeartService $heartService)
    {
        $this->heartService = $heartService;
    }
    
    public function getGameStatus(): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $heartStatus = $this->heartService->getHeartStatus($user);
        $currentStage = $this->getCurrentGameStage($user);
        $totalStages = min(Stage::count(), self::MAX_STAGES);
        
        return response()->json([
            'hearts' => $heartStatus['current_hearts'],
            'max_hearts' => $heartStatus['max_hearts'],
            'current_stage' => $currentStage,
            'total_stages' => $totalStages,
            'heart_regeneration_time' => $heartStatus['next_regeneration'],
            'can_play' => $heartStatus['can_play']
        ]);
    }
    
    public function startGame(): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $gameStartResult = $this->heartService->handleGameStart($user);
        
        if (!$gameStartResult['success']) {
            return response()->json([
                'error' => $gameStartResult['message'],
                'heart_status' => $gameStartResult['heart_status']
            ], 400);
        }
        
        // Reset current game session
        Cache::forget("game_session_{$user->id}");
        
        $currentStage = $this->getCurrentGameStage($user);
        $questions = $this->getRandomQuestionsForStage($currentStage);
        
        // Create new game session in database
        $gameSession = GameSession::create([
            'user_id' => $user->id,
            'stage_id' => $currentStage,
            'questions_ids' => $questions->pluck('id')->toArray(),
            'status' => GameSession::STATUS_ACTIVE,
            'started_at' => now(),
            'hearts_used' => 0,
            'score' => 0,
            'mistakes' => 0,
            'is_synced' => false
        ]);
        
        $sessionData = [
            'session_id' => $gameSession->id,
            'stage_id' => $currentStage,
            'questions' => $questions->pluck('id')->toArray(),
            'current_question_index' => 0,
            'mistakes' => 0,
            'score' => 0,
            'started_at' => now()->toISOString()
        ];
        
        Cache::put("game_session_{$user->id}", $sessionData, now()->addHours(2));
        
        return response()->json([
            'success' => true,
            'stage' => $currentStage,
            'total_questions' => count($questions),
            'first_question' => $this->formatQuestion($questions->first()),
            'heart_status' => $gameStartResult['heart_status']
        ]);
    }
    
    public function getCurrentQuestion(): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $gameSession = Cache::get("game_session_{$user->id}");
        
        if (!$gameSession) {
            return response()->json(['error' => 'No active game session'], 400);
        }
        
        $questionId = $gameSession['questions'][$gameSession['current_question_index']];
        $question = Question::find($questionId);
        
        return response()->json([
            'question' => $this->formatQuestion($question),
            'question_number' => $gameSession['current_question_index'] + 1,
            'total_questions' => count($gameSession['questions']),
            'mistakes' => $gameSession['mistakes'],
            'score' => $gameSession['score']
        ]);
    }
    
    public function answerQuestion(Request $request): JsonResponse
    {
        $request->validate([
            'answer' => ['required', 'string', Rule::in(['A', 'B', 'C', 'D'])],
        ]);
        
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $sessionData = Cache::get("game_session_{$user->id}");
        
        if (!$sessionData) {
            return response()->json(['error' => 'No active game session'], 400);
        }
        
        $gameSession = GameSession::find($sessionData['session_id']);
        if (!$gameSession) {
            return response()->json(['error' => 'Game session not found'], 400);
        }
        
        $questionId = $sessionData['questions'][$sessionData['current_question_index']];
        $question = Question::find($questionId);
        $answer = strtoupper($request->answer);
        $isCorrect = $question->isCorrect($answer);
        
        $heartResult = null;
        
        if (!$isCorrect) {
            $sessionData['mistakes']++;
            $gameSession->mistakes++;
            
            // Use a heart for wrong answer
            $heartResult = $this->heartService->handleWrongAnswer($user, $gameSession);
            
            // Check if game over (too many mistakes OR no hearts left)
            if ($sessionData['mistakes'] > self::MAX_MISTAKES_PER_STAGE || !$heartResult['heart_status']['can_play']) {
                $gameSession->status = GameSession::STATUS_FAILED;
                $gameSession->completed_at = now();
                $gameSession->save();
                Cache::forget("game_session_{$user->id}");
                
                return response()->json([
                    'is_correct' => $isCorrect,
                    'correct_answer' => $question->correct_answer,
                    'explanation' => $question->explanation,
                    'game_over' => true,
                    'reason' => $sessionData['mistakes'] > self::MAX_MISTAKES_PER_STAGE ? 'too_many_mistakes' : 'no_hearts',
                    'heart_status' => $heartResult['heart_status']
                ]);
            }
        } else {
            $sessionData['score'] += 10;
            $gameSession->score += 10;
        }
        
        $sessionData['current_question_index']++;
        $gameSession->is_synced = false;
        $gameSession->save();
        
        // Check if stage completed
        if ($sessionData['current_question_index'] >= count($sessionData['questions'])) {
            $this->completeStage($user, $sessionData);
            $gameSession->status = GameSession::STATUS_COMPLETED;
            $gameSession->completed_at = now();
            $gameSession->save();
            Cache::forget("game_session_{$user->id}");
            
            return response()->json([
                'is_correct' => $isCorrect,
                'correct_answer' => $question->correct_answer,
                'explanation' => $question->explanation,
                'stage_completed' => true,
                'final_score' => $sessionData['score'],
                'next_stage' => $this->getCurrentGameStage($user),
                'heart_status' => $this->heartService->getHeartStatus($user)
            ]);
        }
        
        Cache::put("game_session_{$user->id}", $sessionData, now()->addHours(2));
        
        // Get next question
        $nextQuestionId = $sessionData['questions'][$sessionData['current_question_index']];
        $nextQuestion = Question::find($nextQuestionId);
        
        return response()->json([
            'is_correct' => $isCorrect,
            'correct_answer' => $question->correct_answer,
            'explanation' => $question->explanation,
            'next_question' => $this->formatQuestion($nextQuestion),
            'question_number' => $sessionData['current_question_index'] + 1,
            'mistakes' => $sessionData['mistakes'],
            'score' => $sessionData['score'],
            'heart_status' => $heartResult ? $heartResult['heart_status'] : $this->heartService->getHeartStatus($user)
        ]);
    }
    
    public function buyHearts(Request $request): JsonResponse
    {
        $request->validate([
            'hearts' => ['required', 'integer', 'min:1', 'max:6']
        ]);
        
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $heartsCount = $request->hearts;
        $prices = [1 => 5000, 2 => 10000, 3 => 15000, 4 => 20000, 5 => 25000, 6 => 30000];
        $price = $prices[$heartsCount] ?? 0;
        
        // Here you would integrate with payment gateway
        // For now, we'll just simulate the purchase
        
        $result = $this->heartService->purchaseHearts($user, $heartsCount);
        
        if (!$result['success']) {
            return response()->json([
                'error' => $result['message']
            ], 400);
        }
        
        return response()->json([
            'success' => true,
            'hearts_added' => $result['hearts_purchased'],
            'heart_status' => $result['heart_status'],
            'price_paid' => $price,
            'message' => $result['message']
        ]);
    }
    
    public function getStatistics(): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $completedStages = Cache::get("user_game_stages_{$user->id}", []);
        $currentStage = $this->getCurrentGameStage($user);
        $heartStatus = $this->heartService->getHeartStatus($user);
        
        $totalScore = array_sum(array_column($completedStages, 'score'));
        
        // Generate stage progress
        $stageProgress = [];
        for ($i = 1; $i <= 15; $i++) {
            $completed = collect($completedStages)->where('stage', $i)->isNotEmpty();
            $stageData = collect($completedStages)->where('stage', $i)->first();
            
            $stageProgress[] = [
                'level' => $i,
                'title' => "مرحله {$i}",
                'completed' => $completed,
                'current' => $i === $currentStage,
                'score' => $completed ? $stageData['score'] ?? 0 : null
            ];
        }
        
        // Generate recent games (last 10 completed stages)
        $recentGames = collect($completedStages)
            ->sortByDesc('completed_at')
            ->take(10)
            ->map(function ($stage) {
                return [
                    'id' => $stage['stage'],
                    'stage' => $stage['stage'],
                    'completed' => true,
                    'score' => $stage['score'],
                    'played_at' => $stage['completed_at'],
                    'perfect' => $stage['score'] >= 70 // Assuming 70 is perfect score
                ];
            })
            ->values()
            ->toArray();
        
        return response()->json([
            'success' => true,
            'user_stats' => [
                'current_stage' => $currentStage,
                'total_score' => $totalScore,
                'completed_stages' => count($completedStages),
                'heart_status' => $heartStatus
            ],
            'stage_progress' => $stageProgress,
            'recent_games' => $recentGames
        ]);
    }
    

    
    private function getCurrentGameStage(User $user): int
    {
        $completedStages = Cache::get("user_game_stages_{$user->id}", []);
        return count($completedStages) + 1;
    }
    
    private function completeStage(User $user, array $gameSession): void
    {
        $completedStages = Cache::get("user_game_stages_{$user->id}", []);
        $completedStages[] = [
            'stage' => $gameSession['stage_id'],
            'score' => $gameSession['score'],
            'completed_at' => now()->toISOString()
        ];
        
        Cache::put("user_game_stages_{$user->id}", $completedStages, now()->addDays(30));
    }
    
    private function getRandomQuestionsForStage(int $stage): \Illuminate\Database\Eloquent\Collection
    {
        $stageModel = Stage::where('level', $stage)->first();
        
        if (!$stageModel) {
            $stageModel = Stage::orderBy('level')->first();
        }
        
        return $stageModel->questions()
            ->inRandomOrder()
            ->limit(self::QUESTIONS_PER_STAGE)
            ->get();
    }
    
    private function formatQuestion(Question $question): array
    {
        return [
            'id' => $question->id,
            'text' => $question->question_text,
            'options' => [
                'A' => $question->option_a,
                'B' => $question->option_b,
                'C' => $question->option_c,
                'D' => $question->option_d,
            ],
            'correct_answer' => $question->correct_answer,
            'explanation' => $question->explanation,
            'difficulty' => $question->difficulty
        ];
    }
    

    
    public function skipQuestion(): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        if (!$this->heartService->hasEnoughHearts($user)) {
            return response()->json([
                'error' => 'Not enough hearts to skip question',
                'heart_status' => $this->heartService->getHeartStatus($user)
            ], 400);
        }
        
        // Use a heart for skipping
        $this->heartService->useHeart($user);
        
        // Get new question
        $currentStage = $this->getCurrentGameStage($user);
        $questions = $this->getRandomQuestionsForStage($currentStage);
        
        if ($questions->isEmpty()) {
            return response()->json(['error' => 'No questions available'], 404);
        }
        
        $nextQuestion = $questions->random();
        
        return response()->json([
            'success' => true,
            'next_question' => $this->formatQuestion($nextQuestion),
            'question_number' => 1, // This should be dynamic based on current progress
            'heart_status' => $this->heartService->getHeartStatus($user)
        ]);
    }
    
    public function removeWrongAnswers(): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $heartStatus = $this->heartService->getHeartStatus($user);
        
        if ($heartStatus['current_hearts'] < 2) {
            return response()->json([
                'error' => 'Not enough hearts to remove wrong answers',
                'heart_status' => $heartStatus
            ], 400);
        }
        
        // Use 2 hearts for removing wrong answers
        $this->heartService->useHeart($user);
        $this->heartService->useHeart($user);
        
        // Here we should get current question and remove two wrong options
        // For simplicity, we assume frontend sends the current question
        
        return response()->json([
            'success' => true,
            'message' => 'Two wrong answers removed',
            'heart_status' => $this->heartService->getHeartStatus($user)
        ]);
    }

    public function useHeart(): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        if (!$this->heartService->hasEnoughHearts($user)) {
            return response()->json([
                'success' => false,
                'error' => 'No hearts available',
                'heart_status' => $this->heartService->getHeartStatus($user),
                'message' => 'No hearts available to use'
            ], 400);
        }
        
        // Use one heart
        $this->heartService->useHeart($user);
        
        return response()->json([
            'success' => true,
            'heart_status' => $this->heartService->getHeartStatus($user),
            'message' => 'Heart used successfully'
        ]);
    }
}