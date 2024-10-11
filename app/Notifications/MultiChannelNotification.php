<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class MultiChannelNotification extends Notification
{
    use Queueable;

    private $details;

    // Accepting the details of the notification
    public function __construct($details)
    {
        $this->details = $details;
    }

    // Define the channels this notification will be sent through
    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
        // return ['mail', 'database'];
    }

    // Define how the email will be structured
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->details['subject'])
            ->line($this->details['body'])
            // ->action('Notification Action', $this->details['url'])
            ->line('Thank you for using our application!');
    }

    // Define the structure of a database notification (web notifications)
    public function toDatabase($notifiable)
    {
        return new DatabaseMessage([
            'title' => $this->details['title'],
            'body' => $this->details['body'],
            //'url' => $this->details['url'],
        ]);
    }

    // Define how the notification will be broadcasted (e.g., WebSockets, Pusher)
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => $this->details['title'],
            'body' => $this->details['body'],
            //'url' => $this->details['url'],
        ]);
    }
}
