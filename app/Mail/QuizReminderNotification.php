<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuizReminderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $message_content;
    public function build()
    {
        return $this->subject('تذكير الاختبار')
            ->view('emails.quiz-reminder');
    }

    /**
     * Create a new message instance.
     */
    public function __construct($message_content)
    {
        $this->message_content = $message_content;
    }
}
