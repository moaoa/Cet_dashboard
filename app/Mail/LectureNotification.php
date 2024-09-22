<?php

namespace App\Mail;

use App\Models\Lecture;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LectureNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $lecture;
    public $message;

    public function __construct(Lecture $lecture, string $message)
    {
        $this->lecture = $lecture;
        $this->message = $message;
    }

    public function build()
    {
        return $this->subject('New Lecture Notification')
            ->view('emails.lecture-notification');
    }
}
