<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminAccountCreated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $password
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your admin account is ready - Fanikisha Marketplace',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-account-created',
        );
    }
}
