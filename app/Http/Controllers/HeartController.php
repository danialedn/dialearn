<?php

namespace App\Http\Controllers;

use App\Services\HeartService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class HeartController extends Controller
{
    protected HeartService $heartService;

    public function __construct(HeartService $heartService)
    {
        $this->heartService = $heartService;
    }

    /**
     * Get current heart status for authenticated user
     */
    public function getHeartStatus(): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $heartStatus = $this->heartService->getHeartStatus($user);

        return response()->json([
            'success' => true,
            'heart_status' => $heartStatus
        ]);
    }

    /**
     * Purchase hearts for authenticated user
     */
    public function purchaseHearts(Request $request): JsonResponse
    {
        $request->validate([
            'hearts' => ['required', 'integer', 'min:1', 'max:6']
        ]);

        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $heartsCount = $request->hearts;
        $result = $this->heartService->purchaseHearts($user, $heartsCount);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['message']
            ], 400);
        }

        return response()->json($result);
    }

    /**
     * Add hearts to user (admin only)
     */
    public function addHearts(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'hearts' => ['required', 'integer', 'min:1', 'max:10'],
            'reason' => ['nullable', 'string', 'max:255']
        ]);

        // Check if user is admin (you should implement proper admin check)
        $currentUser = Auth::user();
        if (!$currentUser || !$currentUser->is_admin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = \App\Models\User::find($request->user_id);
        $heartsCount = $request->hearts;
        $reason = $request->reason ?? 'admin_grant';

        $success = $this->heartService->addHearts($user, $heartsCount, $reason);

        if (!$success) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to add hearts'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => "Added {$heartsCount} hearts to user {$user->name}",
            'heart_status' => $this->heartService->getHeartStatus($user)
        ]);
    }

    /**
     * Get hearts statistics (admin only)
     */
    public function getStatistics(): JsonResponse
    {
        $currentUser = Auth::user();
        if (!$currentUser || !$currentUser->is_admin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $statistics = $this->heartService->getHeartsStatistics();

        return response()->json([
            'success' => true,
            'statistics' => $statistics
        ]);
    }

    /**
     * Reset daily purchase limits (admin only)
     */
    public function resetDailyLimits(): JsonResponse
    {
        $currentUser = Auth::user();
        if (!$currentUser || !$currentUser->is_admin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $affected = $this->heartService->resetDailyPurchaseLimits();

        return response()->json([
            'success' => true,
            'message' => "Reset daily purchase limits for {$affected} users"
        ]);
    }

    /**
     * Regenerate hearts for all users (admin only)
     */
    public function regenerateAllHearts(): JsonResponse
    {
        $currentUser = Auth::user();
        if (!$currentUser || !$currentUser->is_admin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $regenerated = $this->heartService->regenerateAllHearts();

        return response()->json([
            'success' => true,
            'message' => "Regenerated hearts for {$regenerated} users"
        ]);
    }
}