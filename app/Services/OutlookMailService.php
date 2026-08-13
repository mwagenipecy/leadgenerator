<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Exception;

class OutlookMailService
{
    protected string $clientId;
    protected string $clientSecret;
    protected string $tenantId;
    protected string $sharedMailbox;
    protected string $fromEmail;

    public function __construct()
    {
        $this->clientId = config('services.outlook.client_id');
        $this->clientSecret = config('services.outlook.client_secret');
        $this->tenantId = config('services.outlook.tenant_id');
        $this->sharedMailbox = config('services.outlook.shared_mailbox');
        $this->fromEmail = config('services.outlook.from_email');

        // Validate configuration
        if (empty($this->clientId) || empty($this->clientSecret) || empty($this->tenantId)) {
            throw new Exception('Outlook mail service is not properly configured. Please check your .env file for OUTLOOK_CLIENT_ID, OUTLOOK_CLIENT_SECRET, and TENANT_ID.');
        }

        if (empty($this->sharedMailbox) || empty($this->fromEmail)) {
            throw new Exception('Outlook mail service is not properly configured. Please check your .env file for SHARED_MAILBOX and FROM_EMAIL.');
        }
    }

    /**
     * Get access token using client credentials flow
     */
    public function getAccessToken(): string
    {
        $cacheKey = 'outlook_access_token';

        return Cache::remember($cacheKey, 3600, function () {
            $tokenEndpoint = "https://login.microsoftonline.com/{$this->tenantId}/oauth2/v2.0/token";

            $response = Http::asForm()->post($tokenEndpoint, [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'scope' => 'https://graph.microsoft.com/.default',
                'grant_type' => 'client_credentials',
            ]);

            if (!$response->successful()) {
                Log::error('Failed to get Outlook access token', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw new Exception('Failed to authenticate with Outlook: ' . $response->body());
            }

            $data = $response->json();
            return $data['access_token'];
        });
    }

    /**
     * Send email via Microsoft Graph API
     */
    public function sendEmail(string $to, string $subject, string $htmlBody, ?string $from = null): bool
    {
        try {
            $accessToken = $this->getAccessToken();
            $fromEmail = $from ?? $this->fromEmail;

            $message = [
                'message' => [
                    'subject' => $subject,
                    'body' => [
                        'contentType' => 'HTML',
                        'content' => $htmlBody,
                    ],
                    'toRecipients' => [
                        [
                            'emailAddress' => [
                                'address' => $to,
                            ],
                        ],
                    ],
                    'from' => [
                        'emailAddress' => [
                            'address' => $fromEmail,
                        ],
                    ],
                ],
            ];

            // Use the shared mailbox to send the email
            $sendEndpoint = "https://graph.microsoft.com/v1.0/users/{$this->sharedMailbox}/sendMail";

            $response = Http::withToken($accessToken)
                ->post($sendEndpoint, $message);

            if (!$response->successful()) {
                Log::error('Failed to send email via Outlook', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'to' => $to,
                    'subject' => $subject,
                ]);
                return false;
            }

            Log::info('Email sent successfully via Outlook', [
                'to' => $to,
                'subject' => $subject,
            ]);

            return true;
        } catch (Exception $e) {
            Log::error('Exception while sending email via Outlook', [
                'error' => $e->getMessage(),
                'to' => $to,
                'subject' => $subject,
            ]);
            throw $e;
        }
    }

    /**
     * Send email with attachments
     */
    public function sendEmailWithAttachments(
        string $to,
        string $subject,
        string $htmlBody,
        array $attachments = [],
        ?string $from = null
    ): bool {
        try {
            $accessToken = $this->getAccessToken();
            $fromEmail = $from ?? $this->fromEmail;

            $message = [
                'message' => [
                    'subject' => $subject,
                    'body' => [
                        'contentType' => 'HTML',
                        'content' => $htmlBody,
                    ],
                    'toRecipients' => [
                        [
                            'emailAddress' => [
                                'address' => $to,
                            ],
                        ],
                    ],
                    'from' => [
                        'emailAddress' => [
                            'address' => $fromEmail,
                        ],
                    ],
                ],
            ];

            // Add attachments if provided
            if (!empty($attachments)) {
                $message['message']['attachments'] = [];
                foreach ($attachments as $attachment) {
                    $message['message']['attachments'][] = [
                        '@odata.type' => '#microsoft.graph.fileAttachment',
                        'name' => $attachment['name'],
                        'contentType' => $attachment['content_type'] ?? 'application/octet-stream',
                        'contentBytes' => base64_encode($attachment['content']),
                    ];
                }
            }

            $sendEndpoint = "https://graph.microsoft.com/v1.0/users/{$this->sharedMailbox}/sendMail";

            $response = Http::withToken($accessToken)
                ->post($sendEndpoint, $message);

            if (!$response->successful()) {
                Log::error('Failed to send email with attachments via Outlook', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'to' => $to,
                    'subject' => $subject,
                ]);
                return false;
            }

            return true;
        } catch (Exception $e) {
            Log::error('Exception while sending email with attachments via Outlook', [
                'error' => $e->getMessage(),
                'to' => $to,
                'subject' => $subject,
            ]);
            // return false;
            throw $e;
        }
    }
}

