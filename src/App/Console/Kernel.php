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
        $schedule->command('telescope:prune')->daily();
        // $schedule->command('plan:check-delayed')->daily();
        // $schedule->command('plan:check-queue-order')->daily();
        $schedule->command('ldap:import users', [
            '--no-interaction',
            '--filter' => '(co=Indonesia)',
        ])->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
