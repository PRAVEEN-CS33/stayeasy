<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SendReminderNotifications;

class SendVisitReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-visit-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $aa = new SendReminderNotifications();
        print(1);
    }
}
