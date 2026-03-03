<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CreditInfoAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Alert type key (e.g. alert_when_report_retrieved).
     */
    public string $alertType;

    public string $title;
    public string $message;
    public ?string $link;
    public bool $sendEmail;

    public function __construct(
        string $alertType,
        string $title,
        string $message,
        ?string $link = null,
        bool $sendEmail = true
    ) {
        $this->alertType = $alertType;
        $this->title = $title;
        $this->message = $message;
        $this->link = $link ?? url('/dashboard');
        $this->sendEmail = $sendEmail;
    }

    /**
     * Get the notification's delivery channels. Always queued (ShouldQueue).
     */
    public function via(object $notifiable): array
    {
        $channels = ['database'];
        if ($this->sendEmail) {
            $channels[] = 'mail';
        }
        return $channels;
    }

    public function toMail(object $notifiable)
    {
        return (new MailMessage)
            ->subject($this->title)
            ->greeting(__('creditinfo_alert.title'))
            ->line($this->message)
            ->action(__('common.view'), $this->link);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'type' => 'creditinfo_alert',
            'alert_type' => $this->alertType,
            'link' => $this->link,
        ];
    }
}
