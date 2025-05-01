<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        // 7 dias de retenção para o Telescope
        $schedule->command('telescope:prune --hours=168')->daily();

        // $schedule->command('sanctum:prune-expired --hours=24')->daily();

        $schedule->command('activitylog:clean')->daily();

        $schedule->command('cache:prune-stale-tags')->hourly();

        $schedule->command(\Spatie\Health\Commands\DispatchQueueCheckJobsCommand::class)->everyMinute()->withoutOverlapping(10);
        $schedule->command(\Spatie\Health\Commands\ScheduleCheckHeartbeatCommand::class)->everyMinute()->withoutOverlapping(10);

        $schedule->command(\Spatie\Health\Commands\RunHealthChecksCommand::class)->everyMinute();

        $schedule->command('model:prune', [
            '--model' => [
                \Spatie\Health\Models\HealthCheckResultHistoryItem::class,
            ],
        ])->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        // require base_path('routes/console.php');
    }
}
