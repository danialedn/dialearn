<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DatabaseSyncService;
use Exception;

class SyncDatabases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:sync 
                            {--type=full : Type of sync (full, hearts, sessions, progress)}
                            {--force : Force sync even if last sync was recent}
                            {--init : Initialize SQLite database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize data between SQLite (offline) and MySQL (online) databases';

    private DatabaseSyncService $syncService;

    public function __construct()
    {
        parent::__construct();
        $this->syncService = new DatabaseSyncService();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Starting database synchronization...');

        try {
            // Initialize SQLite if requested
            if ($this->option('init')) {
                $this->info('📦 Initializing SQLite database...');
                if ($this->syncService->initializeSQLite()) {
                    $this->info('✅ SQLite database initialized successfully');
                } else {
                    $this->error('❌ Failed to initialize SQLite database');
                    return 1;
                }
            }

            // Check if we should proceed with sync
            if (!$this->shouldProceedWithSync()) {
                return 0;
            }

            $syncType = $this->option('type');

            // Perform synchronization based on type
            switch ($syncType) {
                case 'full':
                    $results = $this->performFullSync();
                    break;
                case 'hearts':
                    $results = $this->performHeartsSync();
                    break;
                case 'sessions':
                    $results = $this->performSessionsSync();
                    break;
                case 'progress':
                    $results = $this->performProgressSync();
                    break;
                default:
                    $this->error("❌ Invalid sync type: {$syncType}");
                    return 1;
            }

            $this->displayResults($results, $syncType);
            $this->info('✅ Database synchronization completed successfully!');

            return 0;

        } catch (Exception $e) {
            $this->error('❌ Synchronization failed: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return 1;
        }
    }

    /**
     * Check if we should proceed with sync
     */
    private function shouldProceedWithSync(): bool
    {
        if ($this->option('force')) {
            return true;
        }

        $stats = $this->syncService->getSyncStatistics();
        
        if ($stats['last_sync'] && $stats['last_sync']->diffInHours(now()) < 1) {
            $this->warn('⚠️  Last sync was less than 1 hour ago.');
            
            if (!$this->confirm('Do you want to proceed anyway?')) {
                $this->info('Sync cancelled by user.');
                return false;
            }
        }

        return true;
    }

    /**
     * Perform full synchronization
     */
    private function performFullSync(): array
    {
        $this->info('🔄 Performing full synchronization...');
        
        $progressBar = $this->output->createProgressBar(3);
        $progressBar->setFormat('verbose');
        $progressBar->start();

        $results = $this->syncService->performFullSync();
        
        $progressBar->finish();
        $this->newLine();

        return $results;
    }

    /**
     * Perform hearts synchronization
     */
    private function performHeartsSync(): array
    {
        $this->info('💖 Synchronizing user hearts...');
        $results = ['hearts' => $this->syncService->syncUserHearts()];
        return $results;
    }

    /**
     * Perform sessions synchronization
     */
    private function performSessionsSync(): array
    {
        $this->info('🎮 Synchronizing game sessions...');
        $results = ['sessions' => $this->syncService->syncGameSessions()];
        return $results;
    }

    /**
     * Perform progress synchronization
     */
    private function performProgressSync(): array
    {
        $this->info('📊 Synchronizing user progress...');
        $results = ['progress' => $this->syncService->syncUserProgress()];
        return $results;
    }

    /**
     * Display synchronization results
     */
    private function displayResults(array $results, string $syncType): void
    {
        $this->newLine();
        $this->info('📊 Synchronization Results:');
        $this->newLine();

        $totalSynced = 0;
        $totalFailed = 0;

        foreach ($results as $type => $result) {
            $synced = $result['synced'] ?? 0;
            $failed = $result['failed'] ?? 0;
            
            $totalSynced += $synced;
            $totalFailed += $failed;

            $status = $failed > 0 ? '⚠️' : '✅';
            $this->line("  {$status} {$type}: {$synced} synced, {$failed} failed");
        }

        $this->newLine();
        $this->info("📈 Total: {$totalSynced} records synced, {$totalFailed} failed");

        if ($totalFailed > 0) {
            $this->warn('⚠️  Some records failed to sync. Check the logs for details.');
        }

        // Display sync statistics
        $stats = $this->syncService->getSyncStatistics();
        $this->newLine();
        $this->info('📊 Sync Statistics:');
        $this->line("  Last sync: " . ($stats['last_sync'] ? $stats['last_sync']->format('Y-m-d H:i:s') : 'Never'));
        $this->line("  Failed syncs (last week): {$stats['failed_syncs_last_week']}");
        $this->line("  Pending hearts: {$stats['pending_hearts']}");
        $this->line("  Pending sessions: {$stats['pending_sessions']}");
        $this->line("  Pending progress: {$stats['pending_progress']}");
    }
}
