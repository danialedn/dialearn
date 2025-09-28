<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'stage_id',
        'question_text',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_answer',
        'explanation',
        'difficulty',
    ];

    protected $casts = [
        'difficulty' => 'integer',
    ];

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    public function userProgress(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }

    public function getOptions(): array
    {
        return [
            'A' => $this->option_a,
            'B' => $this->option_b,
            'C' => $this->option_c,
            'D' => $this->option_d,
        ];
    }

    public function isCorrect(string $answer): bool
    {
        return strtoupper($answer) === strtoupper($this->correct_answer);
    }

    public function hasBeenAnsweredBy(User $user): bool
    {
        return $this->userProgress()
            ->where('user_id', $user->id)
            ->exists();
    }

    public function getUserAnswer(User $user): ?UserProgress
    {
        return $this->userProgress()
            ->where('user_id', $user->id)
            ->first();
    }
}