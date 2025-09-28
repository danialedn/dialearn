<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Stage;
use App\Models\UserProgress;
use App\Models\UserStage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class QuestionController extends Controller
{
    public function show(Question $question): JsonResponse
    {
        $user = Auth::user();
        $userAnswer = $user ? $question->getUserAnswer($user) : null;

        return response()->json([
            'question' => [
                'id' => $question->id,
                'question_text' => $question->question_text,
                'options' => [
                    'A' => $question->option_a,
                    'B' => $question->option_b,
                    'C' => $question->option_c,
                    'D' => $question->option_d,
                ],
                'explanation' => $question->explanation,
                'difficulty' => $question->difficulty,
                'user_answer' => $userAnswer?->selected_answer,
                'is_correct' => $userAnswer?->is_correct,
            ],
        ]);
    }

    public function answer(Request $request, Question $question): JsonResponse
    {
        $request->validate([
            'answer' => ['required', 'string', Rule::in(['A', 'B', 'C', 'D'])],
        ]);

        $user = Auth::user();
        $answer = strtoupper($request->answer);
        $isCorrect = $question->isCorrect($answer);
        $score = $isCorrect ? 10 : 0;

        // Update or create user progress
        $progress = UserProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'question_id' => $question->id,
            ],
            [
                'stage_id' => $question->stage_id,
                'selected_answer' => $answer,
                'is_correct' => $isCorrect,
                'score' => $score,
            ]
        );

        // Update stage progress
        $stage = $question->stage;
        $stageProgress = UserStage::firstOrCreate(
            [
                'user_id' => $user->id,
                'stage_id' => $stage->id,
            ],
            [
                'total_questions' => $stage->questions_count,
            ]
        );

        // Recalculate stage statistics
        $correctAnswers = UserProgress::where('user_id', $user->id)
            ->where('stage_id', $stage->id)
            ->where('is_correct', true)
            ->count();

        $totalAnswered = UserProgress::where('user_id', $user->id)
            ->where('stage_id', $stage->id)
            ->count();

        $totalScore = UserProgress::where('user_id', $user->id)
            ->where('stage_id', $stage->id)
            ->sum('score');

        // Stage is completed when user has answered all questions
        $isCompleted = $totalAnswered >= $stage->questions_count;

        $stageProgress->update([
            'correct_answers' => $correctAnswers,
            'total_questions' => $stage->questions_count,
            'total_score' => $totalScore,
            'is_completed' => $isCompleted,
            'completed_at' => $isCompleted ? now() : null,
        ]);

        return response()->json([
            'success' => true,
            'is_correct' => $isCorrect,
            'correct_answer' => $question->correct_answer,
            'explanation' => $question->explanation,
            'score' => $score,
            'stage_completed' => $stageProgress->is_completed,
            'stage_accuracy' => $stageProgress->getAccuracyPercentage(),
        ]);
    }

    public function reset(Stage $stage): JsonResponse
    {
        $user = Auth::user();

        // Delete all progress for this stage
        UserProgress::where('user_id', $user->id)
            ->where('stage_id', $stage->id)
            ->delete();

        // Reset stage progress
        UserStage::where('user_id', $user->id)
            ->where('stage_id', $stage->id)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Stage progress has been reset',
        ]);
    }

    /**
     * Get all questions for offline storage
     */
    public function getAllForOffline(): JsonResponse
    {
        $questions = Question::with('stage')
            ->select([
                'id', 'stage_id', 'question_text', 'option_a', 'option_b', 
                'option_c', 'option_d', 'correct_answer', 'explanation', 'difficulty'
            ])
            ->get()
            ->map(function ($question) {
                return [
                    'id' => $question->id,
                    'stage_id' => $question->stage_id,
                    'stage_name' => $question->stage->name ?? '',
                    'stage_level' => $question->stage->level ?? 1,
                    'question_text' => $question->question_text,
                    'options' => [
                        'A' => $question->option_a,
                        'B' => $question->option_b,
                        'C' => $question->option_c,
                        'D' => $question->option_d,
                    ],
                    'correct_answer' => $question->correct_answer,
                    'explanation' => $question->explanation,
                    'difficulty' => $question->difficulty,
                    'cached_at' => now()->toISOString(),
                ];
            });

        return response()->json([
            'success' => true,
            'questions' => $questions,
            'total_count' => $questions->count(),
            'cached_at' => now()->toISOString(),
        ]);
    }

    /**
     * Get questions for a specific stage for offline storage
     */
    public function getStageQuestionsForOffline(Stage $stage): JsonResponse
    {
        $questions = $stage->questions()
            ->select([
                'id', 'stage_id', 'question_text', 'option_a', 'option_b', 
                'option_c', 'option_d', 'correct_answer', 'explanation', 'difficulty'
            ])
            ->get()
            ->map(function ($question) use ($stage) {
                return [
                    'id' => $question->id,
                    'stage_id' => $question->stage_id,
                    'stage_name' => $stage->name,
                    'stage_level' => $stage->level,
                    'question_text' => $question->question_text,
                    'options' => [
                        'A' => $question->option_a,
                        'B' => $question->option_b,
                        'C' => $question->option_c,
                        'D' => $question->option_d,
                    ],
                    'correct_answer' => $question->correct_answer,
                    'explanation' => $question->explanation,
                    'difficulty' => $question->difficulty,
                    'cached_at' => now()->toISOString(),
                ];
            });

        return response()->json([
            'success' => true,
            'stage' => [
                'id' => $stage->id,
                'name' => $stage->name,
                'level' => $stage->level,
                'description' => $stage->description,
                'questions_count' => $stage->questions_count,
                'difficulty' => $stage->difficulty,
            ],
            'questions' => $questions,
            'total_count' => $questions->count(),
            'cached_at' => now()->toISOString(),
        ]);
    }
}