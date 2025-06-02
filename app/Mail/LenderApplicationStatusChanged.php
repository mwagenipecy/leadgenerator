<?php

namespace App\Mail;

use App\Models\Lender;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LenderApplicationStatusChanged extends Mailable
{
    use SerializesModels;

    public function __construct(
        public Lender $lender,
        public string $status
    ) {}

    public function envelope(): Envelope
    {
        $subject = match($this->status) {
            'approved' => 'Application Approved - Lead Generator',
            'rejected' => 'Application Update - Lead Generator',
            'suspended' => 'Account Suspended - Lead Generator',
            default => 'Application Status Update - Lead Generator'
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.lender-status-changed',
        );
    }
}