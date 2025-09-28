<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Stage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'gender',
        'age',
        'education_level',
        'profile_picture',
        'rules_accepted',
        'rules_accepted_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'rules_accepted' => 'boolean',
            'rules_accepted_at' => 'datetime',
        ];
    }

    public function userProgress(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }

    public function userStages(): HasMany
    {
        return $this->hasMany(UserStage::class);
    }

    public function getCurrentStage(): ?Stage
    {
        $lastCompletedStage = $this->userStages()
            ->where('is_completed', true)
            ->orderByDesc('completed_at')
            ->first();

        if (!$lastCompletedStage) {
            return Stage::orderBy('level')->first();
        }

        return $lastCompletedStage->stage->getNextStage();
    }

    public function getStageProgress(Stage $stage): ?UserStage
    {
        return $this->userStages()
            ->where('stage_id', $stage->id)
            ->first();
    }
}
