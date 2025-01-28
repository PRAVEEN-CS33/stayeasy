<?php

namespace App\Jobs;

use App\Mail\VisitReminder;
use App\Models\ScheduledVisits;
use Illuminate\Bus\Queueable;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendReminderNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // $visit = ScheduledVisits::all()->first();
        $visit = "Remainder to visit";
        Mail::to("praveen@qoruz.com")->send(new VisitReminder($visit));
    }
}
