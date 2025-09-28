<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use App\Models\UserStage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class StageController extends Controller
{
    public function index(): JsonResponse
    {
        $stages = Stage::withCount(['questions'])
            ->orderBy('level')
            ->get()
            ->map(function ($stage) {
                $userStage = Auth::check() ? Auth::user()->getStageProgress($stage) : null;
                return [
                    'id' => $stage->id,
                    'name' => $stage->name,
                    'description' => $stage->description,
                    'level' => $stage->level,
                    'questions_count' => $stage->questions_count,
                    'difficulty' => $stage->difficulty,
                    'is_completed' => $userStage?->is_completed ?? false,
                    'accuracy' => $userStage?->getAccuracyPercentage() ?? 0,
                    'completed_at' => $userStage?->completed_at,
                ];
            });

        return response()->json($stages);
    }

    public function show(Stage $stage): JsonResponse
    {
        $user = Auth::user();
        $userStage = $user ? $user->getStageProgress($stage) : null;

        // Check if previous stages are completed (only for authenticated users)
        if ($user) {
            $previousStages = Stage::where('level', '<', $stage->level)
                ->orderBy('level')
                ->get();

            foreach ($previousStages as $prevStage) {
                if (!$prevStage->isCompletedBy($user)) {
                    return response()->json([
                        'error' => 'Previous stages must be completed first'
                    ], 403);
                }
            }
        }

        $questions = $stage->questions()
            ->orderBy('id')
            ->get()
            ->map(function ($question) use ($user) {
                $userAnswer = $user ? $question->getUserAnswer($user) : null;
                return [
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
                ];
            });

        return response()->json([
            'stage' => [
                'id' => $stage->id,
                'name' => $stage->name,
                'description' => $stage->description,
                'level' => $stage->level,
                'questions_count' => $stage->questions_count,
                'difficulty' => $stage->difficulty,
                'is_completed' => $userStage?->is_completed ?? false,
                'accuracy' => $userStage?->getAccuracyPercentage() ?? 0,
            ],
            'questions' => $questions,
        ]);
    }

    public function current(): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            // For non-authenticated users, return the first stage
            $firstStage = Stage::orderBy('level')->first();
            return response()->json([
                'stage' => $firstStage ? [
                    'id' => $firstStage->id,
                    'name' => $firstStage->name,
                    'description' => $firstStage->description,
                    'level' => $firstStage->level,
                ] : null,
            ]);
        }
        
        $currentStage = $user->getCurrentStage();

        if (!$currentStage) {
            return response()->json([
                'message' => 'All stages completed!',
                'stage' => null,
            ]);
        }

        return response()->json([
            'stage' => [
                'id' => $currentStage->id,
                'name' => $currentStage->name,
                'description' => $currentStage->description,
                'level' => $currentStage->level,
            ],
        ]);
    }

    public function getQuestions(Stage $stage): JsonResponse
    {
        $questions = $stage->questions()
            ->orderBy('id')
            ->get()
            ->map(function ($question) {
                return [
                    'id' => $question->id,
                    'question' => $question->question_text,
                    'options' => [
                        $question->option_a,
                        $question->option_b,
                        $question->option_c,
                        $question->option_d,
                    ],
                    'correct_answer' => $question->correct_answer, // Already stored as integer (0,1,2,3)
                    'stage_id' => $question->stage_id,
                ];
            });

        return response()->json($questions);
    }
}