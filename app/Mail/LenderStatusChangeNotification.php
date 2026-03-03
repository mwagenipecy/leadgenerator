<?php

namespace App\Mail;

use App\Models\Lender;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LenderStatusChangeNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Lender $lender,
        public bool $isDisabled,
        public ?User $user = null,
        public bool $isAdminNotification = false
    ) {}

    public function envelope(): Envelope
    {
        $action = $this->isDisabled ? 'disabled' : 'enabled';
        $subject = $this->isAdminNotification
            ? "Lender account {$action} – {$this->lender->company_name} (Fanikisha Marketplace)"
            : "Your lender account has been {$action} – Fanikisha Marketplace";

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.lender-status-change-notification',
        );
    }
}

