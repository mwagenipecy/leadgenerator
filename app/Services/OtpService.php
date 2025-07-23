<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserOtp;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class OtpService
{
    /**
     * OTP expiry time in minutes
     */
    const OTP_EXPIRY_MINUTES = 10;

    /**
     * Generate and send OTP to user
     */
    public function generateAndSendOtp(User $user): bool
    {
        try {
            Log::info('Starting OTP generation', ['user_id' => $user->id]);

            // Invalidate any existing OTPs for this user
            $this->invalidateExistingOtps($user);

            // Generate new OTP
            $otpCode = UserOtp::generateOtp();
            
            // Create OTP record
            $otp = UserOtp::create([
                'user_id' => $user->id,
                'otp' => $otpCode,
                'expires_at' => Carbon::now()->addMinutes(self::OTP_EXPIRY_MINUTES),
                'is_used' => false
            ]);

            Log::info('OTP record created', [
                'user_id' => $user->id,
                'otp_id' => $otp->id,
                'otp_code' => $otpCode,
                'expires_at' => $otp->expires_at
            ]);

            // Check mail configuration before sending
            $mailDriver = config('mail.default');
            Log::info('Mail configuration', [
                'driver' => $mailDriver,
                'host' => config('mail.mailers.smtp.host'),
                'from_address' => config('mail.from.address')
            ]);

            // Send OTP via email
            try {
                Mail::to($user->email)->send(new OtpMail($user, $otpCode));
                Log::info('OTP email sent successfully', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'otp_code' => $otpCode
                ]);
            } catch (Exception $mailException) {
                Log::error('Failed to send OTP email', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'error' => $mailException->getMessage(),
                    'mail_config' => [
                        'driver' => config('mail.default'),
                        'host' => config('mail.mailers.smtp.host')
                    ]
                ]);
                
                // Still return true since OTP was created in database
                // User can still use resend functionality
                return true;
            }

            return true;
        } catch (Exception $e) {
            Log::error('Failed to generate OTP', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(User $user, string $otpCode): bool
    {
        Log::info('Starting OTP verification', [
            'user_id' => $user->id,
            'provided_otp' => $otpCode
        ]);

        $otp = UserOtp::where('user_id', $user->id)
                      ->where('otp', $otpCode)
                      ->where('is_used', false)
                      ->where('expires_at', '>', Carbon::now())
                      ->first();

        if (!$otp) {
            // Check if OTP exists but is used or expired
            $existingOtp = UserOtp::where('user_id', $user->id)
                                 ->where('otp', $otpCode)
                                 ->first();
            
            if ($existingOtp) {
                Log::warning('OTP exists but invalid', [
                    'user_id' => $user->id,
                    'provided_otp' => $otpCode,
                    'is_used' => $existingOtp->is_used,
                    'is_expired' => $existingOtp->isExpired(),
                    'expires_at' => $existingOtp->expires_at
                ]);
            } else {
                Log::warning('OTP not found', [
                    'user_id' => $user->id,
                    'provided_otp' => $otpCode
                ]);
            }
            return false;
        }

        // Mark OTP as used
        $otp->markAsUsed();

        Log::info('OTP verified successfully', [
            'user_id' => $user->id,
            'otp_id' => $otp->id
        ]);

        return true;
    }

    /**
     * Check if user has a valid OTP
     */
    public function hasValidOtp(User $user): bool
    {
        return $user->hasValidOtp();
    }

    /**
     * Get remaining time for OTP expiry in seconds
     */
    public function getOtpRemainingTime(User $user): ?int
    {
        $otp = $user->latestValidOtp();

        if (!$otp) {
            return null;
        }

        $remainingSeconds = Carbon::now()->diffInSeconds($otp->expires_at, false);
        return $remainingSeconds > 0 ? $remainingSeconds : 0;
    }

    /**
     * Check if user can request a new OTP (rate limiting)
     */
    public function canResendOtp(User $user): bool
    {
        $remainingTime = $this->getOtpRemainingTime($user);
        
        // Allow resend only if less than 1 minute remaining or no valid OTP exists
        return $remainingTime === null || $remainingTime < 60;
    }

    /**
     * Invalidate all existing OTPs for a user
     */
    private function invalidateExistingOtps(User $user): void
    {
        $updated = UserOtp::where('user_id', $user->id)
               ->where('is_used', false)
               ->update(['is_used' => true]);
               
        Log::info('Invalidated existing OTPs', [
            'user_id' => $user->id,
            'count' => $updated
        ]);
    }

    /**
     * Clean up expired OTPs (can be called in a scheduled job)
     */
    public function cleanupExpiredOtps(): int
    {
        return UserOtp::where('expires_at', '<', Carbon::now())
                     ->delete();
    }
}