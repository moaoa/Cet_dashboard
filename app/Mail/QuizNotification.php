<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuizNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $message_content;

    /**
     * Create a new message instance.
     */
    public function __construct($message)
    {
        $this->message_content = $message;
    }

    public function build()
    {
        return $this->subject('إعلان إختبار')
            ->view('emails.quiz-notification');
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
