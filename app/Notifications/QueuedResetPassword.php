<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class QueuedResetPassword extends ResetPassword implements ShouldQueue
{
    use Queueable;

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        $expiration = config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60);

        return (new MailMessage)
            ->subject('Reset your password – Fanikisha Marketplace')
            ->view('emails.reset-password', [
                'url' => $url,
                'expiration' => $expiration,
            ]);
    }
}

