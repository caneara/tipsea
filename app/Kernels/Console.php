<?php

namespace App\Kernels;

use Illuminate\Foundation\Console\Kernel;
use Illuminate\Console\Scheduling\Schedule;

class Console extends Kernel
{
    /**
     * Register the commands for the application.
     *
     */
    protected function commands() : void
    {
        $this->load(app_path('Commands'));
    }

    /**
     * Define the application's command schedule.
     *
     */
    protected function schedule(Schedule $schedule) : void
    {
        $schedule->command('model:prune')->hourly();
        $schedule->command('auth:clear-resets')->daily();
        $schedule->command('system:process-tips')->everyFiveMinutes();
    }
}
