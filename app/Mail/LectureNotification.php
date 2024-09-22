<?php

namespace App\Mail;

use App\Models\Lecture;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LectureNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $message_content;

    public function __construct(string $message)
    {
        $this->message_content = $message;
    }

    public function build()
    {
        return $this->subject('إعلان محاضرة')
            ->view('emails.lecture-notification');
    }
}
