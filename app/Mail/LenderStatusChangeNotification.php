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
        $action = $this->isDisabled ? 'Disabled' : 'Enabled';
        $subject = $this->isAdminNotification 
            ? "Lender Account {$action} - {$this->lender->company_name}"
            : "Your Lender Account Has Been {$action}";

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.lender-status-change-notification',
        );
    }
}

