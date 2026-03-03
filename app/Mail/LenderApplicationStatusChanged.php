<?php

namespace App\Mail;

use App\Models\Lender;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LenderApplicationStatusChanged extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Lender $lender,
        public string $status
    ) {}

    public function envelope(): Envelope
    {
        $subject = match($this->status) {
            'approved' => 'Application approved – Fanikisha Marketplace',
            'rejected' => 'Application update – Fanikisha Marketplace',
            'suspended' => 'Account suspended – Fanikisha Marketplace',
            default => 'Application status update – Fanikisha Marketplace'
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