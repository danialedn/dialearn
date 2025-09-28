<?php

namespace App\Services;

use App\Models\UserHeart;
use App\Models\GameSession;
use App\Models\UserProgress;
use App\Models\SyncLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Exception;

class DatabaseSyncService
{
    private $sqliteConnection;
    private $mysqlConnection;

    public function __construct()
    {
        $this->mysqlConnection = 'sqlite'; // Using SQLite as primary database
        $this->sqliteConnection = 'sqlite_offline';
    }

    /**
     * Initialize SQLite database with required tables
     */
    public function initializeSQLite(): bool
    {
        try {
            // Create SQLite database file if it doesn't exist
            $sqlitePath = database_path('offline.sqlite');
            if (!file_exists($sqlitePath)) {
                touch($sqlitePath);
            }

            // Set SQLite connection
            Config::set('database.connections.sqlite_offline.database', $sqlitePath);

            // Create tables in SQLite
            $this->createSQLiteTables();

            Log::info('SQLite database initialized successfully');
            return true;
        } catch (Exception $e) {
            Log::error('Failed to initialize SQLite database: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Create necessary tables in SQLite
     */
    private function createSQLiteTables(): void
    {
        DB::connection($this->sqliteConnection)->statement('
            CREATE TABLE IF NOT EXISTS user_hearts (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER NOT NULL,
                hearts INTEGER DEFAULT 6,
                max_hearts INTEGER DEFAULT 6,
                last_heart_lost_at TEXT,
                last_heart_regenerated_at TEXT,
                hearts_purchased_today INTEGER DEFAULT 0,
                purchase_date TEXT,
                is_synced INTEGER DEFAULT 0,
                last_synced_at TEXT,
                created_at TEXT,
                updated_at TEXT,
                UNIQUE(user_id)
            )
        ');

        DB::connection($this->sqliteConnection)->statement('
            CREATE TABLE IF NOT EXISTS game_sessions (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER NOT NULL,
                stage_id INTEGER NOT NULL,
                questions_ids TEXT,
                current_question_index INTEGER DEFAULT 0,
                mistakes INTEGER DEFAULT 0,
                score INTEGER DEFAULT 0,
                hearts_used INTEGER DEFAULT 0,
                status TEXT DEFAULT "active",
                started_at TEXT,
                completed_at TEXT,
                is_synced INTEGER DEFAULT 0,
                last_synced_at TEXT,
                created_at TEXT,
                updated_at TEXT
            )
        ');

        DB::connection($this->sqliteConnection)->statement('
            CREATE TABLE IF NOT EXISTS user_progress (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER NOT NULL,
                stage_id INTEGER NOT NULL,
                question_id INTEGER NOT NULL,
                selected_answer TEXT,
                is_correct INTEGER,
                score INTEGER DEFAULT 0,
                is_synced INTEGER DEFAULT 0,
                last_synced_at TEXT,
                created_at TEXT,
                updated_at TEXT
            )
        ');

        // Create indexes for better performance
        DB::connection($this->sqliteConnection)->statement('CREATE INDEX IF NOT EXISTS idx_user_hearts_user_id ON user_hearts(user_id)');
        DB::connection($this->sqliteConnection)->statement('CREATE INDEX IF NOT EXISTS idx_game_sessions_user_id ON game_sessions(user_id)');
        DB::connection($this->sqliteConnection)->statement('CREATE INDEX IF NOT EXISTS idx_user_progress_user_id ON user_progress(user_id)');
    }

    /**
     * Perform full synchronization between databases
     */
    public function performFullSync(): array
    {
        $syncLog = $this->createSyncLog('full_sync', 'bidirectional');
        $results = [
            'hearts' => ['synced' => 0, 'failed' => 0],
            'sessions' => ['synced' => 0, 'failed' => 0],
            'progress' => ['synced' => 0, 'failed' => 0]
        ];

        try {
            $syncLog->update(['status' => 'in_progress']);

            // Sync hearts
            $heartsResult = $this->syncUserHearts();
            $results['hearts'] = $heartsResult;

            // Sync game sessions
            $sessionsResult = $this->syncGameSessions();
            $results['sessions'] = $sessionsResult;

            // Sync user progress
            $progressResult = $this->syncUserProgress();
            $results['progress'] = $progressResult;

            $totalSynced = $results['hearts']['synced'] + $results['sessions']['synced'] + $results['progress']['synced'];
            $totalFailed = $results['hearts']['failed'] + $results['sessions']['failed'] + $results['progress']['failed'];

            $syncLog->update([
                'status' => 'completed',
                'records_synced' => $totalSynced,
                'records_failed' => $totalFailed,
                'completed_at' => now()
            ]);

            Log::info('Full sync completed', $results);

        } catch (Exception $e) {
            $syncLog->update([
                'status' => 'failed',
                'error_details' => ['error' => $e->getMessage()],
                'completed_at' => now()
            ]);

            Log::error('Full sync failed: ' . $e->getMessage());
            throw $e;
        }

        return $results;
    }

    /**
     * Sync user hearts between databases
     */
    public function syncUserHearts(): array
    {
        $synced = 0;
        $failed = 0;

        try {
            // Sync from MySQL to SQLite (newer records)
            $mysqlHearts = DB::connection($this->mysqlConnection)
                ->table('user_hearts')
                ->where('is_synced', false)
                ->get();

            foreach ($mysqlHearts as $heart) {
                try {
                    DB::connection($this->sqliteConnection)
                        ->table('user_hearts')
                        ->updateOrInsert(
                            ['user_id' => $heart->user_id],
                            (array) $heart
                        );

                    // Mark as synced in MySQL
                    DB::connection($this->mysqlConnection)
                        ->table('user_hearts')
                        ->where('id', $heart->id)
                        ->update(['is_synced' => true, 'last_synced_at' => now()]);

                    $synced++;
                } catch (Exception $e) {
                    Log::error("Failed to sync heart for user {$heart->user_id}: " . $e->getMessage());
                    $failed++;
                }
            }

            // Sync from SQLite to MySQL (newer records)
            $sqliteHearts = DB::connection($this->sqliteConnection)
                ->table('user_hearts')
                ->where('is_synced', 0)
                ->get();

            foreach ($sqliteHearts as $heart) {
                try {
                    DB::connection($this->mysqlConnection)
                        ->table('user_hearts')
                        ->updateOrInsert(
                            ['user_id' => $heart->user_id],
                            (array) $heart
                        );

                    // Mark as synced in SQLite
                    DB::connection($this->sqliteConnection)
                        ->table('user_hearts')
                        ->where('id', $heart->id)
                        ->update(['is_synced' => 1, 'last_synced_at' => now()]);

                    $synced++;
                } catch (Exception $e) {
                    Log::error("Failed to sync heart from SQLite for user {$heart->user_id}: " . $e->getMessage());
                    $failed++;
                }
            }

        } catch (Exception $e) {
            Log::error('Hearts sync failed: ' . $e->getMessage());
            throw $e;
        }

        return ['synced' => $synced, 'failed' => $failed];
    }

    /**
     * Sync game sessions between databases
     */
    public function syncGameSessions(): array
    {
        $synced = 0;
        $failed = 0;

        try {
            // Sync from MySQL to SQLite
            $mysqlSessions = DB::connection($this->mysqlConnection)
                ->table('game_sessions')
                ->where('is_synced', false)
                ->get();

            foreach ($mysqlSessions as $session) {
                try {
                    DB::connection($this->sqliteConnection)
                        ->table('game_sessions')
                        ->updateOrInsert(
                            ['id' => $session->id],
                            (array) $session
                        );

                    DB::connection($this->mysqlConnection)
                        ->table('game_sessions')
                        ->where('id', $session->id)
                        ->update(['is_synced' => true, 'last_synced_at' => now()]);

                    $synced++;
                } catch (Exception $e) {
                    Log::error("Failed to sync session {$session->id}: " . $e->getMessage());
                    $failed++;
                }
            }

            // Sync from SQLite to MySQL
            $sqliteSessions = DB::connection($this->sqliteConnection)
                ->table('game_sessions')
                ->where('is_synced', 0)
                ->get();

            foreach ($sqliteSessions as $session) {
                try {
                    DB::connection($this->mysqlConnection)
                        ->table('game_sessions')
                        ->updateOrInsert(
                            ['id' => $session->id],
                            (array) $session
                        );

                    DB::connection($this->sqliteConnection)
                        ->table('game_sessions')
                        ->where('id', $session->id)
                        ->update(['is_synced' => 1, 'last_synced_at' => now()]);

                    $synced++;
                } catch (Exception $e) {
                    Log::error("Failed to sync session from SQLite {$session->id}: " . $e->getMessage());
                    $failed++;
                }
            }

        } catch (Exception $e) {
            Log::error('Game sessions sync failed: ' . $e->getMessage());
            throw $e;
        }

        return ['synced' => $synced, 'failed' => $failed];
    }

    /**
     * Sync user progress between databases
     */
    public function syncUserProgress(): array
    {
        $synced = 0;
        $failed = 0;

        try {
            // Sync from MySQL to SQLite
            $mysqlProgress = DB::connection($this->mysqlConnection)
                ->table('user_progress')
                ->where('is_synced', false)
                ->get();

            foreach ($mysqlProgress as $progress) {
                try {
                    DB::connection($this->sqliteConnection)
                        ->table('user_progress')
                        ->updateOrInsert(
                            ['id' => $progress->id],
                            (array) $progress
                        );

                    DB::connection($this->mysqlConnection)
                        ->table('user_progress')
                        ->where('id', $progress->id)
                        ->update(['is_synced' => true, 'last_synced_at' => now()]);

                    $synced++;
                } catch (Exception $e) {
                    Log::error("Failed to sync progress {$progress->id}: " . $e->getMessage());
                    $failed++;
                }
            }

            // Sync from SQLite to MySQL
            $sqliteProgress = DB::connection($this->sqliteConnection)
                ->table('user_progress')
                ->where('is_synced', 0)
                ->get();

            foreach ($sqliteProgress as $progress) {
                try {
                    DB::connection($this->mysqlConnection)
                        ->table('user_progress')
                        ->updateOrInsert(
                            ['id' => $progress->id],
                            (array) $progress
                        );

                    DB::connection($this->sqliteConnection)
                        ->table('user_progress')
                        ->where('id', $progress->id)
                        ->update(['is_synced' => 1, 'last_synced_at' => now()]);

                    $synced++;
                } catch (Exception $e) {
                    Log::error("Failed to sync progress from SQLite {$progress->id}: " . $e->getMessage());
                    $failed++;
                }
            }

        } catch (Exception $e) {
            Log::error('User progress sync failed: ' . $e->getMessage());
            throw $e;
        }

        return ['synced' => $synced, 'failed' => $failed];
    }

    /**
     * Create a sync log entry
     */
    private function createSyncLog(string $syncType, string $direction): SyncLog
    {
        return SyncLog::create([
            'sync_type' => $syncType,
            'direction' => $direction,
            'status' => 'pending',
            'started_at' => now()
        ]);
    }

    /**
     * Get sync statistics
     */
    public function getSyncStatistics(): array
    {
        $lastSync = SyncLog::where('status', 'completed')
            ->orderBy('completed_at', 'desc')
            ->first();

        $failedSyncs = SyncLog::where('status', 'failed')
            ->where('started_at', '>=', now()->subDays(7))
            ->count();

        return [
            'last_sync' => $lastSync?->completed_at,
            'failed_syncs_last_week' => $failedSyncs,
            'pending_hearts' => DB::connection($this->mysqlConnection)->table('user_hearts')->where('is_synced', false)->count(),
            'pending_sessions' => DB::connection($this->mysqlConnection)->table('game_sessions')->where('is_synced', false)->count(),
            'pending_progress' => DB::connection($this->mysqlConnection)->table('user_progress')->where('is_synced', false)->count()
        ];
    }
}