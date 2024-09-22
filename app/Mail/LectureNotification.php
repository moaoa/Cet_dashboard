<?php

namespace App\Mail;

use App\Models\Lecture;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LectureNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function build()
    {
        return $this->subject('New Lecture Notification')
            ->view('emails.lecture-notification');
    }
}
