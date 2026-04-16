<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public User $user;
    public string $otp;
    public string $mailLocale;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $otp, ?string $locale = null)
    {
        $this->user = $user;
        $this->otp = $otp;
        $this->mailLocale = $locale ?? app()->getLocale();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->mailLocale === 'sw'
            ? 'Nambari yako ya uthibitishaji - Fanikisha Marketplace'
            : 'Your verification code - Fanikisha Marketplace';

        return new Envelope(
            subject: $subject,
            from: config('mail.from.address', 'noreply@fanikisha.com'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.otp',
            with: [
                'user' => $this->user,
                'otp' => $this->otp,
                'expiryMinutes' => 10,
                'locale' => $this->mailLocale,
            ]
        );
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