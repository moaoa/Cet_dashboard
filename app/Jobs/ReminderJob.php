<?php

namespace App\Jobs;

use App\Services\OneSignalNotifier;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;

class ReminderJob implements ShouldQueue
{
    use Queueable;

    public $message;
    public $users;
    public $mailable;

    /**
     * Create a new job instance.
     */
    public function __construct($message, $users, Mailable $mailable)
    {
        $this->message = $message;
        $this->users = $users;
        $this->mailable = $mailable;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        OneSignalNotifier::init();
        $message = $this->message;
        $users = $this->users;

        foreach ($users as $user) {
            $subscriptions = json_decode($user->device_subscriptions) ?? [];

            if (count($subscriptions) > 0) {
                OneSignalNotifier::sendNotificationToUsers(
                    json_decode($user->device_subscriptions) ?? [],
                    $message,
                    $url = "https://cet-management.moaad.ly"
                );
            }
        }

        // Mail::to($user->email)->send(new HomeworkReminderNotification($message));
        Mail::to($user->email)->send($this->mailable);
    }
}
