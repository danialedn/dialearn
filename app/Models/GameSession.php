<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stage_id',
        'questions_ids',
        'current_question_index',
        'mistakes',
        'score',
        'hearts_used',
        'status',
        'started_at',
        'completed_at',
        'is_synced',
        'last_synced_at'
    ];

    protected $casts = [
        'questions_ids' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_synced' => 'boolean',
        'last_synced_at' => 'datetime'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_ABANDONED = 'abandoned';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    /**
     * Get current question for this session
     */
    public function getCurrentQuestion(): ?Question
    {
        if ($this->current_question_index >= count($this->questions_ids)) {
            return null;
        }

        $questionId = $this->questions_ids[$this->current_question_index];
        return Question::find($questionId);
    }

    /**
     * Move to next question
     */
    public function nextQuestion(): bool
    {
        if ($this->current_question_index >= count($this->questions_ids) - 1) {
            return false;
        }

        $this->current_question_index++;
        $this->is_synced = false;
        $this->save();

        return true;
    }

    /**
     * Record a mistake
     */
    public function recordMistake(): void
    {
        $this->mistakes++;
        $this->is_synced = false;
        $this->save();
    }

    /**
     * Add score
     */
    public function addScore(int $points): void
    {
        $this->score += $points;
        $this->is_synced = false;
        $this->save();
    }

    /**
     * Use a heart in this session
     */
    public function useHeart(): void
    {
        $this->hearts_used++;
        $this->is_synced = false;
        $this->save();
    }

    /**
     * Complete the session
     */
    public function complete(): void
    {
        $this->status = self::STATUS_COMPLETED;
        $this->completed_at = now();
        $this->is_synced = false;
        $this->save();
    }

    /**
     * Fail the session
     */
    public function fail(): void
    {
        $this->status = self::STATUS_FAILED;
        $this->completed_at = now();
        $this->is_synced = false;
        $this->save();
    }

    /**
     * Abandon the session
     */
    public function abandon(): void
    {
        $this->status = self::STATUS_ABANDONED;
        $this->is_synced = false;
        $this->save();
    }

    /**
     * Check if session is active
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Check if session is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Get progress percentage
     */
    public function getProgressPercentage(): float
    {
        if (empty($this->questions_ids)) {
            return 0;
        }

        return ($this->current_question_index / count($this->questions_ids)) * 100;
    }
}