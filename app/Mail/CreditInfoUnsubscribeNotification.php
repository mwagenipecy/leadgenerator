<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CreditInfoUnsubscribeNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $serviceName,
        public string $unsubscribedAt
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('creditinfo_alert.email_unsubscribe_subject', ['name' => $this->serviceName]),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.credit-info-unsubscribe',
        );
    }
}
