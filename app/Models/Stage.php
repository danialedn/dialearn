<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Stage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'level',
        'questions_count',
        'difficulty',
    ];

    protected $casts = [
        'level' => 'integer',
        'questions_count' => 'integer',
        'difficulty' => 'integer',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function userStages(): HasMany
    {
        return $this->hasMany(UserStage::class);
    }

    public function userProgress(): HasManyThrough
    {
        return $this->hasManyThrough(UserProgress::class, Question::class);
    }

    public function getNextStage(): ?Stage
    {
        return static::where('level', '>', $this->level)
            ->orderBy('level')
            ->first();
    }

    public function isCompletedBy(User $user): bool
    {
        return $this->userStages()
            ->where('user_id', $user->id)
            ->where('is_completed', true)
            ->exists();
    }
}