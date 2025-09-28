<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Sync databases every 24 hours at 2:00 AM
        $schedule->command('db:sync --type=full')
                 ->dailyAt('02:00')
                 ->withoutOverlapping()
                 ->runInBackground()
                 ->appendOutputTo(storage_path('logs/sync.log'));

        // Sync hearts every 4 hours (for more frequent heart updates)
        $schedule->command('db:sync --type=hearts')
                 ->everyFourHours()
                 ->withoutOverlapping()
                 ->runInBackground()
                 ->appendOutputTo(storage_path('logs/sync-hearts.log'));

        // Clean up old sync logs weekly
        $schedule->call(function () {
            \App\Models\SyncLog::where('created_at', '<', now()->subDays(30))->delete();
        })->weekly()->sundays()->at('03:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}