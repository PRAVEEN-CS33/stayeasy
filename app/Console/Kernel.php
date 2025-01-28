<?php

namespace App\Console;

use App\Jobs\SendNotificationJob;
use App\Jobs\SendReminderNotifications;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {

        $schedule->command('app:send-visit-reminders')
            ->dailyAt('02:30');

        // $schedule->command('reminder:visits')->dailyAt('08:00')->timezone('Asia/Kolkata');
        // $schedule->job(new SendReminderNotifications)
        //     // ->dailyAt('08:00')
        //     ->everyMinute()
        //     ->timezone('Asia/Kolkata');
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
