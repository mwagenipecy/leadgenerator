<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PromotionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $promotion;

    /**
     * Create a new notification instance.
     */
    public function __construct($promotion)
    {
        $this->promotion = $promotion;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = [];
        
        if ($this->promotion->send_notification) {
            $channels[] = 'database';
        }
        
        if ($this->promotion->send_email) {
            $channels[] = 'mail';
        }
        
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable)
    {
        return (new MailMessage)
            ->subject($this->promotion->title)
            ->view('emails.promotion', [
                'promotion' => $this->promotion,
                'user' => $notifiable,
                'actionUrl' => url('/dashboard'),
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->promotion->title,
            'message' => $this->promotion->message,
            'promotion_id' => $this->promotion->id,
            'type' => 'promotion',
            'link' => url('/dashboard'), // Default link to dashboard
        ];
    }
}
