<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $title;
    public $message;
    public $link;

    /**
     * Create a new notification instance.
     */
    public function __construct($title = null, $message = null, $link = null)
    {
        $this->title = $title ?? 'Welcome to Fanikisha Marketplace!';
        $this->message = $message ?? 'Thank you for joining us! We\'re excited to have you on board. Complete your profile to get started with loan applications.';
        $this->link = $link ?? url('/application/profile');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification (optional).
     */
    public function toMail(object $notifiable)
    {
        return (new MailMessage)
            ->subject($this->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line($this->message)
            ->action('Complete Your Profile', $this->link)
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'type' => 'welcome',
            'link' => $this->link,
        ];
    }
}

