<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stage_id',
        'total_score',
        'correct_answers',
        'total_questions',
        'is_completed',
        'completed_at',
    ];

    protected $casts = [
        'total_score' => 'integer',
        'correct_answers' => 'integer',
        'total_questions' => 'integer',
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    public function getAccuracyPercentage(): float
    {
        if ($this->total_questions === 0) {
            return 0;
        }

        return round(($this->correct_answers / $this->total_questions) * 100, 2);
    }

    public function markAsCompleted(): void
    {
        $this->update([
            'is_completed' => true,
            'completed_at' => now(),
        ]);
    }
}