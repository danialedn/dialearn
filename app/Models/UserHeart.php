<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class UserHeart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hearts',
        'max_hearts',
        'last_heart_lost_at',
        'last_heart_regenerated_at',
        'hearts_purchased_today',
        'purchase_date',
        'is_synced',
        'last_synced_at'
    ];

    protected $casts = [
        'last_heart_lost_at' => 'datetime',
        'last_heart_regenerated_at' => 'datetime',
        'purchase_date' => 'date',
        'is_synced' => 'boolean',
        'last_synced_at' => 'datetime'
    ];

    const HEART_REGENERATION_HOURS = 8;
    const MAX_HEARTS_DEFAULT = 6;
    const MAX_DAILY_PURCHASES = 10;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate and update regenerated hearts based on time elapsed
     */
    public function regenerateHearts(): void
    {
        if (!$this->last_heart_lost_at || $this->hearts >= $this->max_hearts) {
            return;
        }

        $hoursElapsed = $this->last_heart_lost_at->diffInHours(now());
        $heartsToRegenerate = intval($hoursElapsed / self::HEART_REGENERATION_HOURS);

        if ($heartsToRegenerate > 0) {
            $newHearts = min($this->max_hearts, $this->hearts + $heartsToRegenerate);
            $heartsActuallyRegenerated = $newHearts - $this->hearts;
            
            $this->hearts = $newHearts;
            $this->last_heart_regenerated_at = now();
            
            // Update last_heart_lost_at to reflect the regeneration
            if ($heartsActuallyRegenerated > 0) {
                $this->last_heart_lost_at = $this->last_heart_lost_at
                    ->addHours($heartsActuallyRegenerated * self::HEART_REGENERATION_HOURS);
            }
            
            $this->is_synced = false;
            $this->save();
        }
    }

    /**
     * Use a heart (decrease by 1)
     */
    public function useHeart(): bool
    {
        if ($this->hearts <= 0) {
            return false;
        }

        $this->hearts--;
        $this->last_heart_lost_at = now();
        $this->is_synced = false;
        $this->save();

        return true;
    }

    /**
     * Add hearts (from purchase or other means)
     */
    public function addHearts(int $count): void
    {
        $this->hearts = min($this->max_hearts, $this->hearts + $count);
        $this->is_synced = false;
        $this->save();
    }

    /**
     * Get next heart regeneration time
     */
    public function getNextRegenerationTime(): ?Carbon
    {
        if (!$this->last_heart_lost_at || $this->hearts >= $this->max_hearts) {
            return null;
        }

        return $this->last_heart_lost_at->addHours(self::HEART_REGENERATION_HOURS);
    }

    /**
     * Check if user can purchase more hearts today
     */
    public function canPurchaseHearts(): bool
    {
        if (!$this->purchase_date || !Carbon::parse($this->purchase_date)->isToday()) {
            return true;
        }

        return $this->hearts_purchased_today < self::MAX_DAILY_PURCHASES;
    }

    /**
     * Record heart purchase
     */
    public function recordPurchase(int $hearts): void
    {
        if (!$this->purchase_date || !Carbon::parse($this->purchase_date)->isToday()) {
            $this->hearts_purchased_today = 0;
            $this->purchase_date = now()->toDateString();
        }

        $this->hearts_purchased_today += $hearts;
        $this->addHearts($hearts);
    }
}