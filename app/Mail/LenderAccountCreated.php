<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LenderAccountCreated extends Mailable
{
    use SerializesModels;

    public function __construct(
        public User $user,
        public string $password
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Lender Account Has Been Created - Lead Generator',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.lender-account-created',
        );
    }
}


