<?php

namespace App\Services;

use App\Models\User;
use App\Models\OtpCode;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
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
            $otpCode = OtpCode::generateOtp();
            
            // Create OTP record
            $otp = OtpCode::create([
                'user_id' => $user->id,
                'otp_hash' => Hash::make($otpCode),
                'expires_at' => Carbon::now()->addMinutes(self::OTP_EXPIRY_MINUTES),
                'is_used' => false
            ]);

            Log::info('OTP record created', [
                'user_id' => $user->id,
                'expires_at' => $otp->expires_at
            ]);

            // Check mail configuration before sending
            $mailDriver = config('mail.default');
            Log::info('Mail configuration', [
                'driver' => $mailDriver,
                'from_address' => config('mail.from.address'),
                'outlook_configured' => !empty(config('services.outlook.client_id'))
            ]);

            // Send OTP via email
            try {
                Mail::to($user->email)->send(new OtpMail($user, $otpCode, app()->getLocale()));
                Log::info('OTP email sent successfully', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);
            } catch (Exception $mailException) {
                Log::error('Failed to send OTP email', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'error' => $mailException->getMessage(),
                    'mail_config' => [
                        'driver' => config('mail.default'),
                        'outlook_configured' => !empty(config('services.outlook.client_id'))
                    ]
                ]);
       
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
        ]);

        $otp = OtpCode::where('user_id', $user->id)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->orderByDesc('id')
            ->first();

        if (!$otp || !Hash::check($otpCode, $otp->otp_hash)) {
            Log::warning('OTP verification failed', ['user_id' => $user->id]);
            return false;
        }

        // Mark OTP as used
        $otp->markAsUsed();

        // Enforce single-use semantics strictly: invalidate any other active OTP sessions
        // for this user immediately after the first successful verification.
        OtpCode::where('user_id', $user->id)
            ->where('is_used', false)
            ->update(['is_used' => true]);

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
        $updated = OtpCode::where('user_id', $user->id)
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
        return OtpCode::where('expires_at', '<', Carbon::now())
                     ->delete();
    }

    
}