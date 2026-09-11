<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;

class SelcomSmsService
{
    protected $apiUrl;
    protected $username;
    protected $password;
    protected $sourceId;

    public function __construct()
    {
        $this->apiUrl = config('services.sms.api_url');
        $this->username = config('services.sms.username');
        $this->password = config('services.sms.password');
        // $this->sourceId = config('services.sms.source_id');
    }

    /**
     * Send OTP SMS to client
     *
     * @param string $phoneNumber
     * @param string $otp
     * @param string $clientName
     * @param string $clientId
     * @return array
     */
    public function sendOtp($phoneNumber, $otp, $clientName = null, $clientId = null)
    {
        $message = $this->generateOtpMessage($otp, $clientName);

        return $this->sendSms($phoneNumber, $message, [
            'type' => 'otp',
            'client_id' => $clientId,
            'otp' => $otp,
            'message_id' => 'OTP_' . date('YmdHis') . '_' . strtoupper(substr(md5(uniqid()), 0, 8))
        ]);
    }

    /**
     * Send generic SMS message
     *
     * @param string $phoneNumber
     * @param string $message
     * @param array $metadata
     * @return array
     */
    public function sendSms($phoneNumber, $message, $metadata = [])
    {
        if (empty($this->apiUrl) || empty($this->username) || empty($this->password)) {
            throw new Exception('SMS service is not configured.');
        }

        $formattedPhone = $this->formatPhoneNumber($phoneNumber);

        if (!$this->isValidPhoneNumber($formattedPhone)) {
            throw new Exception('Invalid phone number format');
        }

        try {

            $params = [
                'USERNAME' => $this->username,
                'PASSWORD' => $this->password,
                'DESTADDR' => $formattedPhone,
                'MESSAGE' => $message,
            ];

            Log::info('Sending SMS', [
                'phone' => $formattedPhone,
                'message_type' => $metadata['type'] ?? 'generic',
                'client_id' => $metadata['client_id'] ?? null,
            ]);

            // Retry only on connection issues
            $response = Http::timeout(30)
                ->retry(3, 1000, function ($exception) {
                    return $exception instanceof ConnectionException;
                })
                ->post($this->apiUrl, $params);

            Log::info('SMS Sending Response', [
                'status' => $response->status(),
                'body_preview' => substr($response->body(), 0, 200)
            ]);

            if ($response->ok()) {
                $responseBody = $response->json();

                // Safer parsing
                $results = $responseBody['results'][0] ?? null;
                if (!$results) {
                    throw new Exception('Invalid API response structure');
                }

                $status = $results['status'] ?? 'unknown';
                $msgid = $results['msgid'] ?? null;
                $statustext = $results['statustext'] ?? 'No status text';

                // Status mapping
                $statusMap = [
                    '0' => 'SENT',
                ];
                $messageStatus = $statusMap[$status] ?? 'FAILED';

                $result = [
                    'success' => ($status == '0'),
                    'message_id' => $msgid,
                    'status' => $status,
                    'status_text' => $statustext,
                ];

                Log::info('SMS sent successfully', $result);

                return $result;

            } else {
                Log::error('Failed to send SMS', [
                    'status' => $response->status(),
                    'body_preview' => substr($response->body(), 0, 200),
                ]);

                throw new Exception('Failed to send SMS: ' . $response->status());
            }

        } catch (ConnectionException $e) {
            $errorMessage = $this->getUserFriendlyErrorMessage($e);

            Log::error('Connection error occurred while sending SMS', [
                'error' => $e->getMessage(),
                'phone' => $formattedPhone,
                'user_friendly_message' => $errorMessage
            ]);

            throw new Exception($errorMessage, 0, $e);

        } catch (RequestException $e) {
            $errorMessage = $this->getUserFriendlyErrorMessage($e);

            Log::error('Request error occurred while sending SMS', [
                'error' => $e->getMessage(),
                'phone' => $formattedPhone,
                'user_friendly_message' => $errorMessage
            ]);

            throw new Exception($errorMessage, 0, $e);

        } catch (Exception $e) {
            $errorMessage = $this->getUserFriendlyErrorMessage($e);

            Log::error('Exception occurred while sending SMS', [
                'error' => $e->getMessage(),
                'phone' => $formattedPhone,
                'user_friendly_message' => $errorMessage
            ]);

            throw new Exception($errorMessage, 0, $e);
        }
    }

    /**
     * Generate OTP message
     *
     * @param string $otp
     * @param string|null $clientName
     * @return string
     */
    protected function generateOtpMessage($otp, $clientName = null)
    {
        $greeting = $clientName ? "Dear {$clientName}" : "Dear Customer";

        return "{$greeting}, your OTP is: {$otp}. This code will expire in 10 minutes. Do not share this code with anyone.";
    }

    /**
     * Format phone number to international format
     *
     * @param string $phoneNumber
     * @return string
     */
    protected function formatPhoneNumber($phoneNumber)
    {
        // Remove any non-digit characters
        $phone = preg_replace('/[^0-9]/', '', $phoneNumber);

        //get last 9 digits
        $phone = substr($phone, -9);

        // If it's a 9-digit number, add +255 prefix
        if (strlen($phone) == 9) {
            return '255' . $phone;
        }
        return $phone;
    }

    /**
     * Validate phone number format
     *
     * @param string $phoneNumber
     * @return bool
     */
    protected function isValidPhoneNumber($phoneNumber)
    {
        // Basic validation for Tanzanian phone numbers
        $pattern = '/^255[0-9]{9}$/';
        return preg_match($pattern, $phoneNumber);
    }

    /**
     * Get SMS delivery status
     *
     * @param string $messageId
     * @return array
     */

    /**
     * Get user-friendly error message based on the exception
     *
     * @param Exception $e
     * @return string
     */
    protected function getUserFriendlyErrorMessage($e)
    {
        $errorMessage = $e->getMessage();

        // Check for specific cURL error codes
        if (strpos($errorMessage, 'cURL error 35') !== false) {
            return 'SMS service is temporarily unavailable due to network connection issues. Please try again in a few minutes.';
        }

        if (strpos($errorMessage, 'cURL error 28') !== false) {
            return 'SMS request timed out. Please check your internet connection and try again.';
        }

        if (strpos($errorMessage, 'cURL error 6') !== false) {
            return 'Unable to resolve SMS service hostname. Please check your internet connection and try again.';
        }

        if (strpos($errorMessage, 'cURL error 7') !== false) {
            return 'Unable to connect to SMS service. Please check your internet connection and try again.';
        }

        if (strpos($errorMessage, 'cURL error 52') !== false) {
            return 'SMS service returned an empty response. Please try again later.';
        }

        if (strpos($errorMessage, 'cURL error 56') !== false) {
            return 'Network connection was reset while sending SMS. Please try again.';
        }

        // Check for HTTP status codes
        if (strpos($errorMessage, 'HTTP request returned status code 500') !== false) {
            return 'SMS service is experiencing technical difficulties. Please try again later.';
        }

        if (strpos($errorMessage, 'HTTP request returned status code 503') !== false) {
            return 'SMS service is temporarily unavailable for maintenance. Please try again later.';
        }

        if (strpos($errorMessage, 'HTTP request returned status code 401') !== false) {
            return 'SMS service authentication failed. Please contact support.';
        }

        if (strpos($errorMessage, 'HTTP request returned status code 403') !== false) {
            return 'Access to SMS service is forbidden. Please contact support.';
        }

        // Check for timeout errors
        if (strpos($errorMessage, 'timeout') !== false) {
            return 'SMS request timed out. Please try again.';
        }

        // Check for connection errors
        if (strpos($errorMessage, 'connection') !== false) {
            return 'Unable to connect to SMS service. Please check your internet connection and try again.';
        }

        // Default user-friendly message
        return 'Unable to send SMS at this time. Please try again later or contact support if the problem persists.';
    }
}
