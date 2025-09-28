<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyncLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'sync_type',
        'direction',
        'status',
        'records_processed',
        'records_synced',
        'records_failed',
        'error_details',
        'started_at',
        'completed_at',
        'notes'
    ];

    protected $casts = [
        'error_details' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    const SYNC_TYPE_HEARTS = 'hearts';
    const SYNC_TYPE_GAME_SESSIONS = 'game_sessions';
    const SYNC_TYPE_USER_PROGRESS = 'user_progress';
    const SYNC_TYPE_FULL_SYNC = 'full_sync';

    const DIRECTION_SQLITE_TO_MYSQL = 'sqlite_to_mysql';
    const DIRECTION_MYSQL_TO_SQLITE = 'mysql_to_sqlite';
    const DIRECTION_BIDIRECTIONAL = 'bidirectional';

    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    /**
     * Get duration of sync in seconds
     */
    public function getDurationAttribute(): ?int
    {
        if (!$this->started_at || !$this->completed_at) {
            return null;
        }

        return $this->started_at->diffInSeconds($this->completed_at);
    }

    /**
     * Get success rate percentage
     */
    public function getSuccessRateAttribute(): float
    {
        if ($this->records_processed === 0) {
            return 0;
        }

        return ($this->records_synced / $this->records_processed) * 100;
    }

    /**
     * Check if sync was successful
     */
    public function isSuccessful(): bool
    {
        return $this->status === self::STATUS_COMPLETED && $this->records_failed === 0;
    }

    /**
     * Check if sync had partial success
     */
    public function hasPartialSuccess(): bool
    {
        return $this->status === self::STATUS_COMPLETED && $this->records_synced > 0 && $this->records_failed > 0;
    }

    /**
     * Scope for recent syncs
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('started_at', '>=', now()->subDays($days));
    }

    /**
     * Scope for failed syncs
     */
    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    /**
     * Scope for completed syncs
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }
}