<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HomeworkReminderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $message_content;

    public function __construct(string $message)
    {
        $this->message_content = $message;
    }
    public function build()
    {
        return $this->subject('تذكير بالواجب')
            ->view('emails.homework-reminder');
    }
}
