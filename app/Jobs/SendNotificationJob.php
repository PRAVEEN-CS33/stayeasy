<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $mail;
    public $recipient;

    /**
     * Create a new job instance.
     */
    public function __construct($mail, $recipient)
    {
        $this->mail = $mail;
        $this ->recipient = $recipient;
        // Log::info("hi");
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Log::info("hello");
        Mail::to($this->recipient)->send($this->mail);
    }
}
