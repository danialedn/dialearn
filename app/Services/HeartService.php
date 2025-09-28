<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserHeart;
use App\Models\GameSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class HeartService
{
    /**
     * Get or create user hearts record
     */
    public function getUserHearts(User $user): UserHeart
    {
        $userHeart = UserHeart::where('user_id', $user->id)->first();

        if (!$userHeart) {
            $userHeart = UserHeart::create([
                'user_id' => $user->id,
                'hearts' => UserHeart::MAX_HEARTS_DEFAULT,
                'max_hearts' => UserHeart::MAX_HEARTS_DEFAULT,
                'last_heart_lost_at' => null,
                'last_heart_regenerated_at' => null,
                'hearts_purchased_today' => 0,
                'purchase_date' => null,
                'is_synced' => false
            ]);
        }

        // Auto-regenerate hearts based on time
        $userHeart->regenerateHearts();

        return $userHeart;
    }

    /**
     * Check if user has enough hearts to play
     */
    public function hasEnoughHearts(User $user): bool
    {
        $userHeart = $this->getUserHearts($user);
        return $userHeart->hearts > 0;
    }

    /**
     * Use a heart for gameplay
     */
    public function useHeart(User $user, ?GameSession $session = null): bool
    {
        $userHeart = $this->getUserHearts($user);

        if (!$userHeart->useHeart()) {
            return false;
        }

        // Record heart usage in game session if provided
        if ($session) {
            $session->useHeart();
        }

        Log::info("Heart used by user {$user->id}. Remaining hearts: {$userHeart->hearts}");
        return true;
    }

    /**
     * Add hearts to user (from purchase or reward)
     */
    public function addHearts(User $user, int $count, string $reason = 'manual'): bool
    {
        $userHeart = $this->getUserHearts($user);
        
        if ($reason === 'purchase') {
            if (!$userHeart->canPurchaseHearts()) {
                return false;
            }
            $userHeart->recordPurchase($count);
        } else {
            $userHeart->addHearts($count);
        }

        Log::info("Added {$count} hearts to user {$user->id}. Reason: {$reason}. Total hearts: {$userHeart->hearts}");
        return true;
    }

    /**
     * Get heart status for user
     */
    public function getHeartStatus(User $user): array
    {
        $userHeart = $this->getUserHearts($user);
        $nextRegenTime = $userHeart->getNextRegenerationTime();

        return [
            'hearts' => $userHeart->hearts,
            'current_hearts' => $userHeart->hearts,
            'max_hearts' => $userHeart->max_hearts,
            'can_play' => $userHeart->hearts > 0,
            'heart_regeneration_time' => $nextRegenTime?->toISOString(),
            'next_regeneration' => $nextRegenTime?->toISOString(),
            'next_regeneration_human' => $nextRegenTime?->diffForHumans(),
            'hearts_purchased_today' => $userHeart->hearts_purchased_today,
            'can_purchase_more' => $userHeart->canPurchaseHearts(),
            'last_heart_lost' => $userHeart->last_heart_lost_at?->toISOString(),
            'regeneration_hours' => UserHeart::HEART_REGENERATION_HOURS
        ];
    }

    /**
     * Get hearts statistics for admin/analytics
     */
    public function getHeartsStatistics(): array
    {
        return [
            'total_users_with_hearts' => UserHeart::count(),
            'users_with_full_hearts' => UserHeart::whereColumn('hearts', 'max_hearts')->count(),
            'users_with_no_hearts' => UserHeart::where('hearts', 0)->count(),
            'average_hearts' => UserHeart::avg('hearts'),
            'total_hearts_purchased_today' => UserHeart::where('purchase_date', now()->toDateString())
                ->sum('hearts_purchased_today'),
            'users_who_purchased_today' => UserHeart::where('purchase_date', now()->toDateString())
                ->where('hearts_purchased_today', '>', 0)
                ->count()
        ];
    }

    /**
     * Reset daily purchase limits (should be called daily)
     */
    public function resetDailyPurchaseLimits(): int
    {
        $affected = UserHeart::where('purchase_date', '<', now()->toDateString())
            ->update([
                'hearts_purchased_today' => 0,
                'purchase_date' => null,
                'is_synced' => false
            ]);

        Log::info("Reset daily purchase limits for {$affected} users");
        return $affected;
    }

    /**
     * Regenerate hearts for all users (batch operation)
     */
    public function regenerateAllHearts(): int
    {
        $regenerated = 0;
        
        // Get users who need heart regeneration
        $usersNeedingRegen = UserHeart::where('hearts', '<', DB::raw('max_hearts'))
            ->whereNotNull('last_heart_lost_at')
            ->where('last_heart_lost_at', '<=', now()->subHours(UserHeart::HEART_REGENERATION_HOURS))
            ->get();

        foreach ($usersNeedingRegen as $userHeart) {
            $oldHearts = $userHeart->hearts;
            $userHeart->regenerateHearts();
            
            if ($userHeart->hearts > $oldHearts) {
                $regenerated++;
            }
        }

        Log::info("Regenerated hearts for {$regenerated} users");
        return $regenerated;
    }

    /**
     * Handle heart system for game start
     */
    public function handleGameStart(User $user): array
    {
        $userHeart = $this->getUserHearts($user);
        
        if ($userHeart->hearts <= 0) {
            return [
                'success' => false,
                'message' => 'شما قلب کافی برای شروع بازی ندارید',
                'heart_status' => $this->getHeartStatus($user)
            ];
        }

        return [
            'success' => true,
            'message' => 'می‌توانید بازی را شروع کنید',
            'heart_status' => $this->getHeartStatus($user)
        ];
    }

    /**
     * Handle heart system for wrong answer
     */
    public function handleWrongAnswer(User $user, GameSession $session): array
    {
        if (!$this->useHeart($user, $session)) {
            return [
                'success' => false,
                'message' => 'قلب کافی برای ادامه بازی ندارید',
                'heart_status' => $this->getHeartStatus($user),
                'game_over' => true
            ];
        }

        $heartStatus = $this->getHeartStatus($user);
        
        return [
            'success' => true,
            'message' => 'یک قلب کم شد',
            'heart_status' => $heartStatus,
            'game_over' => !$heartStatus['can_play']
        ];
    }

    /**
     * Purchase hearts for user
     */
    public function purchaseHearts(User $user, int $count): array
    {
        $userHeart = $this->getUserHearts($user);

        if (!$userHeart->canPurchaseHearts()) {
            return [
                'success' => false,
                'message' => 'شما امروز حداکثر تعداد قلب مجاز را خریداری کرده‌اید'
            ];
        }

        $remainingPurchases = UserHeart::MAX_DAILY_PURCHASES - $userHeart->hearts_purchased_today;
        if ($count > $remainingPurchases) {
            return [
                'success' => false,
                'message' => "شما فقط می‌توانید {$remainingPurchases} قلب دیگر خریداری کنید"
            ];
        }

        if ($userHeart->hearts >= $userHeart->max_hearts) {
            return [
                'success' => false,
                'message' => 'قلب‌های شما کامل است'
            ];
        }

        $actualCount = min($count, $userHeart->max_hearts - $userHeart->hearts);
        
        if ($this->addHearts($user, $actualCount, 'purchase')) {
            return [
                'success' => true,
                'message' => "{$actualCount} قلب با موفقیت خریداری شد",
                'hearts_purchased' => $actualCount,
                'heart_status' => $this->getHeartStatus($user)
            ];
        }

        return [
            'success' => false,
            'message' => 'خطا در خرید قلب'
        ];
    }
}