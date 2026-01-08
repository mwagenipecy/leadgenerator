<?php

namespace App\Mail\Transport;

use App\Services\OutlookMailService;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Message;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class OutlookTransport extends AbstractTransport
{
    protected OutlookMailService $outlookService;

    public function __construct(OutlookMailService $outlookService, ?EventDispatcherInterface $dispatcher = null)
    {
        parent::__construct($dispatcher);
        $this->outlookService = $outlookService;
    }

    /**
     * {@inheritDoc}
     */
    protected function doSend(SentMessage $message): void
    {
        $email = $message->getOriginalMessage();

        if (!$email instanceof Email) {
            return;
        }

        $to = $this->getFirstEmailAddress($email->getTo());
        $from = $this->getFirstEmailAddress($email->getFrom());
        $subject = $email->getSubject() ?? '';
        $htmlBody = $email->getHtmlBody() ?? $email->getTextBody() ?? '';

        // Convert text body to HTML if only text is provided
        if (empty($htmlBody) && !empty($email->getTextBody())) {
            $htmlBody = nl2br(e($email->getTextBody()));
        }

        // Handle attachments
        $attachments = [];
        foreach ($email->getAttachments() as $attachment) {
            $attachments[] = [
                'name' => $attachment->getFilename(),
                'content_type' => $attachment->getContentType(),
                'content' => $attachment->getBody(),
            ];
        }

        if (!empty($attachments)) {
            $this->outlookService->sendEmailWithAttachments($to, $subject, $htmlBody, $attachments, $from);
        } else {
            $this->outlookService->sendEmail($to, $subject, $htmlBody, $from);
        }
    }

    /**
     * Get the first email address from an array of addresses
     */
    protected function getFirstEmailAddress(array $addresses): string
    {
        if (empty($addresses)) {
            return '';
        }

        $firstAddress = $addresses[0];
        return $firstAddress->getAddress();
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return 'outlook';
    }
}

